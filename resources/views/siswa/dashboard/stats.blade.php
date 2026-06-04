{{--
| resources/views/siswa/dashboard/stats.blade.php
| Partial: KPI Cards untuk Dashboard Siswa
| Variabel: $totalJadwal, $totalMapel, $totalGuru, $hariAktif, $totalJamPerMinggu, $studyGroup
--}}

<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

    {{-- Total Jadwal --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm px-4 py-3.5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30
                    flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 leading-none">
                {{ $totalJadwal ?? 0 }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Total Sesi</p>
        </div>
    </div>

    {{-- Mata Pelajaran --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm px-4 py-3.5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30
                    flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477
                         3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5
                         1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477
                         4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746
                         0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 leading-none">
                {{ $totalMapel ?? 0 }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Mata Pelajaran</p>
        </div>
    </div>

    {{-- Guru Pengajar --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm px-4 py-3.5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30
                    flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                         M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                         m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-black text-amber-600 dark:text-amber-400 leading-none">
                {{ $totalGuru ?? 0 }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Guru Pengajar</p>
        </div>
    </div>

    {{-- Jam per Minggu --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm px-4 py-3.5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-sky-100 dark:bg-sky-900/30
                    flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-black text-sky-600 dark:text-sky-400 leading-none">
                {{ $totalJamPerMinggu ?? 0 }}
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">Jam / Minggu</p>
        </div>
    </div>

</div>