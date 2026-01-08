<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    protected $fillable = [
        'driver_id',
        'cnic_images',
        'license_images',
        'profile_image',
        'verification_image',
        'other_document'
    ];

    protected $casts = [
    'cnic_images'    => 'array',
    'license_images' => 'array',
    'other_document' => 'array',
];

    // ✅ Each document belongs to one Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
