<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodeUser extends Model
{
    use HasFactory;

    protected $table = 'promo_codes_user';

    protected $fillable = [
        'user_id',
        'promo_code_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
