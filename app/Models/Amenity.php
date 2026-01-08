<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name'];


    // Amenity has many VehicleAmenities
    public function vehicleAmenities()
    {
        return $this->hasMany(VehicleAmenity::class, 'amenities_id');
    }
}
