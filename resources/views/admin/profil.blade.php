{{-- resources/views/admin/profil.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Admin')

@section('content')
<div class="max-w-lg">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700 p-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Profil Admin
            </h2>
            <a href="{{ route('admin.profil.edit') }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl
                      bg-indigo-600 text-white text-xs font-semibold
                      hover:bg-indigo-700 active:scale-95 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0
                             113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </a>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">

            {{-- Avatar --}}
            <div class="shrink-0">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" alt="Foto Profil"
                         class="w-16 h-16 rounded-2xl object-cover
                                border-2 border-slate-200 dark:border-slate-600">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40
                                flex items-center justify-center
                                text-indigo-600 dark:text-indigo-400
                                text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 flex-1 min-w-0">
                {{-- Nama --}}
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500
                               uppercase tracking-wide mb-0.5">Nama</p>
                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                        {{ $user->name }}
                    </p>
                </div>

                {{-- Email --}}
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500
                               uppercase tracking-wide mb-0.5">Email</p>
                    <p class="text-xs text-slate-700 dark:text-slate-300 break-all">
                        {{ $user->email }}
                    </p>
                </div>

                {{-- Role --}}
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500
                               uppercase tracking-wide mb-0.5">Role</p>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold
                                 bg-indigo-50 text-indigo-700
                                 dark:bg-indigo-900/30 dark:text-indigo-300">
                        Admin
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection