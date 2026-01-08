<?php

namespace App\Models;

use App\Models\DriverDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;


  


   protected $fillable = [
    'user_id',
    'father_name',
         'cnic',
        'contact',
        'emergency_contact',
        'blood_group',
        'address',
        'verification_status',
        'city_id',
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    


 public function documents()
    {
        return $this->hasOne(DriverDocument::class, 'driver_id');
    }

    // (Optional) City relation
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   

    // Relationship with Bookings (as Driver)
   
    public function driverDetail()
    {
        return $this->hasOne(DriverDetail::class, 'user_id');
    }
}
