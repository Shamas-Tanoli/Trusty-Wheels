<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceJobPassenger extends Model
{
     protected $fillable = [
        'service_job_id',
        'passenger_id',
        'status',
    ];

    public function serviceJob()
    {
        return $this->belongsTo(ServiceJob::class, 'service_job_id');
    }

    public function passenger()
    {
        return $this->belongsTo(BookingPassenger::class, 'passenger_id');
    }
}
