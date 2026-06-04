<?php

namespace App\Models;

use App\Contracts\Reservable;
use App\Enums\AccommodationReservationStatus;
use Illuminate\Database\Eloquent\Model;

class AccommodationReservation extends Model implements Reservable
{
    protected $fillable = [
        'user_id', 'guest_name', 'guest_phone', 'guest_email',
        'pet_name', 'room_type', 'price_per_night',
        'check_in', 'check_out', 'nights', 'total_price',
        'status', 'order_no',
    ];

    protected $casts = [
        'check_in'  => 'date',
        'check_out' => 'date',
        'status'    => AccommodationReservationStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(AccommodationRoom::class, 'room_type', 'slug');
    }

    public function roomName(): string
    {
        return $this->room?->name ?? $this->room_type;
    }

    // ── Reservable 介面實作 ───────────────────────────────────

    public function ownedBy(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    public function canBeCancelled(): bool
    {
        return $this->status->canCancel();
    }

    public function cancel(): void
    {
        $this->update(['status' => AccommodationReservationStatus::Cancelled->value]);
    }
}
