<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    // Mass assignment
    protected $fillable = [
        'name',
        'price',
        'area_from_id',
        'area_to_id',
        'status',
        'service_time_id'
    ];

    /**
     * Pickup Area (From)
     */
    public function areaFrom()
    {
        return $this->belongsTo(Town::class, 'area_from_id');
    }

    /**
     * Drop Area (To)
     */
    public function areaTo()
    {
        return $this->belongsTo(Town::class, 'area_to_id');
    }

    
     public function serviceTime()
    {
        return $this->belongsTo(ServiceTime::class, 'service_time_id');
    }
   

    /**
     * Active Plans Scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

     public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }
    
}
