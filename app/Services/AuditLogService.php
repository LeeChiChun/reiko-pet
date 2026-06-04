<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public function log(string $action, ?string $targetType = null, ?int $targetId = null, array $payload = []): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'payload'     => empty($payload) ? null : $payload,
            'ip_address'  => Request::ip(),
        ]);
    }
}
