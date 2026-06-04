{{-- resources/views/guru/dashboard/modal-pengumuman.blade.php --}}
<div id="wdgModal-guru"
     class="hidden fixed inset-0 z-[300] items-center justify-center p-4"
     style="background:rgba(0,0,0,.5);backdrop-filter:blur(2px);"
     onclick="if(event.target===this)wdgTutup('guru')">

    <div style="background:#fff;border-radius:16px;width:100%;max-width:480px;
                max-height:88vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,.2);
                display:flex;flex-direction:column;">

        {{-- Modal header sticky --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                     padding:12px 16px;border-bottom:1px solid #f1f5f9;
                     position:sticky;top:0;background:#fff;z-index:2;
                     border-radius:16px 16px 0 0;">
            <div style="display:flex;align-items:center;gap:7px;">
                <span style="font-size:1rem;">📢</span>
                <p style="font-size:.82rem;font-weight:700;color:#1e293b;margin:0;">Pengumuman</p>
            </div>
            <button onclick="wdgTutup('guru')"
                    style="width:28px;height:28px;border:none;background:#f1f5f9;
                           border-radius:8px;cursor:pointer;font-size:.85rem;color:#64748b;
                           display:flex;align-items:center;justify-content:center;
                           transition:background .15s;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f1f5f9'">
                ✕
            </button>
        </div>

        {{-- Konten dinamis diisi JS --}}
        <div id="wdgModalKonten-guru" style="flex:1;min-height:80px;"></div>

    </div>
</div>