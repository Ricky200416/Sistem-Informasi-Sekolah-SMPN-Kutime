@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran')

@push('styles')
<style>
    /* Custom table styling */
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* Button styling */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scale(1.1);
    }
    
    /* Bootstrap-style Pagination */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-radius: 4px;
    }
    
    .pagination .page-item {
        margin: 0 1px;
    }
    
    .pagination .page-link {
        position: relative;
        display: block;
        padding: 8px 16px;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        min-width: 44px;
        text-align: center;
    }
    
    .pagination .page-link:hover {
        z-index: 2;
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    
    .pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    .pagination .page-item:first-child .page-link {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    
    .pagination .page-item:last-child .page-link {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    
    .pagination-info {
        color: #6c757d;
        font-size: 0.875rem;
        margin: 0;
        padding: 8px 16px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-book me-2 text-primary"></i>Manajemen Mata Pelajaran
            </h1>
            <p class="text-muted mb-0">Kelola semua mata pelajaran sekolah</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMapelModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Mapel
        </button>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.academic-planner.index') }}">Academic Planner</a>
            </li>
            <li class="breadcrumb-item active">Manajemen Mapel</li>
        </ol>
    </nav>

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Daftar Mata Pelajaran --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th>Nama Mata Pelajaran</th>
                                <th width="8%">SKS</th>
                                <th width="12%">Tipe</th>
                                <th width="10%">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-semibold">{{ $subject->code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $subject->name }}</div>
                                        @if($subject->description)
                                            <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $subject->credit_hours }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subject->type == 'core' ? 'primary' : 'secondary' }} text-white">
                                            {{ $subject->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($subject->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#showMapelModal{{ $subject->id }}"
                                                    title="Lihat Detail">
                                                <i class="bi bi-eye me-1"></i>Show
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#editMapelModal{{ $subject->id }}"
                                                    title="Edit Mata Pelajaran">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <form method="POST" action="/academic-planner/study-subjects/{{ $subject->id }}" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran \"{{ $subject->name }}\"?')" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        title="Hapus Mata Pelajaran">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <nav aria-label="Page navigation" class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            Menampilkan {{ $subjects->firstItem() ?? 0 }} - {{ $subjects->lastItem() ?? 0 }} dari {{ $subjects->total() }} data
                        </div>
                        {{ $subjects->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-book-x d-block mb-3 opacity-50" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">Belum ada mata pelajaran</h5>
                    <p class="text-muted">Silakan tambah mata pelajaran terlebih dahulu.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Mata Pelajaran
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Modal Tambah Mapel -->
<div class="modal fade" id="addMapelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mata Pelajaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.academic-planner.study-subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
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
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="core"     {{ old('type','core') == 'core'     ? 'selected' : '' }}>Wajib (Core)</option>
                                    <option value="elective" {{ old('type') == 'elective'        ? 'selected' : '' }}>Pilihan (Elective)</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" rows="3" maxlength="500"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Deskripsi singkat mata pelajaran (opsional)">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Show Mapel Dinamis -->
@foreach ($subjects as $subject)
<div class="modal fade" id="showMapelModal{{ $subject->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mata Pelajaran - {{ $subject->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Kode:</strong></td>
                                <td><span class="badge bg-light text-dark fw-semibold">{{ $subject->code }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Mata Pelajaran:</strong></td>
                                <td>{{ $subject->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>SKS:</strong></td>
                                <td>{{ $subject->credit_hours }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipe:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $subject->type == 'core' ? 'primary' : 'secondary' }} text-white">
                                        {{ $subject->type_label }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Status:</strong></td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $subject->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diupdate:</strong></td>
                                <td>{{ $subject->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($subject->description)
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p class="text-muted">{{ $subject->description }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Edit Mapel Dinamis -->
@foreach ($subjects as $subject)
<div class="modal fade" id="editMapelModal{{ $subject->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mata Pelajaran - {{ $subject->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.academic-planner.study-subjects.update', $subject->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $subject->name) }}" placeholder="Contoh: Matematika"
                                       class="form-control @error('name') is-invalid @enderror" maxlength="100" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $subject->code) }}" placeholder="Contoh: MTK"
                                       class="form-control @error('code') is-invalid @enderror" maxlength="10" required
                                       style="text-transform:uppercase">
                                <div class="form-text">Kode unik, maksimal 10 karakter.</div>
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jumlah Jam Per Minggu <span class="text-danger">*</span></label>
                                <input type="number" name="credit_hours" value="{{ old('credit_hours', $subject->credit_hours) }}"
                                       min="1" max="6" class="form-control @error('credit_hours') is-invalid @enderror" required>
                                @error('credit_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="core"     {{ old('type', $subject->type) == 'core'     ? 'selected' : '' }}>Wajib (Core)</option>
                                    <option value="elective" {{ old('type', $subject->type) == 'elective' ? 'selected' : '' }}>Pilihan (Elective)</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" rows="3" maxlength="500"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Deskripsi singkat mata pelajaran (opsional)">{{ old('description', $subject->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" id="is_active_{{ $subject->id }}" name="is_active" value="1"
                                           {{ $subject->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_{{ $subject->id }}">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Update Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
    /* Modal overlay effects */
    .modal.show {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 1050;
    }
    
    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.7) !important;
        z-index: 1040;
    }
    
    .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: none;
        z-index: 1060;
    }
    
    .modal-dialog {
        animation: modalSlideIn 0.3s ease-out;
        z-index: 1055;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translate(0, -50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Refresh halaman setelah submit modal
    document.addEventListener('DOMContentLoaded', function() {
        // Modal tambah mapel
        const addForm = document.querySelector('#addMapelModal form');
        if (addForm) {
            addForm.addEventListener('submit', function() {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        }

        // Modal edit mapel (dinamis)
        @foreach ($subjects as $subject)
        const editForm{{ $subject->id }} = document.querySelector('#editMapelModal{{ $subject->id }} form');
        if (editForm{{ $subject->id }}) {
            editForm{{ $subject->id }}.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Create temporary form for submission
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = this.action;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    tempForm.appendChild(csrfInput);
                }
                
                // Add method override
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                tempForm.appendChild(methodInput);
                
                // Add all form data
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    tempForm.appendChild(input);
                }
                
                // Submit form
                document.body.appendChild(tempForm);
                tempForm.submit();
            });
        }
        @endforeach
    });
</script>
@endpush

@endsection
