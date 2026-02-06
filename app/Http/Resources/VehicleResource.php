<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title'             => $this->title,
            'description'       => $this->description,
            'short_description' => $this->short_description,
            'year'              => $this->year,
            'mileage'           => $this->mileage,
            'rent'              => (float) $this->rent,
            'status'            => $this->status,

            'specs' => [
                'transmission' => $this->transmission,
                'fuel_type'    => $this->fuel_type,
                'doors'        => $this->door,
                'seats'        => $this->seats,
            ],

            'make'  => $this->make->name,
            'model' => $this->vehicleModel->name,
            'type'  => $this->vehicleType->name,
            'location' => $this->location->name,

            'amenities' => $this->vehicleAmenities
                ->pluck('name')
                ->values(),

            'images' => $this->vehicleImages
                ->sortBy('image_order')
                ->map(function ($img) {
                    return [
                        
                        'url'   => asset('storage/vehicles/' . $img->image_url),
                    ];
                })
                ->values(),
        ];
    }
}
