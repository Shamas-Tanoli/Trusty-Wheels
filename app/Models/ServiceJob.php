<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceJob extends Model
{
    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'status',
        'job_date',
    ];

    public function passengers()
    {
        return $this->hasMany(ServiceJobPassenger::class);
    }

      public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Vehicle relation
    public function vehicle()
    {
        return $this->belongsTo(ServiceVehicle::class, 'vehicle_id');
    }
    
}
