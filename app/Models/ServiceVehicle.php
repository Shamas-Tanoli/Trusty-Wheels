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

    public function jobs()
    {
        return $this->hasMany(ServiceJob::class, 'vehicle_id');
    }

    protected $appends = ['full_image_url'];


    public function getFullImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }
    
}
