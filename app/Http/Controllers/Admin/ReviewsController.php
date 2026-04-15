<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Serve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReviewsController extends Controller
{
     public function reviewsIndex()
   {
    $sliders = Review::get();
      return view('admin.content.pages.reviews.index', compact('sliders'));
   }

     public function reviewsStore(Request $request)
    {

   
        $validator = Validator::make($request->all(), [
            'slider_id'     => 'nullable|array',
            'title'         => 'required|array',
            'title.*'       => 'required|string|max:255',
            'subtitle.*'    => 'nullable|string|max:255',
            'image'         => 'nullable|array',
            'image.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'existing_image'=> 'nullable|array',
           
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
                $destinationPath = public_path('assets/img/review');

                $file->move($destinationPath, $fileName);

                $newImagePath = 'assets/img/review/' . $fileName;

                // Delete old image if exists
                if ($sliderId) {
                    $oldSlider = Review::find($sliderId);
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

                $slider = Review::find($sliderId);

                if ($slider) {
                    $slider->update([
                        'title'     => $title,
                        'sub_title'  => $request->subtitle[$index] ?? null,
                        'image_path'     => $imagePath,
                        
                    ]);

                    $submittedIds[] = $slider->id;
                }

            } else {

                $newSlider = Review::create([
                    'title'     => $title,
                    'sub_title'  => $request->subtitle[$index] ?? null,
                    'image_path'     => $imagePath,
                    
                ]);

                $submittedIds[] = $newSlider->id;
            }
        }


      
        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED SLIDERS
        |--------------------------------------------------------------------------
        */
        $existingSliders = Review::pluck('id')->toArray();

        $toDelete = array_diff($existingSliders, $submittedIds);

        if (!empty($toDelete)) {
            $slidersToDelete = Review::whereIn('id', $toDelete)->get();

            foreach ($slidersToDelete as $slider) {
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                $slider->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'REview saved successfully!'
        ]);
    }
    public function ReviewsController(){
        
    }
}
