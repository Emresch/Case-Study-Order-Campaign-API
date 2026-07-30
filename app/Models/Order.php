<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'sub_total',
        'discount_amount',
        'shipping_cost',
        'campaign_name',
        'total_amount',
        'order_status',
        'applied_campaigns',
    ];

    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    protected $casts = [
        'applied_campaigns' => 'array',
    ];
}
