<?php

namespace App\Services;

use App\Contracts\AppointmentServiceInterface;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;

class AppointmentService implements AppointmentServiceInterface
{
    public function cancel(Appointment $appointment): void
    {
        $appointment->update(['status' => AppointmentStatus::Cancelled->value]);
    }

    public function updateStatus(Appointment $appointment, string $status, ?int $groomerId = null, ?string $note = null): void
    {
        $updates = ['status' => $status];

        if ($note !== null) {
            $updates['note'] = $note;
        }

        if ($groomerId !== null && !$appointment->groomer_id) {
            $updates['groomer_id'] = $groomerId;
        }

        $appointment->update($updates);
    }
}
