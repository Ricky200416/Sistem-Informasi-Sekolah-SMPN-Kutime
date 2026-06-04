<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'credit_hours',
        'type',
        'color',
        'session',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'credit_hours' => 'integer',
    ];

    /* ================================
       RELATIONS
    ================================= */

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StudyClassAssignment::class);
    }

    /* ================================
       ACCESSORS
    ================================= */

    public function getColorAttribute($value): string
    {
        return $value ?? '#3B82F6';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'core' ? 'Wajib' : 'Pilihan';
    }
}