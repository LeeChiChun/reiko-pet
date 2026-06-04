<?php

namespace App\Contracts;

use App\Models\Appointment;

interface BookingServiceInterface
{
    public function isSlotFull(int $storeId, string $appointmentAt): bool;
    public function createAppointment(array $booking): Appointment;
    public function calculateTotal(array $booking): float;
    public function cancel(Appointment $appointment): void;
}
