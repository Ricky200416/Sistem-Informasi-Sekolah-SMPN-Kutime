@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="container py-4" style="max-width:580px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.index') }}">Academic Planner</a></li>
            <li class="breadcrumb-item"><a href="{{ route('academic-planner.study-subjects.index') }}">Manajemen Mapel</a></li>
            <li class="breadcrumb-item active">Tambah Mata Pelajaran</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-book-half me-2 text-success"></i>Tambah Mata Pelajaran</h5>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('academic-planner.study-subjects.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika"
                           class="form-control @error('name') is-invalid @enderror" maxlength="100" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: MTK"
                           class="form-control @error('code') is-invalid @enderror" maxlength="10" required
                           style="text-transform:uppercase">
                    <div class="form-text">Kode unik, maksimal 10 karakter.</div>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Jam Per Minggu <span class="text-danger">*</span></label>
                    <input type="number" name="credit_hours" value="{{ old('credit_hours', 2) }}"
                           min="1" max="6" class="form-control @error('credit_hours') is-invalid @enderror" required>
                    @error('credit_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="core"     {{ old('type','core') == 'core'     ? 'selected' : '' }}>Wajib (Core)</option>
                        <option value="elective" {{ old('type') == 'elective'        ? 'selected' : '' }}>Pilihan (Elective)</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Deskripsi singkat mata pelajaran (opsional)">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save me-1"></i>Simpan Mata Pelajaran
                    </button>
                    <a href="{{ route('academic-planner.study-subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
