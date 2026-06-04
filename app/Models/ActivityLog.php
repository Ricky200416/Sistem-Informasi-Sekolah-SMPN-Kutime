<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;          // hanya pakai created_at manual

    protected $fillable = [
        'user_id',
        'role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'extra',
        'created_at',
    ];

    protected $casts = [
        'extra'      => 'array',
        'created_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper statis ────────────────────────────────────────
    /**
     * Catat aktivitas.
     *
     * ActivityLog::record('login', 'Auth', 'Berhasil masuk sistem');
     */
    public static function record(
        string  $action,
        ?string $module      = null,
        ?string $description = null,
        array   $extra       = []
    ): void {
        try {
            $user = auth()->user();
            if (! $user) return;

            static::create([
                'user_id'     => $user->id,
                'role'        => $user->role ?? 'user',
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => substr(request()->userAgent() ?? '', 0, 255),
                'extra'       => $extra ?: null,
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {
            // Jangan sampai error log menghentikan alur utama
        }
    }

    // ── Icon per action ──────────────────────────────────────
    public function actionIcon(): string
    {
        return match (true) {
            str_contains($this->action, 'login')    => '🔑',
            str_contains($this->action, 'logout')   => '🚪',
            str_contains($this->action, 'create')   => '✏️',
            str_contains($this->action, 'update')   => '🔄',
            str_contains($this->action, 'delete')   => '🗑️',
            str_contains($this->action, 'view')     => '👁️',
            str_contains($this->action, 'export')   => '📤',
            str_contains($this->action, 'import')   => '📥',
            str_contains($this->action, 'absensi')  => '📋',
            str_contains($this->action, 'nilai')    => '📊',
            str_contains($this->action, 'upload')   => '⬆️',
            default                                 => '⚡',
        };
    }

    // ── Badge warna per role ────────────────────────────────
    public function roleBadgeColor(): string
    {
        return match ($this->role) {
            'admin' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            'guru'  => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'siswa' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400',
        };
    }
}