<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSection extends Model
{
     protected $table = 'service_section';

    protected $fillable = [
        'image_path',
        'title', 
        'sub_title'
    ];
}
