<?php

namespace App\Contracts;

interface BookingSmsNotifiable
{
    /**
     * 傳送預約前提醒 SMS 給顧客
     */
    public function sendBookingReminder(string $phone, string $appointmentAt): void;
}
