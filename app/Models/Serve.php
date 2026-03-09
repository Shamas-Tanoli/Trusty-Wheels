<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serve extends Model
{
    use HasFactory;

     protected $table = 'serve';

    protected $fillable = [
        'image_path',
        'title',
        'sub_title'
    ];
}