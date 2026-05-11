<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    use HasFactory;

    protected $table = 'customer_invoices';

    protected $fillable = [
        'customer_id',
        'invoice_for_date',
        'total_amount',
        'discounted_total',
        'after_discount',
        'discount_type',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'invoice_for_date' => 'date',
        'due_date'         => 'date',
        'paid_at'          => 'datetime',
        'total_amount'     => 'decimal:2',
        'discounted_total' => 'decimal:2',
        'after_discount'   => 'decimal:2',
    ];

    /**
     * Relationship: Invoice belongs to a customer (User)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}