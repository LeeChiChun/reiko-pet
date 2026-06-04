<?php

namespace App\Services\Discount;

use App\Services\Discount\Contracts\DiscountStrategy;
use InvalidArgumentException;

class DiscountStrategyFactory
{
    /** @var array<string, class-string<DiscountStrategy>> */
    private static array $strategies = [
        'percentage'      => PercentageDiscount::class,
        'fixed'           => FixedDiscount::class,
        'buy_one_get_one' => BuyOneGetOneDiscount::class,
    ];

    public static function make(string $type): DiscountStrategy
    {
        $class = self::$strategies[$type] ?? null;

        if ($class === null) {
            throw new InvalidArgumentException("未知的折扣類型：{$type}");
        }

        return new $class();
    }

    /**
     * 註冊新折扣類型（新增類型時只需呼叫這裡，不改現有程式碼）
     *
     * @param class-string<DiscountStrategy> $strategyClass
     */
    public static function register(string $type, string $strategyClass): void
    {
        self::$strategies[$type] = $strategyClass;
    }
}
