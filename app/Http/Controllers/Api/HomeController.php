<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Serve;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHomeData()
{
    $sliders = Slider::where('is_active',1)
        ->orderBy('order','asc')
        ->get()
        ->map(function ($item) {
            return [
                'id'       => $item->id,
                'title'    => $item->title,
                'subtitle' => $item->subtitle,
                'image'    => url($item->image),
                'order'    => $item->order,
            ];
        });

    $serves = Serve::all()->map(function ($item) {
        return [
            'id'       => $item->id,
            'title'    => $item->title,
            'subtitle' => $item->sub_title,
            'image'    => url($item->image_path),
        ];
    });

     $services = Serve::all()->map(function ($item) {
        return [
            'id'       => $item->id,
            'title'    => $item->title,
            'subtitle' => $item->sub_title,
            'image'    => url($item->image_path),
        ];
    });

    $reviews = Review::all()->map(function ($item) {
        return [
            'id'       => $item->id,
            'name'    => $item->title,
            'review' => $item->sub_title,
            'image'    => url($item->image_path),
        ];
    });

    return response()->json([
        'success' => true,
        'data' => [
            'sliders' => $sliders,
            'serves'  => $serves,
            'reviews' => $reviews,
            'services' => $services,
        ]
    ]);
}
}
