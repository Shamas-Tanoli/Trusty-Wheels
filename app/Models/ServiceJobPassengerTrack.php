<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobPassengerTrack extends Model
{
    use HasFactory;

    protected $table = 'service_job_passenger_track';

    protected $fillable = [
        'service_job_track_id',
        'service_job_passengers_id',
        'status',
        'pickup_trip_one',
        'dropoff_trip_one',
        'pickup_trip_two',
        'dropoff_trip_two',
    ];

    // Relationships
    public function jobTrack()
    {
        return $this->belongsTo(ServiceJobTrack::class, 'service_job_track_id');
    }

    public function passenger()
    {
        return $this->belongsTo(ServiceJobPassenger::class, 'service_job_passengers_id');
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
