<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDetail extends Model
{

    protected $table = 'driver_detail';

    protected $fillable = [
        'user_id',
        'phone',
        'license_nbr',
        'id_nbr',
        'address',
        'dob',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'user_id');
    }
}
