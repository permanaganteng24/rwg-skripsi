<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'code',
        'shipping_name',
        'company_name',        
        'shipping_email',     
        'shipping_phone',
        'shipping_address',
        'shipping_country',
        'shipping_province',
        'shipping_city',
        'shipping_district',   
        'shipping_postal_code',
        'shipping_method', 
        'shipping_courier',
        'tracking_number',
        'total_weight_kg',
        'total_product_price',
        'shipping_price',
        'discount_amount',
        'grand_total',
        'order_status',
        'payment_status',
        'payment_method',   
        'payment_url',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
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