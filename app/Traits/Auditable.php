<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logActivity('create', 'Data ' . class_basename($model) . ' ditambahkan', null, $model->toArray());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                $old = [];
                foreach (array_keys($changes) as $key) {
                    $old[$key] = $model->getOriginal($key);
                }
                $model->logActivity('update', 'Data ' . class_basename($model) . ' diperbarui', $old, $model->toArray());
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', 'Data ' . class_basename($model) . ' dihapus', $model->toArray(), null);
        });
    }

    public function logActivity($action, $description, $old = null, $new = null)
    {
        // Cek apakah tabel audit_logs sudah ada
        if (!app()->runningInConsole() && Auth::check()) {
            try {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => $action,
                    'entity_type' => class_basename($this),
                    'entity_id' => $this->id ?? null,
                    'description' => $description,
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                    'old_values' => $old,
                    'new_values' => $new,
                ]);
            } catch (\Exception $e) {
                // Silent fail jika ada error (misal: tabel belum ada)
            }
        }
    }
}