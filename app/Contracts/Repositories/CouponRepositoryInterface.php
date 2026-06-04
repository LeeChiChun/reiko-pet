<?php

namespace App\Contracts\Repositories;

use App\Models\Coupon;
use Illuminate\Support\Collection;

interface CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon;
    public function findById(int $id): ?Coupon;
    public function getAvailableForCheckout(string $source, ?int $userId): Collection;
    public function recordUsage(int $couponId, ?int $userId, string $orderNo, int $discount): void;
}
