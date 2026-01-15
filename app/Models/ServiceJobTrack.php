<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_job_id',
        'status',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(ServiceJob::class, 'service_job_id');
    }

    public function tripTracks()
    {
        return $this->hasMany(ServiceJobTripTrack::class, 'service_job_track_id');
    }

    public function passengerTracks()
    {
        return $this->hasMany(ServiceJobPassengerTrack::class, 'service_job_track_id');
    }
}
