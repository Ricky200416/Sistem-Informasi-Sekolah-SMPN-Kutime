{{-- resources/views/guru/dashboard/attendance-trend.blade.php --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:6px;">
        <div>
            <p style="font-size:.78rem;font-weight:700;color:#1e293b;margin:0;">
                📈 Tren Kehadiran 7 Hari Terakhir
            </p>
            <p style="font-size:.6rem;color:#94a3b8;margin:2px 0 0;">
                Perbandingan hadir vs tidak hadir harian
            </p>
        </div>
        @if(isset($totalSiswa) && $totalSiswa > 0)
            <span style="font-size:.6rem;font-weight:600;color:#4f46e5;background:#eef2ff;
                         border:1px solid #c7d2fe;border-radius:6px;padding:3px 8px;">
                {{ $totalSiswa }} siswa
            </span>
        @endif
    </div>
    <div id="kehadiranChart" style="min-height:200px;"></div>
</div>