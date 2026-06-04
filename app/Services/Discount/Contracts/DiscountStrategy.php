<?php

namespace App\Services\Discount\Contracts;

interface DiscountStrategy
{
    /**
     * 計算折扣金額
     *
     * @param  int   $originalPrice 原始金額（元）
     * @param  float $discountValue 折扣設定值
     * @return int   折扣金額（元）
     */
    public function calculate(int $originalPrice, float $discountValue): int;

    /**
     * 回傳人類可讀的折扣說明（用於前台顯示）
     */
    public function label(float $discountValue): string;
}
