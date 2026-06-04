<?php

namespace App\Services;

use App\Contracts\BookingServiceInterface;
use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class BookingService implements BookingServiceInterface
{
    public function __construct(
        private AppointmentRepositoryInterface $appointmentRepo,
    ) {}

    public function isSlotFull(int $storeId, string $appointmentAt): bool
    {
        $count = $this->appointmentRepo->countActiveAtSlot($storeId, $appointmentAt);
        return $count >= config('booking.max_slot_count');
    }

    public function createAppointment(array $booking): Appointment
    {
        $appointment = $this->appointmentRepo->create([
            'customer_id'    => Auth::id(),
            'pet_id'         => $booking['pet_id'],
            'service_id'     => $booking['service_id'],
            'store_id'       => $booking['store_id'],
            'appointment_at' => $booking['appointment_at'],
            'status'         => AppointmentStatus::Pending->value,
            'total_price'    => $booking['total_price'],
        ]);

        $this->attachAddons($appointment, $booking['addon_ids'] ?? []);

        return $appointment;
    }

    public function calculateTotal(array $booking): float
    {
        return ($booking['service_price'] ?? 0) + ($booking['addon_total'] ?? 0);
    }

    public function cancel(Appointment $appointment): void
    {
        $appointment->cancel();
    }

    private function attachAddons(Appointment $appointment, array $addonIds): void
    {
        if (empty($addonIds)) return;

        $addons = $this->appointmentRepo->getAddonsByIds($addonIds);

        foreach ($addonIds as $addonId) {
            if ($addon = $addons->get($addonId)) {
                $this->appointmentRepo->attachAddon($appointment->id, $addon->id, $addon->price);
            }
        }
    }
}
