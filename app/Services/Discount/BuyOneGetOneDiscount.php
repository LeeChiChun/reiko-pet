<?php

namespace App\Services\Discount;

use App\Services\Discount\Contracts\DiscountStrategy;

class BuyOneGetOneDiscount implements DiscountStrategy
{
    // 買一送一為顯示用類型，折扣金額由後台另行處理，此處回傳 0
    public function calculate(int $originalPrice, float $discountValue): int
    {
        return 0;
    }

    public function label(float $discountValue): string
    {
        return '買一送一';
    }
}
