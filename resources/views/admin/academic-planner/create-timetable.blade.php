@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container py-4" style="max-width:640px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item active">Tambah Jadwal</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-calendar-plus me-2 text-primary"></i>Tambah Jadwal Pelajaran</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('academic-planner.timetables.store') }}" method="POST">
                @csrf

                {{-- Kelas --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <select name="study_group_id" class="form-select @error('study_group_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($studyGroups as $group)
                            <option value="{{ $group->id }}"
                                {{ old('study_group_id', $selectedGroup) == $group->id ? 'selected' : '' }}>
                                Kelas {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('study_group_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Mata pelajaran --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="study_subject_id" class="form-select @error('study_subject_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($studySubjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('study_subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('study_subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Guru --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Hari --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                    <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Jam --}}
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" value="{{ old('start_time', '07:00') }}"
                               class="form-control @error('start_time') is-invalid @enderror" required>
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" value="{{ old('end_time', '08:30') }}"
                               class="form-control @error('end_time') is-invalid @enderror" required>
                        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Ruang --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ruang</label>
                    <input type="text" name="room" value="{{ old('room') }}" placeholder="Contoh: Lab Fisika"
                           class="form-control @error('room') is-invalid @enderror" maxlength="50">
                    @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tahun ajaran & semester --}}
                <div class="row mb-3">
                    <div class="col-7">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', '2025/2026') }}"
                               placeholder="2025/2026" class="form-control @error('academic_year') is-invalid @enderror"
                               maxlength="9" required>
                        @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-5">
                        <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="1" {{ old('semester','1') == '1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                        </select>
                        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="notes" rows="2" maxlength="500"
                              class="form-control @error('notes') is-invalid @enderror"
                              placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Simpan Jadwal
                    </button>
                    <a href="{{ route('academic-planner.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
