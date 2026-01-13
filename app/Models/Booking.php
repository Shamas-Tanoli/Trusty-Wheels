<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
   
    protected $fillable = [
        'plan_id',
        'customer_id',
        'service_time_id',
        'service_id',
        'town_id',
        'status',
        'booking_type_id'
    ];

     public function passengers()
    {
        return $this->hasMany(BookingPassenger::class, 'booking_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

   

    public function serviceTime()
    {
        return $this->belongsTo(ServiceTime::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
