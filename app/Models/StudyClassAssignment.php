<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyClassAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'study_subject_id',
        'study_group_id',
        'academic_year',
        'semester',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ================================
       RELATIONS
    ================================= */

    // Guru
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Mata pelajaran
    public function studySubject(): BelongsTo
    {
        return $this->belongsTo(StudySubject::class);
    }

    // Kelas (PENTING - pengganti kelas_id)
    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }
}