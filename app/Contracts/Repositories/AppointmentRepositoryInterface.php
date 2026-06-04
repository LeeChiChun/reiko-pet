<?php

namespace App\Contracts\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Collection;

interface AppointmentRepositoryInterface
{
    public function create(array $data): Appointment;
    public function countActiveAtSlot(int $storeId, string $appointmentAt): int;
    public function getAddonsByIds(array $ids): Collection;
    public function attachAddon(int $appointmentId, int $addonServiceId, float $price): void;
}
