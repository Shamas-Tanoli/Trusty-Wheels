<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'customer_id',
        'message',
        'rating',
        'is_approved'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
