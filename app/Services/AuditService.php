<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log a system event or CRUD action.
     */
    public static function log(string $event, ?string $modelType = null, ?int $modelId = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Don't log empty changes on update
        if ($event === 'update' && empty($oldValues) && empty($newValues)) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $modelType,
            'auditable_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
