{{-- resources/views/components/profil-field.blade.php --}}
{{-- Usage: <x-profil-field label="Nama" :value="$siswa->nama" /> --}}

@props(['label', 'value' => null])

<div>
    <p class="text-xs text-slate-500 mb-0.5">{{ $label }}</p>
    <p class="text-slate-800 text-sm">{{ $value ?? '-' }}</p>
</div>