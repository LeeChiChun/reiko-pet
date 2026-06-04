<?php

namespace App\Services\Mock;

use App\Contracts\BookingMailNotifiable;
use App\Contracts\StatusMailNotifiable;
use Illuminate\Support\Facades\Log;

class MockMailNotification implements BookingMailNotifiable, StatusMailNotifiable
{
    public function sendBookingConfirmation(int $appointmentId): void
    {
        Log::info("[Mock Mail] BookingConfirmation appointment={$appointmentId}");
    }

    public function sendCancellation(int $appointmentId): void
    {
        Log::info("[Mock Mail] Cancellation appointment={$appointmentId}");
    }

    public function sendStatusUpdate(int $appointmentId, string $status): void
    {
        Log::info("[Mock Mail] StatusUpdate appointment={$appointmentId} status={$status}");
    }
}
