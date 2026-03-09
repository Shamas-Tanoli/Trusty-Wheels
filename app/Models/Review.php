<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
     protected $table = 'reviews';

    protected $fillable = [
        'image_path',
        'title',
        'sub_title'
    ];
}
