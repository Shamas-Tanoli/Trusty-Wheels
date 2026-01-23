<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

   
    protected $table = 'discount';

    
    protected $fillable = [
        'type',
        'value',
        'person',
    ];

   
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
}
