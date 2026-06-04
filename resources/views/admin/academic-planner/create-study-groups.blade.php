@extends('layouts.app')

@section('title', 'Tambah Kelas Baru')

@section('content')
<div class="container py-4" style="max-width:600px">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item active">Tambah Kelas</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kelas Baru</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('academic-planner.store-study-group') }}" method="POST">
                @csrf

                {{-- Nama Kelas --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Contoh: VII A" maxlength="50" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tingkat --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tingkatan <span class="text-danger">*</span></label>
                    <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        <option value="7" {{ old('grade') == '7' ? 'selected' : '' }}>VII (Kelas 7)</option>
                        <option value="8" {{ old('grade') == '8' ? 'selected' : '' }}>VIII (Kelas 8)</option>
                        <option value="9" {{ old('grade') == '9' ? 'selected' : '' }}>IX (Kelas 9)</option>
                    </select>
                    @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Jurusan --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jurusan</label>
                    <input type="text" name="section" value="{{ old('section') }}" 
                           placeholder="Contoh: A, IPS, IPA" class="form-control @error('section') is-invalid @enderror" 
                           maxlength="10">
                    @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tahun Ajaran --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="academic_year" value="{{ old('academic_year') }}" 
                           placeholder="Contoh: 2024/2025" class="form-control @error('academic_year') is-invalid @enderror" 
                           maxlength="9" required>
                    <div class="form-text">Format: YYYY/YYYY</div>
                    @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Wali Kelas --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Wali Kelas</label>
                    <select name="homeroom_teacher_id"
                        class="form-select @error('homeroom_teacher_id') is-invalid @enderror">
                        <option value="">-- Pilih Wali Kelas (opsional) --</option>
                        @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                            <span class="text-muted">({{ $teacher->role_label }})</span>
                        </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Hanya Guru dan Kepala Sekolah yang aktif yang dapat dipilih.
                    </div>
                    @error('homeroom_teacher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Semester --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                        <option value="">Pilih Semester</option>
                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                    @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Ruang --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ruang Kelas</label>
                    <input type="text" name="room" value="{{ old('room') }}" 
                           placeholder="Contoh: Lab Komputer" class="form-control @error('room') is-invalid @enderror" 
                           maxlength="50">
                    @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status Aktif --}}
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                               {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Simpan Kelas
                    </button>
                    <a href="{{ route('academic-planner.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection