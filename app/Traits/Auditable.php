<?php

namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            AuditService::log('create', get_class($model), $model->id, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            // Don't log timestamps only
            unset($changes['updated_at']);
            if (empty($changes)) return;

            $oldValues = array_intersect_key($model->getOriginal(), $changes);
            $newValues = $changes;

            AuditService::log('update', get_class($model), $model->id, $oldValues, $newValues);
        });

        static::deleted(function ($model) {
            AuditService::log('delete', get_class($model), $model->id, $model->getAttributes(), null);
        });
    }
}
