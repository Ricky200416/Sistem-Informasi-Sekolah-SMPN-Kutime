<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGroup extends Model
{
    use HasFactory;

    protected $table = 'study_groups';

    protected $fillable = [
        'name',
        'grade',
        'section',
        'capacity',
        'homeroom_teacher_id',
        'description',
        'room',
        'academic_year',
        'semester',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity'  => 'integer',
        'grade'     => 'integer',
        'semester'  => 'integer',
    ];

    /* ================================
       RELATIONS
    ================================= */

    // Wali kelas
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    // Jadwal
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    // Assignment guru
    public function assignments(): HasMany
    {
        return $this->hasMany(StudyClassAssignment::class, 'study_group_id');
    }

    // 🔥 TAMBAHAN PENTING → relasi ke siswa
    public function students(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /* ================================
       HELPER
    ================================= */

    public function timetablesByDay(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $grouped = [];

        foreach ($days as $day) {
            $grouped[$day] = $this->timetables()
                ->where('day_of_week', $day)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->with(['studySubject', 'teacher'])
                ->get();
        }

        return $grouped;
    }
}