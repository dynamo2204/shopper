<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'status',

        'shipping_first_name',
        'shipping_last_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'shipping_postal_code',
        'shipping_city',
        'shipping_method',
        'shipping_cost',

        'customer_notes',

        'invoice_requested',
        'invoice_company',
        'invoice_nip',
        'invoice_address',
        'invoice_postal_code',
        'invoice_city',

        'payment_method',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}