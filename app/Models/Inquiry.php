<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'name',
        'email',
        'phone',
        'message',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
