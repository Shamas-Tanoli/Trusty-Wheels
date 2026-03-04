<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class mobileAppController extends Controller
{
   public function sliderIndex()
   {
      $sliders = Slider::get();
      return view('admin.content.pages.mobileapp.slider', compact('sliders'));
   }

   public function sliderstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slider_id'     => 'nullable|array',
            'title'         => 'required|array',
            'title.*'       => 'required|string|max:255',
            'subtitle.*'    => 'nullable|string|max:255',
            'image'         => 'nullable|array',
            'image.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'existing_image'=> 'nullable|array',
            'order.*'       => 'required|integer',
            'is_active.*'   => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        $submittedIds = []; // Track IDs sent from form

        foreach ($request->title as $index => $title) {

            $sliderId = $request->slider_id[$index] ?? null;
            $imagePath = $request->existing_image[$index] ?? null;

            /*
            |--------------------------------------------------------------------------
            | IMAGE UPLOAD
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('image') && isset($request->file('image')[$index])) {

                $file = $request->file('image')[$index];

                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('assets/img/slider');

                $file->move($destinationPath, $fileName);

                $newImagePath = 'assets/img/slider/' . $fileName;

                // Delete old image if exists
                if ($sliderId) {
                    $oldSlider = Slider::find($sliderId);
                    if ($oldSlider && $oldSlider->image && file_exists(public_path($oldSlider->image))) {
                        unlink(public_path($oldSlider->image));
                    }
                }

                $imagePath = $newImagePath;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE OR CREATE
            |--------------------------------------------------------------------------
            */
            if ($sliderId) {

                $slider = Slider::find($sliderId);

                if ($slider) {
                    $slider->update([
                        'title'     => $title,
                        'subtitle'  => $request->subtitle[$index] ?? null,
                        'image'     => $imagePath,
                        'order'     => $request->order[$index] ?? 0,
                        'is_active' => isset($request->is_active[$index]) ? 1 : 0,
                    ]);

                    $submittedIds[] = $slider->id;
                }

            } else {

                $newSlider = Slider::create([
                    'title'     => $title,
                    'subtitle'  => $request->subtitle[$index] ?? null,
                    'image'     => $imagePath,
                    'order'     => $request->order[$index] ?? 0,
                    'is_active' => isset($request->is_active[$index]) ? 1 : 0,
                ]);

                $submittedIds[] = $newSlider->id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED SLIDERS
        |--------------------------------------------------------------------------
        */
        $existingSliders = Slider::pluck('id')->toArray();

        $toDelete = array_diff($existingSliders, $submittedIds);

        if (!empty($toDelete)) {
            $slidersToDelete = Slider::whereIn('id', $toDelete)->get();

            foreach ($slidersToDelete as $slider) {
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                $slider->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sliders saved successfully!'
        ]);
    }
}
