<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobTripTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_job_track_id',
        'driver_id',
        'vehicle_id',
        'trip_one_status',
        'trip_two_status',
    ];

    // Relationships
    public function jobTrack()
    {
        return $this->belongsTo(ServiceJobTrack::class, 'service_job_track_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(ServiceVehicle::class, 'vehicle_id');
    }

    public function serviceJob()
    {
        return $this->hasOneThrough(
            ServiceJob::class,
            ServiceJobTrack::class,
            'id',                  
            'id',                  
            'service_job_track_id',
            'service_job_id'       
        );
    }


}
