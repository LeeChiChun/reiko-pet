<?php

namespace App\Repositories;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Models\{Coupon, CouponUsage};
use Illuminate\Support\Collection;

class EloquentCouponRepository implements CouponRepositoryInterface
{
    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', strtoupper(trim($code)))->first();
    }

    public function findById(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    public function getAvailableForCheckout(string $source, ?int $userId): Collection
    {
        return Coupon::where('is_active', true)
            ->where(fn($q) => $q->where('scope', 'all')->orWhere('scope', $source))
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->where(fn($q) => $q->whereNull('max_uses')->orWhereColumn('used_count', '<', 'max_uses'))
            ->where(function ($q) use ($userId) {
                $q->where('visibility', 'public');
                if ($userId) {
                    $q->orWhere('visibility', 'member')
                      ->orWhere(fn($q2) => $q2->where('visibility', 'personal')
                          ->where('assigned_user_id', $userId));
                }
            })
            ->orderBy('name')
            ->get();
    }

    public function recordUsage(int $couponId, ?int $userId, string $orderNo, int $discount): void
    {
        $coupon = $this->findById($couponId);
        if (!$coupon) return;

        CouponUsage::create([
            'coupon_id'    => $couponId,
            'user_id'      => $userId,
            'order_no'     => $orderNo,
            'amount_saved' => $discount,
        ]);

        $coupon->increment('used_count');
    }
}
