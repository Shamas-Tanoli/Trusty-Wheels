<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceVehicle extends Model
{
    protected $fillable = [
        'name',
        'number_plate',
        'image',
    ];
}
