<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name'];


    // Location has many Vehicles
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    
}
