<?php

namespace App\Contracts;

interface SmsNotificationInterface
{
    public function sendBookingReminder(string $phone, string $appointmentAt): void;

    public function sendOtp(string $phone, string $code): void;
}
