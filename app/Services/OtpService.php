<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    private const EXPIRE_MINUTES = 10;

    public function generate(User $user, string $type): string
    {
        OtpCode::where('user_id', $user->id)->where('type', $type)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'type'       => $type,
            'expires_at' => now()->addMinutes(self::EXPIRE_MINUTES),
        ]);

        return $code;
    }

    public function verify(User $user, string $inputCode, string $type): bool
    {
        $otp = OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp || $otp->isExpired() || $otp->code !== $inputCode) {
            return false;
        }

        $otp->update(['used' => true]);
        return true;
    }

    public function sendByMail(User $user, string $code, string $type): void
    {
        $subject = $type === 'register' ? '信箱驗證碼' : '登入驗證碼';
        $body    = "您的{$subject}為：{$code}，有效期限 " . self::EXPIRE_MINUTES . " 分鐘。";

        Mail::raw($body, function ($message) use ($user, $subject) {
            $message->to($user->email)->subject("[寵物美容] {$subject}");
        });
    }
}
