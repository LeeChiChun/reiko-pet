<?php

namespace App\Contracts;

use App\Models\Appointment;

interface AppointmentServiceInterface
{
    public function cancel(Appointment $appointment): void;
    public function updateStatus(Appointment $appointment, string $status, ?int $groomerId, ?string $note): void;
}
