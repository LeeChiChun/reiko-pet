<?php

namespace App\Services\Discount;

use App\Services\Discount\Contracts\DiscountStrategy;

class PercentageDiscount implements DiscountStrategy
{
    public function calculate(int $originalPrice, float $discountValue): int
    {
        return (int) round($originalPrice * $discountValue / 100);
    }

    public function label(float $discountValue): string
    {
        return "享 {$discountValue}% 折扣";
    }
}
