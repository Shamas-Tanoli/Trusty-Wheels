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


    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getCnicImagesUrlsAttribute()
    {
        $images = json_decode($this->cnic_images, true) ?: [];
        return collect($images)->map(fn($img) => asset($img))->toArray();
    }

    public function getLicenseImagesUrlsAttribute()
    {
        $images = json_decode($this->license_images, true) ?: [];
        return collect($images)->map(fn($img) => asset($img))->toArray();
    }

    public function getOtherDocumentUrlsAttribute()
    {
        $images = json_decode($this->other_document, true) ?: [];
        return collect($images)->map(fn($img) => asset($img))->toArray();
    }
}
