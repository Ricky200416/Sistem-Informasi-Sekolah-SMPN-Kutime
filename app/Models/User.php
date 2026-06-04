<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Role helpers ──────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // ── Relasi profil ─────────────────────────────────────────

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    // ── Wali Kelas via study_groups.homeroom_teacher_id ───────

    public function homeroomGroups(): HasMany
    {
        return $this->hasMany(StudyGroup::class, 'homeroom_teacher_id');
    }

    /**
     * Cek apakah user ini wali kelas.
     * Query: study_groups WHERE homeroom_teacher_id = users.id
     */
    public function isWaliKelas(): bool
    {
        return $this->homeroomGroups()->exists();
    }

    /**
     * Ambil study_group pertama yang diwali user ini.
     */
    public function kelasWali(): ?StudyGroup
    {
        return $this->homeroomGroups()->first();
    }
}