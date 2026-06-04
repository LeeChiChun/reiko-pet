<?php

namespace App\Contracts;

interface OtpSmsNotifiable
{
    /**
     * 傳送一次性驗證碼 SMS（2FA 流程使用）
     */
    public function sendOtp(string $phone, string $code): void;
}
