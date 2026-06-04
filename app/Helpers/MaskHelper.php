<?php

namespace App\Helpers;

class MaskHelper
{
    public static function phone(?string $phone): string
    {
        if (!$phone || mb_strlen($phone) < 7) return $phone ?? '';
        return mb_substr($phone, 0, 4) . '***' . mb_substr($phone, -3);
    }

    public static function email(?string $email): string
    {
        if (!$email || !str_contains($email, '@')) return $email ?? '';
        [$local, $domain] = explode('@', $email, 2);
        $masked = mb_substr($local, 0, 2) . str_repeat('*', max(3, mb_strlen($local) - 2));
        return $masked . '@' . $domain;
    }
}
