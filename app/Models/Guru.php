<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $table = 'gurus'; // eksplisit karena nama tabel plural

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status_pegawai',
        'pangkat_gol_ruang',
        'no_sk_pertama',
        'no_sk_terakhir',
        'pendidikan_terakhir',
        'study_group_id',
        'kelas_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }

    /**
     * Study groups yang diwali guru ini.
     * FK: homeroom_teacher_id di study_groups = users.id (bukan gurus.id)
     * Gunakan user_id sebagai local key.
     */
    public function homeroomGroups(): HasMany
    {
        return $this->hasMany(StudyGroup::class, 'homeroom_teacher_id', 'user_id');
    }
}