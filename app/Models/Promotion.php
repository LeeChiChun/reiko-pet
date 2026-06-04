<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['title', 'badge', 'period', 'description', 'tag', 'color', 'link_url', 'coupon_id', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function ctaUrl(): string
    {
        if ($this->coupon) {
            return match ($this->coupon->scope) {
                'grooming'      => route('booking.step1'),
                'accommodation' => route('accommodation.index'),
                'shop'          => route('shop.index'),
                default         => $this->link_url ?? route('home'),
            };
        }
        return $this->link_url ?? route('home');
    }
}
