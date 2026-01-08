<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_id',
        'vehicle_model_id',
        'vehicle_type_id',
        'location_id',
        'title',
        'description',
        'short_Description',
        'vin_nbr',
        'lic_plate_nbr',
        'transmission',
        'fuel_type',
        'door',
        'seats',
        'year',
        'mileage',
        'rent',
        'status'
    ];

    // Vehicle belongs to a Make
    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    // Vehicle belongs to a VehicleModel
    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    // Vehicle belongs to a VehicleType
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    // Vehicle belongs to a Location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Vehicle has many VehicleAmenities
    public function vehicleAmenities()
    {
        return $this->belongsToMany(Amenity::class, 'vehicle_amenities', 'vehicle_id', 'amenities_id') ->withTimestamps();
    }

    // Vehicle has many VehicleImages
    public function vehicleImages()
    {
        return $this->hasMany(VehicleImage::class);
    }

    // Relationship with Bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

     public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }
}
