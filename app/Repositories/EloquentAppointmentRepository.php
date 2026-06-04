<?php

namespace App\Repositories;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Models\{AddonService, Appointment, AppointmentAddon};
use Illuminate\Support\Collection;

class EloquentAppointmentRepository implements AppointmentRepositoryInterface
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function countActiveAtSlot(int $storeId, string $appointmentAt): int
    {
        return Appointment::where('store_id', $storeId)
            ->where('appointment_at', $appointmentAt)
            ->whereIn('status', [AppointmentStatus::Pending->value, AppointmentStatus::InProgress->value])
            ->count();
    }

    public function getAddonsByIds(array $ids): Collection
    {
        return AddonService::whereIn('id', $ids)->get()->keyBy('id');
    }

    public function attachAddon(int $appointmentId, int $addonServiceId, float $price): void
    {
        AppointmentAddon::create([
            'appointment_id'   => $appointmentId,
            'addon_service_id' => $addonServiceId,
            'price'            => $price,
        ]);
    }
}
