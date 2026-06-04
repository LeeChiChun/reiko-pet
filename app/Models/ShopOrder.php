<?php

namespace App\Models;

use App\Enums\ShopOrderStatus;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $fillable = [
        'user_id', 'order_no', 'guest_name', 'guest_email',
        'guest_phone', 'guest_address', 'items', 'total', 'status',
    ];

    protected $casts = [
        'items'  => 'array',
        'status' => ShopOrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
