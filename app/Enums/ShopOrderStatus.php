<?php

namespace App\Enums;

enum ShopOrderStatus: string
{
    case Pending   = 'pending';
    case Paid      = 'paid';
    case Shipped   = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => '待付款',
            self::Paid      => '已付款',
            self::Shipped   => '已出貨',
            self::Completed => '已完成',
            self::Cancelled => '已取消',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'text-amber-600 bg-amber-50',
            self::Paid      => 'text-blue-600 bg-blue-50',
            self::Shipped   => 'text-purple-600 bg-purple-50',
            self::Completed => 'text-green-600 bg-green-50',
            self::Cancelled => 'text-gray-500 bg-gray-50',
        };
    }
}
