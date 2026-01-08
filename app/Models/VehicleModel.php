<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel  extends Model 
{

    protected $fillable = ['make_id', 'name'];


    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    // VehicleModel has many Vehicles
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
