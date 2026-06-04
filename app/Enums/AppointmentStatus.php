<?php

namespace App\Enums;

use App\Contracts\ReservationStatus;

enum AppointmentStatus: string implements ReservationStatus
{
    case Pending    = 'pending';
    case InProgress = 'in_progress';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending    => '待服務',
            self::InProgress => '進行中',
            self::Completed  => '已完成',
            self::Cancelled  => '已取消',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending    => 'text-amber-600 bg-amber-50',
            self::InProgress => 'text-blue-600 bg-blue-50',
            self::Completed  => 'text-green-600 bg-green-50',
            self::Cancelled  => 'text-gray-500 bg-gray-50',
        };
    }

    public function canCancel(): bool
    {
        return $this === self::Pending;
    }
}
