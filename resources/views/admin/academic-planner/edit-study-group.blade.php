@extends('layouts.app')

@section('title', 'Edit Kelas ' . $studyGroup->name)

@section('content')
<div class="container py-4" style="max-width:600px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('academic-planner.show-study-group', $studyGroup->id) }}">Kelas {{ $studyGroup->name }}</a>
            </li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Kelas {{ $studyGroup->name }}</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('academic-planner.study-groups.update', $studyGroup->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                    <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                        @foreach ([1,2,3] as $g)
                            <option value="{{ $g }}" {{ old('grade', $studyGroup->grade) == $g ? 'selected' : '' }}>Kelas {{ $g }}</option>
                        @endforeach
                    </select>
                    @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Seksi <span class="text-danger">*</span></label>
                    <select name="section" class="form-select @error('section') is-invalid @enderror" required>
                        @foreach (['A','B','C','D'] as $s)
                            <option value="{{ $s }}" {{ old('section', $studyGroup->section) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', $studyGroup->capacity) }}"
                           min="1" max="50" class="form-control @error('capacity') is-invalid @enderror" required>
                    @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Wali Kelas</label>
                    <select name="homeroom_teacher_id" class="form-select @error('homeroom_teacher_id') is-invalid @enderror">
                        <option value="">-- Tidak Ada --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('homeroom_teacher_id', $studyGroup->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ruang</label>
                    <input type="text" name="room" value="{{ old('room', $studyGroup->room) }}"
                           class="form-control @error('room') is-invalid @enderror" maxlength="50">
                    @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $studyGroup->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-save me-1"></i>Perbarui
                    </button>
                    <a href="{{ route('academic-planner.show-study-group', $studyGroup->id) }}"
                       class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
