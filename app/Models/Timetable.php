<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_group_id', 'study_subject_id', 'teacher_id',
        'day_of_week', 'start_time', 'end_time',
        'room', 'session_type', 'academic_year', 'semester', 'is_active', 'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke kelas
    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    // Relasi ke mata pelajaran
    public function studySubject(): BelongsTo
    {
        return $this->belongsTo(StudySubject::class);
    }

    // Relasi ke guru
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Format jam tampil "07:00 - 08:30"
    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }

    // Deteksi konflik jadwal untuk kelas yang sama
    public static function hasConflict(
        int $studyGroupId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $excludeId = null
    ): bool {
        $query = self::where('study_group_id', $studyGroupId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // public function studySubject()
    // {
    //     return $this->belongsTo(StudySubject::class, 'study_subject_id');
    // }

    // public function studyGroup()
    // {
    //     return $this->belongsTo(StudyGroup::class, 'study_group_id');
    // }
}
