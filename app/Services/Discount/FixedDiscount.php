<?php

namespace App\Services\Discount;

use App\Services\Discount\Contracts\DiscountStrategy;

class FixedDiscount implements DiscountStrategy
{
    public function calculate(int $originalPrice, float $discountValue): int
    {
        return min((int) $discountValue, $originalPrice);
    }

    public function label(float $discountValue): string
    {
        return '折抵 NT$ ' . number_format($discountValue);
    }
}
