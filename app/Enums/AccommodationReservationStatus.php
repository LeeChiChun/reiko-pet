<?php

namespace App\Enums;

use App\Contracts\ReservationStatus;

enum AccommodationReservationStatus: string implements ReservationStatus
{
    case Pending    = 'pending';
    case Confirmed  = 'confirmed';
    case CheckedIn  = 'checked_in';
    case CheckedOut = 'checked_out';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending    => '待確認',
            self::Confirmed  => '已確認',
            self::CheckedIn  => '已入住',
            self::CheckedOut => '已退房',
            self::Cancelled  => '已取消',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending    => 'text-amber-600 bg-amber-50',
            self::Confirmed  => 'text-blue-600 bg-blue-50',
            self::CheckedIn  => 'text-green-600 bg-green-50',
            self::CheckedOut => 'text-gray-500 bg-gray-50',
            self::Cancelled  => 'text-red-500 bg-red-50',
        };
    }

    public function canCancel(): bool
    {
        return in_array($this, [self::Pending, self::Confirmed]);
    }
}
