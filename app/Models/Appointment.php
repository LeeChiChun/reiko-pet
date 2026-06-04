<?php

namespace App\Models;

use App\Contracts\Reservable;
use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model implements Reservable
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'groomer_id', 'pet_id', 'service_id', 'store_id',
        'appointment_at', 'status', 'total_price', 'note',
    ];

    protected function casts(): array
    {
        return [
            'appointment_at' => 'datetime',
            'total_price'    => 'decimal:2',
            'status'         => AppointmentStatus::class,
        ];
    }

    public function scopePending($query)
    {
        return $query->where('status', AppointmentStatus::Pending);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::InProgress]);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function groomer()
    {
        return $this->belongsTo(User::class, 'groomer_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function addons()
    {
        return $this->hasMany(AppointmentAddon::class);
    }

    // ── Reservable 介面實作 ───────────────────────────────────

    public function ownedBy(int $userId): bool
    {
        return $this->customer_id === $userId;
    }

    public function canBeCancelled(): bool
    {
        return $this->status->canCancel();
    }

    public function cancel(): void
    {
        $this->update(['status' => AppointmentStatus::Cancelled->value]);
    }
}
