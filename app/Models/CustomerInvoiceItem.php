<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoiceItem extends Model
{
   

    protected $fillable = [

        'invoice_id',
        'subscription_id',
        'amount',
    ];

    protected $casts = [

        'amount' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Invoice Relationship
    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class, 'invoice_id');
    }

    // Subscription Relationship
    public function subscription()
    {
        return $this->belongsTo(CustomerSubscription::class, 'subscription_id');
    }
}
