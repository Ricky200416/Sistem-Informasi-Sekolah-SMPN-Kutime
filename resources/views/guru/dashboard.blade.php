{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Guru')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #mainContent { font-family: 'Plus Jakarta Sans', sans-serif !important; }
</style>
@endpush

@section('content')

@php
    /*
     * SAFETY CAST — jamin $isWaliKelas selalu boolean di view ini.
     * Meski controller gagal inject, ?? false mencegah undefined error.
     */
    $isWaliKelas = isset($isWaliKelas) ? (bool) $isWaliKelas : false;

    /*
     * SAFETY CAST — jamin $guruUltah selalu Collection.
     * Jika controller gagal inject (misal guru belum terdaftar),
     * widget tidak akan crash karena ada guard @if di dalam partial.
     */
    $guruUltah = isset($guruUltah) ? $guruUltah : collect();
@endphp

<div class="space-y-4">

    @include('guru.dashboard.header')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Kolom KIRI 2/3 --}}
        <div class="lg:col-span-2 space-y-4">

            @include('guru.dashboard.performance-summary')
            @include('guru.dashboard.jadwal-mengajar')
            @include('guru.dashboard.attendance-trend')

            @if($isWaliKelas)
                @include('guru.dashboard.rekap-absensi')
            @endif

            @include('guru.dashboard.at-risk-students')

        </div>

        {{-- Kolom KANAN 1/3 --}}
        <div class="lg:col-span-1 space-y-4">

            @include('guru.dashboard.announcements')

            {{--
                Widget Ulang Tahun Guru Bulan Ini.
                Guard @if ada di DALAM partial (ultah_guru.blade.php),
                sehingga jika $guruUltah kosong, tidak ada HTML yang dirender.
            --}}
            @include('guru.dashboard.ultah_guru')

            {{--
                KUNCI UTAMA: Guard @if($isWaliKelas) di SINI.
                File wali-kelas-summary hanya di-include
                jika $isWaliKelas benar-benar true.
            --}}
            @if($isWaliKelas)
                @include('guru.dashboard.wali-kelas-summary')
            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')
@include('guru.dashboard.scripts-modal')
@include('guru.dashboard.scripts-chart')
@endpush