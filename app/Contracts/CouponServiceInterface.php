<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface CouponServiceInterface
{
    public function validateCode(string $code, int $total, ?int $userId): array;
    public function getAvailableCoupons(string $source, ?int $userId): Collection;
    public function recordUsage(int $couponId, ?int $userId, string $orderNo, int $discount): void;
}
