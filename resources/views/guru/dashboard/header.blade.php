{{-- resources/views/guru/dashboard/header.blade.php --}}
<div class="relative overflow-hidden rounded-2xl px-6 py-5 text-white shadow-lg"
     style="background: linear-gradient(135deg, #4338ca 0%, #6d28d9 55%, #7c3aed 100%);">

    {{-- Grid pattern --}}
    <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="g-grid" width="24" height="24" patternUnits="userSpaceOnUse">
                    <path d="M 24 0 L 0 0 0 24" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#g-grid)"/>
        </svg>
    </div>

    {{-- Decorative circles --}}
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/10" aria-hidden="true"></div>
    <div class="absolute -right-2 -bottom-10 w-24 h-24 rounded-full bg-white/5" aria-hidden="true"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                @if(isset($isWaliKelas) && $isWaliKelas)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-amber-400/25 border border-amber-300/40"
                          style="font-size:.6rem;font-weight:700;color:#fef3c7;letter-spacing:.04em;text-transform:uppercase;">
                        ⭐ Wali Kelas
                    </span>
                @endif
            </div>
            <h2 style="font-size:1.15rem;font-weight:800;line-height:1.3;color:#fff;">
                Selamat datang, {{ auth()->user()->name ?? 'Guru' }}! 👋
            </h2>
            <p style="font-size:.7rem;color:#c4b5fd;margin-top:4px;">
                {{ now()->isoFormat('dddd, D MMMM Y') }}
                @if(isset($isWaliKelas) && $isWaliKelas && isset($namaKelasWali))
                    &nbsp;·&nbsp; Wali Kelas <strong style="color:#fff;">{{ $namaKelasWali }}</strong>
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            {{-- Jam --}}
            <div class="rounded-xl px-4 py-3 text-center border bg-white/15 border-white/20 min-w-[72px]">
                <p id="dashClock" style="font-size:1.1rem;font-weight:800;line-height:1;color:#fff;letter-spacing:.02em;">--:--</p>
                <p style="font-size:.55rem;color:#c4b5fd;margin-top:3px;text-transform:uppercase;letter-spacing:.06em;">WIB</p>
            </div>
            {{-- Tanggal --}}
            <div class="rounded-xl px-4 py-3 text-center border bg-white/15 border-white/20">
                <p style="font-size:1.1rem;font-weight:800;line-height:1;color:#fff;">{{ now()->format('d') }}</p>
                <p style="font-size:.55rem;color:#c4b5fd;margin-top:3px;">{{ now()->isoFormat('MMM Y') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    function tick(){
        var d=new Date(),h=String(d.getHours()).padStart(2,'0'),m=String(d.getMinutes()).padStart(2,'0');
        var el=document.getElementById('dashClock');
        if(el) el.textContent=h+':'+m;
    }
    tick(); setInterval(tick,10000);
})();
</script>