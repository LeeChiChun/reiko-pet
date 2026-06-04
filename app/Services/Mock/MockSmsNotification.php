<?php

namespace App\Services\Mock;

use App\Contracts\BookingSmsNotifiable;
use App\Contracts\OtpSmsNotifiable;
use Illuminate\Support\Facades\Log;

class MockSmsNotification implements BookingSmsNotifiable, OtpSmsNotifiable
{
    public function sendBookingReminder(string $phone, string $appointmentAt): void
    {
        Log::info("[Mock SMS] BookingReminder phone={$phone} at={$appointmentAt}");
    }

    public function sendOtp(string $phone, string $code): void
    {
        Log::info("[Mock SMS] OTP phone={$phone} code={$code}");
    }
}
