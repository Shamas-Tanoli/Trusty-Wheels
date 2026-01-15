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
    public function jobTrack()
    {
        return $this->hasOne(ServiceJobTrack::class, 'service_job_id');
    }

    public function tripTrack()
    {
        return $this->hasOneThrough(
            ServiceJobTripTrack::class,
            ServiceJobTrack::class,
            'service_job_id',
            'service_job_track_id',
            'id',
            'id'
        );
    }

    public function passengerTracks()
    {
        return $this->hasManyThrough(
            ServiceJobPassengerTrack::class,
            ServiceJobTrack::class,
            'service_job_id',         
            'service_job_track_id',   
            'id',                     
            'id'               
        );
    }
}
