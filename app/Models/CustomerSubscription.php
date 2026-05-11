<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [

        'customer_id',
        'booking_id',
        'passenger_id',
        'plan_id',
        'start_date',
        'end_date',
        'price',
        'payment_type',
        'status',
    ];

    protected $casts = [

        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function passenger()
    {
        return $this->belongsTo(BookingPassenger::class, 'passenger_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}