<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'amenities_id'
    ];
    
    public $timestamps = true;
    // VehicleAmenity belongs to a Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // VehicleAmenity belongs to an Amenity
    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenities_id');
    }
}
