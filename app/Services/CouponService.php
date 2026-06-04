<?php

namespace App\Services;

use App\Contracts\CouponServiceInterface;
use App\Contracts\Repositories\CouponRepositoryInterface;
use Illuminate\Support\Collection;

class CouponService implements CouponServiceInterface
{
    public function __construct(
        private CouponRepositoryInterface $couponRepo,
    ) {}

    public function validateCode(string $code, int $total, ?int $userId): array
    {
        $coupon = $this->couponRepo->findByCode($code);

        if (!$coupon) {
            return ['success' => false, 'message' => '優惠碼無效或已過期'];
        }

        $visibility = $coupon->visibility ?? 'public';

        if ($visibility === 'member' && $userId === null) {
            return ['success' => false, 'message' => '此優惠碼需要登入才能使用'];
        }

        if ($visibility === 'personal' && $coupon->assigned_user_id !== $userId) {
            return ['success' => false, 'message' => '優惠碼無效或已過期'];
        }

        if (!$coupon->isValid($userId)) {
            return ['success' => false, 'message' => '優惠碼無效或已過期'];
        }

        if ($coupon->minimum_amount > 0 && $total < $coupon->minimum_amount) {
            return [
                'success' => false,
                'message' => '此優惠碼需最低消費 NT$ ' . number_format($coupon->minimum_amount),
            ];
        }

        $discount = $coupon->computeDiscount($total);

        return [
            'success'     => true,
            'coupon_id'   => $coupon->id,
            'coupon_name' => $coupon->name,
            'type'        => $coupon->type,
            'discount'    => $discount,
            'message'     => "已套用「{$coupon->name}」，折抵 NT$ " . number_format($discount),
        ];
    }

    public function getAvailableCoupons(string $source, ?int $userId): Collection
    {
        return $this->couponRepo->getAvailableForCheckout($source, $userId);
    }

    public function recordUsage(int $couponId, ?int $userId, string $orderNo, int $discount): void
    {
        $this->couponRepo->recordUsage($couponId, $userId, $orderNo, $discount);
    }
}
