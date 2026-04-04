<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInquiry extends Model
{
    use HasFactory;

    protected $table = 'customer_inquiry';

    protected $fillable = [
        'name',
        'contact',
        'pickup_address',
        'dropoff_address',
        'num_of_passenger',
        'message'
    ];
}