{{-- resources/views/admin/kelola-website/tabs/galeri.blade.php --}}
<div x-show="tab === 'galeri'" x-cloak class="space-y-4">

    @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-xs rounded-lg px-3 py-2">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Kelola Galeri Media</h2>
                <p class="text-xs text-slate-500 mt-0.5">Upload foto, video, atau tambahkan link YouTube/Facebook.</p>
            </div>
            <a href="{{ route('admin.galeri.create') }}"
               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Media
            </a>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-3 gap-2 mb-4">
            @foreach([
                ['label'=>'Total', 'value'=>$galeriStats['total'], 'color'=>'slate'],
                ['label'=>'Aktif', 'value'=>$galeriStats['aktif'], 'color'=>'green'],
                ['label'=>'Draf',  'value'=>$galeriStats['draf'],  'color'=>'amber'],
            ] as $s)
            <div class="bg-{{ $s['color'] }}-50 border border-{{ $s['color'] }}-200 rounded-lg p-2.5 text-center">
                <p class="text-lg font-bold text-{{ $s['color'] }}-700">{{ $s['value'] }}</p>
                <p class="text-xs text-{{ $s['color'] }}-600">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.kelola-website') }}" class="flex flex-wrap gap-2 mb-4">
            <input type="hidden" name="tab" value="galeri">

            <select name="galeri_kategori"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($galeriKategoriOptions as $val => $label)
                    <option value="{{ $val }}" @selected(request('galeri_kategori') === $val)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="galeri_tipe"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Tipe</option>
                <option value="photo"         @selected(request('galeri_tipe') === 'photo')>🖼️ Foto</option>
                <option value="video"         @selected(request('galeri_tipe') === 'video')>🎥 Video</option>
                <option value="link_youtube"  @selected(request('galeri_tipe') === 'link_youtube')>▶️ YouTube</option>
                <option value="link_facebook" @selected(request('galeri_tipe') === 'link_facebook')>📘 Facebook</option>
            </select>

            <select name="galeri_status"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('galeri_status') === 'aktif')>Aktif</option>
                <option value="draf"  @selected(request('galeri_status') === 'draf')>Draf</option>
            </select>

            <button type="submit"
                    class="px-3 py-1.5 bg-slate-700 text-white text-xs rounded-lg hover:bg-slate-800 transition">Filter</button>

            @if(request()->hasAny(['galeri_kategori','galeri_tipe','galeri_status']))
                <a href="{{ route('admin.kelola-website', ['tab'=>'galeri']) }}"
                   class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs rounded-lg hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        {{-- Grid Media --}}
        @if($galeris->isEmpty())
            <div class="text-center py-10 text-slate-400 text-xs border border-dashed border-slate-300 rounded-lg">
                <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Belum ada media. <a href="{{ route('admin.galeri.create') }}" class="text-indigo-600 hover:underline">Tambah sekarang →</a>
            </div>
        @else
            <div class="grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($galeris as $item)
                <div class="group relative bg-slate-100 rounded-lg overflow-hidden aspect-[4/3] border border-slate-200 {{ $item->status === 'draf' ? 'opacity-60' : '' }}">

                    {{-- Thumbnail --}}
                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}"
                         class="w-full h-full object-cover">

                    {{-- Badge tipe --}}
                    <div class="absolute top-1.5 left-1.5">
                        <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded backdrop-blur-sm">
                            {{ $item->tipe_label }}
                        </span>
                    </div>

                    {{-- Badge draf --}}
                    @if($item->status === 'draf')
                    <div class="absolute top-1.5 right-1.5">
                        <span class="px-1.5 py-0.5 text-xs bg-amber-500/80 text-white rounded">Draf</span>
                    </div>
                    @endif

                    {{-- Play icon untuk video --}}
                    @if($item->is_video)
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-8 h-8 rounded-full bg-black/40 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    {{-- Overlay aksi --}}
                    <div class="absolute inset-0 bg-black/55 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2">
                        <p class="text-white text-xs font-medium line-clamp-2 leading-tight">{{ $item->judul }}</p>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white/70 capitalize">{{ $item->kategori }}</span>
                            <div class="flex gap-1.5">
                                {{-- Edit --}}
                                <a href="{{ route('admin.galeri.edit', $item) }}"
                                   class="p-1 bg-white/20 hover:bg-white/30 rounded text-white transition" title="Edit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Toggle status --}}
                                <form action="{{ route('admin.galeri.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-1 bg-white/20 hover:bg-white/30 rounded text-white transition"
                                            title="{{ $item->status === 'aktif' ? 'Jadikan Draf' : 'Aktifkan' }}">
                                        @if($item->status === 'aktif')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.galeri.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus media ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 bg-red-500/60 hover:bg-red-500/80 rounded text-white transition" title="Hapus">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($galeris->hasPages())
                <div class="mt-3 text-xs">{{ $galeris->links() }}</div>
            @endif
        @endif

    </div>

    {{-- Pratinjau --}}
    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-xs">
        <p class="font-medium text-green-800 mb-1">Pratinjau Halaman Publik:</p>
        <a href="{{ route('website.galeri') }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-1">
            Buka halaman Galeri sekarang →
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</div>