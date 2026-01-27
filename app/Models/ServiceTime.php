<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTime extends Model
{
     protected $fillable = [
        'service_id',
        'timing'
    ];

    public function service() 
    {
        return $this->belongsTo(Service::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class, 'service_time_id');
    }
    public function jobs()
    {
        return $this->hasMany(ServiceJob::class, 'service_time_id');
    }
    
}
