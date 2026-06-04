<?php

namespace App\Models;

use App\Services\Discount\DiscountStrategyFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'type', 'discount_value', 'minimum_amount',
        'scope', 'visibility', 'assigned_user_id',
        'max_uses', 'used_count', 'starts_at', 'expires_at', 'is_active', 'show_on_home',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'expires_at'   => 'datetime',
        'is_active'    => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /** 首頁／會員中心共用查詢：show_on_home=true 且有效 */
    public static function forHome(?int $userId = null)
    {
        return static::where('is_active', true)
            ->where('show_on_home', true)
            ->where(function ($q) use ($userId) {
                $q->where('visibility', 'public');
                if ($userId) {
                    $q->orWhere('visibility', 'member');
                }
            })
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->where(fn($q) => $q->whereNull('max_uses')->orWhereColumn('used_count', '<', 'max_uses'))
            ->orderBy('expires_at')
            ->get();
    }

    public function isValid(?int $userId = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;

        $visibility = $this->visibility ?? 'public';
        if ($visibility === 'member' && $userId === null) return false;
        if ($visibility === 'personal' && $this->assigned_user_id !== $userId) return false;

        return true;
    }

    public function computeDiscount(int $total): int
    {
        if ($total < $this->minimum_amount) return 0;

        return DiscountStrategyFactory::make($this->type)
            ->calculate($total, $this->discount_value);
    }

    public function discountLabel(): string
    {
        return DiscountStrategyFactory::make($this->type)
            ->label($this->discount_value);
    }

    public function scopeLabel(): string
    {
        return match ($this->scope) {
            'grooming'      => '寵物美容',
            'accommodation' => '寵物住宿',
            'shop'          => '線上商城',
            default         => '全館適用',
        };
    }

    public function scopeUrl(): string
    {
        return match ($this->scope) {
            'accommodation' => '/accommodation',
            'shop'          => '/shop',
            default         => '/booking',
        };
    }

    public function visibilityLabel(): string
    {
        return match ($this->visibility ?? 'public') {
            'member'   => '會員',
            'personal' => '個人專屬',
            default    => '公開',
        };
    }
}
