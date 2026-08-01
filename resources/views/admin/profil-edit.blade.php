{{-- resources/views/admin/profil-edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto text-center py-12">
    <div class="animate-spin inline-block w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full mb-3" role="status"></div>
    <p class="text-xs text-slate-500 dark:text-slate-400">Mengalihkan ke halaman manajemen profil...</p>
</div>

<script>
    // Langsung arahkan kembali ke profil utama jika rute ini tidak sengaja terakses
    window.location.href = "{{ route('admin.profil') }}";
</script>
@endsection