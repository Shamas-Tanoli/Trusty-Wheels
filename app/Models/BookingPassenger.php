<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    protected $table = 'booking_passengers';

     protected $fillable = [
        'customer_id',
        'booking_id',
        'name',
        'pickup_time',
        'dropoff_time',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_latitude',
        'dropoff_longitude',
        'pickup_location',
        'dropoff_location',
        'plan_id'
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Passenger belongs to customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
