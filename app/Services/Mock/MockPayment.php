<?php

namespace App\Services\Mock;

use App\Contracts\PaymentInterface;
use App\Contracts\RefundInterface;
use Illuminate\Support\Str;

class MockPayment implements PaymentInterface, RefundInterface
{
    public function processPayment(float $amount, array $metadata = []): array
    {
        return [
            'success'        => true,
            'transaction_id' => 'MOCK_' . Str::upper(Str::random(12)),
            'amount'         => $amount,
        ];
    }

    public function processRefund(string $transactionId): bool
    {
        return true;
    }
}
