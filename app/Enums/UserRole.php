<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Groomer  = 'groomer';
    case Admin    = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Customer => '顧客',
            self::Groomer  => '美容師',
            self::Admin    => '管理員',
        };
    }
}
