@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container py-4" style="max-width:640px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('academic-planner.show-study-group', $timetable->study_group_id) }}">
                    Kelas {{ $timetable->studyGroup->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">Edit Jadwal</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Jadwal</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('academic-planner.timetables.update', $timetable->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <select name="study_group_id" class="form-select @error('study_group_id') is-invalid @enderror" required>
                        @foreach ($studyGroups as $group)
                            <option value="{{ $group->id }}"
                                {{ old('study_group_id', $timetable->study_group_id) == $group->id ? 'selected' : '' }}>
                                Kelas {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('study_group_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="study_subject_id" class="form-select @error('study_subject_id') is-invalid @enderror" required>
                        @foreach ($studySubjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ old('study_subject_id', $timetable->study_subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('study_subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                    <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                            <option value="{{ $day }}"
                                {{ old('day_of_week', $timetable->day_of_week) == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="start_time"
                               value="{{ old('start_time', substr($timetable->start_time,0,5)) }}"
                               class="form-control @error('start_time') is-invalid @enderror" required>
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="end_time"
                               value="{{ old('end_time', substr($timetable->end_time,0,5)) }}"
                               class="form-control @error('end_time') is-invalid @enderror" required>
                        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ruang</label>
                    <input type="text" name="room" value="{{ old('room', $timetable->room) }}"
                           class="form-control @error('room') is-invalid @enderror" maxlength="50">
                    @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row mb-3">
                    <div class="col-7">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year"
                               value="{{ old('academic_year', $timetable->academic_year) }}"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               maxlength="9" required>
                        @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-5">
                        <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="1" {{ old('semester', $timetable->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ old('semester', $timetable->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="notes" rows="2" maxlength="500"
                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $timetable->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-save me-1"></i>Perbarui Jadwal
                    </button>
                    <a href="{{ route('academic-planner.show-study-group', $timetable->study_group_id) }}"
                       class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
