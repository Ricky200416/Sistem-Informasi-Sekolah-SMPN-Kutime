@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="container py-4" style="max-width:580px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.study-subjects.index') }}">Manajemen Mapel</a></li>
            <li class="breadcrumb-item active">Edit Mata Pelajaran</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit: {{ $subject->name }}</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('academic-planner.study-subjects.update', $subject->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}"
                           class="form-control @error('name') is-invalid @enderror" maxlength="100" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $subject->code) }}"
                           class="form-control @error('code') is-invalid @enderror" maxlength="10" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Jam Per Minggu <span class="text-danger">*</span></label>
                    <input type="number" name="credit_hours" value="{{ old('credit_hours', $subject->credit_hours) }}"
                           min="1" max="6" class="form-control @error('credit_hours') is-invalid @enderror" required>
                    @error('credit_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="core"     {{ old('type', $subject->type) == 'core'     ? 'selected' : '' }}>Wajib (Core)</option>
                        <option value="elective" {{ old('type', $subject->type) == 'elective' ? 'selected' : '' }}>Pilihan (Elective)</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $subject->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" id="is_active" name="is_active" value="1"
                               {{ $subject->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-save me-1"></i>Perbarui
                    </button>
                    <a href="{{ route('academic-planner.study-subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
