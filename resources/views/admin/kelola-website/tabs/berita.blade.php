{{-- resources/views/admin/kelola-website/tabs/berita.blade.php --}}

<div
    x-show="tab === 'berita'"
    x-cloak
    class="space-y-4"
    x-data="beritaTab()"
>

    {{-- ── Flash success ── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="flex items-center justify-between gap-2 bg-green-50 border border-green-200 text-green-800 text-xs rounded-lg px-3 py-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- ── SECTION DAFTAR BERITA & PENGUMUMAN ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Kelola Berita & Pengumuman</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tambah, edit, atau hapus konten yang tampil di website resmi.</p>
            </div>
            <button type="button" @click="openAdd()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita
            </button>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-4 gap-2 mb-4">
            @foreach([
                ['label' => 'Total',   'value' => $beritaStats['total'],   'color' => 'slate'],
                ['label' => 'Aktif',   'value' => $beritaStats['aktif'],   'color' => 'green'],
                ['label' => 'Draf',    'value' => $beritaStats['draf'],    'color' => 'amber'],
                ['label' => 'Penting', 'value' => $beritaStats['penting'], 'color' => 'red'],
            ] as $s)
            <div class="bg-{{ $s['color'] }}-50 border border-{{ $s['color'] }}-200 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-{{ $s['color'] }}-700">{{ $s['value'] }}</p>
                <p class="text-xs text-{{ $s['color'] }}-600 mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.kelola-website') }}" class="flex flex-wrap gap-2 mb-4">
            <input type="hidden" name="tab" value="berita">
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..."
                       class="rounded-lg border-slate-300 text-xs py-1.5 pl-8 pr-3 w-44 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="status" class="rounded-lg border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="aktif"  @selected(request('status') === 'aktif')>Aktif</option>
                <option value="draf"   @selected(request('status') === 'draf')>Draf</option>
            </select>
            <select name="kategori" class="rounded-lg border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                <option value="berita"      @selected(request('kategori') === 'berita')>Berita</option>
                <option value="pengumuman"  @selected(request('kategori') === 'pengumuman')>Pengumuman</option>
            </select>
            <button type="submit"
                    class="px-3 py-1.5 bg-slate-700 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition">
                Filter
            </button>
            @if(request()->hasAny(['search','status','kategori']))
                <a href="{{ route('admin.kelola-website', ['tab'=>'berita']) }}"
                   class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-200 transition">
                   Reset
                </a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Judul & Media</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($beritas as $item)
                    <tr class="hover:bg-slate-50 transition-colors">

                        {{-- Thumbnail + Judul --}}
                        <td class="px-4 py-3 text-xs">
                            <div class="flex items-center gap-3">

                                <div class="w-14 h-10 rounded-lg overflow-hidden shrink-0 bg-slate-100 relative border border-slate-200">
                                    @if($item->media_tipe === 'photo' && $item->media_file)
                                        <img src="{{ $item->media_file_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">

                                    @elseif($item->media_tipe === 'video' && $item->media_file)
                                        @if($item->media_thumbnail_url)
                                            <img src="{{ $item->media_thumbnail_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M15 10l4.553-2.277A1 1 0 0121 8.677V15.32a1 1 0 01-1.447.894L15 14v-4z"/>
                                                    <path d="M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-black/50 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    @elseif($item->media_tipe === 'link_youtube')
                                        @if($item->media_thumbnail_url)
                                            <img src="{{ $item->media_thumbnail_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-red-600/80 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    @elseif($item->media_tipe === 'link_facebook')
                                        @if($item->media_thumbnail_url)
                                            <img src="{{ $item->media_thumbnail_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white/80" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-black/40 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    @else
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-800 line-clamp-1 max-w-xs">{{ $item->judul }}</p>
                                    <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                        @if($item->is_penting)
                                            <span class="text-red-500 text-xs font-semibold">● Penting</span>
                                        @endif
                                        @if($item->has_media)
                                            @php
                                                $badgeClass = match($item->media_tipe) {
                                                    'photo'         => 'bg-blue-100 text-blue-700',
                                                    'video'         => 'bg-purple-100 text-purple-700',
                                                    'link_youtube'  => 'bg-red-100 text-red-700',
                                                    'link_facebook' => 'bg-indigo-100 text-indigo-700',
                                                    default         => 'bg-slate-100 text-slate-500',
                                                };
                                            @endphp
                                            <span class="px-1.5 py-0.5 text-xs rounded-md font-medium {{ $badgeClass }}">
                                                {{ $item->media_tipe_label }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($item->kategori === 'pengumuman')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">📢 Pengumuman</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">📰 Berita</span>
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500">
                            {{ $item->tanggal_publish?->format('d M Y') ?? '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            {!! $item->status_badge !!}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="inline-flex items-center gap-1">

                                {{-- Lihat di website --}}
                                @if($item->status === 'aktif')
                                    <a href="{{ route('website.berita.show', $item->slug) }}" target="_blank"
                                       title="Lihat di website"
                                       class="p-1.5 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @endif

                                {{-- Tombol Edit --}}
                                <button type="button"
                                        @click="openEdit({
                                            id: {{ $item->id }},
                                            judul: {{ Js::from($item->judul) }},
                                            ringkasan: {{ Js::from($item->ringkasan ?? '') }},
                                            isi: {{ Js::from($item->isi ?? '') }},
                                            kategori: '{{ $item->kategori }}',
                                            status: '{{ $item->status }}',
                                            tanggal_publish: '{{ $item->tanggal_publish?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i') }}',
                                            is_penting: {{ $item->is_penting ? 'true' : 'false' }},
                                            media_tipe: '{{ $item->media_tipe ?? 'none' }}',
                                            media_link: '{{ $item->media_link ?? '' }}',
                                            media_file_url: '{{ $item->media_file_url ?? '' }}',
                                            media_thumbnail_url: '{{ $item->media_thumbnail_url ?? '' }}',
                                            update_url: '{{ route('admin.berita.update', $item) }}'
                                        })"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition bg-indigo-50 text-indigo-600 hover:bg-indigo-100">
                                    ✏️ Edit
                                </button>

                                {{-- Toggle status --}}
                                <form action="{{ route('admin.berita.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="px-2.5 py-1.5 rounded-lg transition text-xs font-bold
                                                {{ $item->status === 'aktif'
                                                    ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                        {{ $item->status === 'aktif' ? 'Draf' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.berita.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus berita ini?\nTindakan tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition text-xs font-bold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-400">Belum ada berita atau pengumuman.</p>
                                <button type="button" @click="openAdd()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($beritas->hasPages())
            <div class="mt-4 text-xs">{{ $beritas->links() }}</div>
        @endif

    </div>{{-- /section daftar --}}


    {{-- Pratinjau publik --}}
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-emerald-800">Pratinjau Halaman Publik</p>
            <p class="text-xs text-emerald-600 mt-0.5">Lihat tampilan halaman berita seperti yang dilihat pengunjung.</p>
        </div>
        <a href="{{ route('website.berita') }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">
            Buka Website →
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         OVERLAY MODAL: FORM TAMBAH & EDIT BERITA
    ═══════════════════════════════════════════════════════ --}}
    <div 
        x-show="isOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Modal Card --}}
        <div 
            @click.away="closeModal()"
            class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-4xl max-h-[92vh] flex flex-col"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50 rounded-t-2xl">
                <div class="flex items-center gap-2">
                    <span x-show="!isEdit" class="p-1.5 bg-indigo-500 text-white rounded-lg text-xs font-bold">➕ Baru</span>
                    <span x-show="isEdit" class="p-1.5 bg-amber-500 text-white rounded-lg text-xs font-bold">✏️ Edit</span>
                    <h3 class="text-sm font-bold text-slate-900" x-text="isEdit ? 'Ubah Berita / Pengumuman' : 'Tambah Berita / Pengumuman Baru'"></h3>
                </div>
                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Wrapper --}}
            <form :action="formAction" method="POST" enctype="multipart/form-data" class="flex flex-col h-full overflow-hidden">
                @csrf
                <template x-if="methodField === 'PATCH'">
                    <input type="hidden" name="_method" value="PATCH">
                </template>
                <input type="hidden" name="id" :value="id">

                {{-- Modal Body (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[calc(92vh-130px)]">

                    {{-- Server-side validation errors --}}
                    @if($errors->any())
                        <div class="flex gap-2 bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
                            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="grid lg:grid-cols-5 gap-5">
                        
                        {{-- Kiri: Konten Utama (3/5) --}}
                        <div class="lg:col-span-3 space-y-4">
                            {{-- JUDUL --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Judul Berita <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul" x-model="judul"
                                       class="w-full rounded-lg border-slate-300 text-sm py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition"
                                       placeholder="Tulis judul berita atau pengumuman..." required>
                            </div>

                            {{-- RINGKASAN --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Ringkasan <span class="text-slate-400 font-normal">(maks. 500 karakter, opsional)</span>
                                </label>
                                <textarea name="ringkasan" rows="2" x-model="ringkasan"
                                          class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
                                          placeholder="Ringkasan singkat yang muncul di daftar halaman depan..."></textarea>
                            </div>

                            {{-- ISI --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Isi Berita / Konten Utama <span class="text-red-500">*</span>
                                </label>
                                <textarea name="isi" rows="10" x-model="isi"
                                          class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
                                          placeholder="Tulis isi berita secara lengkap di sini..." required></textarea>
                            </div>
                        </div>

                        {{-- Kanan: Pengaturan & Media (2/5) --}}
                        <div class="lg:col-span-2 space-y-4">
                            {{-- Pengaturan Form --}}
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3.5">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">⚙️ Pengaturan Posting</p>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                    <select name="kategori" x-model="kategori"
                                            class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="berita">📰 Berita</option>
                                        <option value="pengumuman">📢 Pengumuman</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Publish <span class="text-red-500">*</span></label>
                                    <select name="status" x-model="status"
                                            class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="aktif">✅ Aktif – tampil di website</option>
                                        <option value="draf">📝 Draf – simpan sebagai draf</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Publish</label>
                                    <input type="datetime-local" name="tanggal_publish" x-model="tanggalPublish"
                                           class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <label class="flex items-start gap-2 cursor-pointer pt-1">
                                    <input type="checkbox" name="is_penting" value="1" x-model="isPenting"
                                           class="rounded border-slate-300 text-red-500 focus:ring-red-400 mt-0.5">
                                    <span class="text-xs text-slate-700">
                                        🔴 Tandai sebagai <strong>Penting</strong>
                                        <span class="block text-slate-400 text-[10px] font-normal">Tampil di urutan teratas website</span>
                                    </span>
                                </label>
                            </div>

                            {{-- Media Attachment --}}
                            <div class="bg-indigo-50/40 border border-indigo-100 rounded-xl p-4 space-y-3">
                                <p class="text-[10px] font-bold text-indigo-900 uppercase tracking-wider">📎 Media Lampiran</p>

                                {{-- Pilihan Tipe Media --}}
                                <input type="hidden" name="media_tipe" :value="mediaTipe">

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-1.5">
                                    <template x-for="opt in mediaOptions" :key="opt.value">
                                        <label class="flex items-center gap-1.5 px-2 py-1.5 border-2 rounded-lg cursor-pointer transition-all text-xs font-medium"
                                               :class="mediaTipe === opt.value
                                                   ? 'border-indigo-600 bg-white text-indigo-700 shadow-sm font-bold'
                                                   : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700'">
                                            <input type="radio" class="sr-only" :value="opt.value" x-model="mediaTipe" @change="onTipeChange()">
                                            <span x-text="opt.icon"></span>
                                            <span x-text="opt.label"></span>
                                        </label>
                                    </template>
                                </div>

                                {{-- Upload FOTO (Diproteksi dengan :disabled apabila tidak aktif) --}}
                                <div x-show="mediaTipe === 'photo'" class="space-y-2 pt-1">
                                    <div x-show="isEdit && existingFileUrl" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <img :src="existingFileUrl" class="w-full max-h-32 object-cover">
                                        <p class="text-[10px] text-slate-400 p-1.5 border-t border-slate-100">📷 Foto saat ini. Upload baru jika ingin mengganti.</p>
                                    </div>
                                    <input type="file" id="modal-media-photo" name="media_file" accept="image/*"
                                           :disabled="mediaTipe !== 'photo'"
                                           @change="previewPhoto($event)"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    
                                    <div x-show="photoPreview" class="rounded-lg overflow-hidden border border-indigo-200 bg-white">
                                        <img :src="photoPreview" class="w-full max-h-32 object-cover">
                                    </div>
                                </div>

                                {{-- Upload VIDEO (Diproteksi dengan :disabled apabila tidak aktif) --}}
                                <div x-show="mediaTipe === 'video'" class="space-y-2 pt-1">
                                    <div x-show="isEdit && existingFileUrl" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <video :src="existingFileUrl" controls class="w-full max-h-32"></video>
                                        <p class="text-[10px] text-slate-400 p-1.5 border-t border-slate-100">🎥 Video saat ini. Upload baru jika ingin mengganti.</p>
                                    </div>
                                    <input type="file" id="modal-media-video" name="media_file" accept="video/*"
                                           :disabled="mediaTipe !== 'video'"
                                           @change="previewVideo($event)"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                                    <div x-show="videoPreview" class="rounded-lg overflow-hidden border border-indigo-200 bg-white">
                                        <video :src="videoPreview" controls class="w-full max-h-32"></video>
                                    </div>
                                </div>

                                {{-- Input YouTube (Diproteksi dengan :disabled apabila tidak aktif) --}}
                                <div x-show="mediaTipe === 'link_youtube'" class="space-y-2 pt-1">
                                    <input type="url" name="media_link" x-model="mediaLinkValue"
                                           :disabled="mediaTipe !== 'link_youtube'"
                                           @input="previewYoutube($event.target.value)"
                                           class="w-full rounded-lg border-slate-300 text-xs py-1.5 focus:border-red-500 focus:ring-red-400"
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    
                                    <div x-show="youtubeThumbnail" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <img :src="youtubeThumbnail" class="w-full max-h-32 object-cover">
                                    </div>
                                </div>

                                {{-- Input Facebook (Diproteksi dengan :disabled apabila tidak aktif) --}}
                                <div x-show="mediaTipe === 'link_facebook'" class="space-y-2 pt-1">
                                    <input type="url" name="media_link" x-model="mediaLinkValue"
                                           :disabled="mediaTipe !== 'link_facebook'"
                                           class="w-full rounded-lg border-slate-300 text-xs py-1.5 focus:border-blue-500 focus:ring-blue-400"
                                           placeholder="https://www.facebook.com/watch?v=...">
                                    <p class="text-[10px] text-slate-500">Masukkan link video publik facebook.</p>
                                </div>

                                {{-- Custom Thumbnail (Opsional) --}}
                                <div x-show="mediaTipe !== 'none'" class="pt-2 border-t border-indigo-100 space-y-1.5">
                                    <label class="block text-[11px] font-semibold text-slate-700">Thumbnail Kustom <span class="text-slate-400 font-normal">(opsional)</span></label>
                                    
                                    <div x-show="isEdit && existingThumbnailUrl" class="rounded overflow-hidden border border-slate-200 bg-white w-20 h-14">
                                        <img :src="existingThumbnailUrl" class="w-full h-full object-cover">
                                    </div>

                                    <input type="file" id="modal-thumbnail" name="media_thumbnail" accept="image/*"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between rounded-b-2xl">
                    <button type="button" @click="closeModal()"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Publikasikan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>{{-- /x-data --}}


{{-- ═══════════════════════════════════════════════════════
     Alpine.js Component
═══════════════════════════════════════════════════════ --}}
<script>
function beritaTab() {
    // Membaca state error server-side untuk pemulihan otomatis
    const hasErrors = @json($errors->any());
    const isEditMode = @json(old('_method') === 'PATCH');
    const oldId = @json(old('id', ''));
    const beritas = @json($beritas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $beritas->items() : $beritas);

    let initialAction = @json(route('admin.berita.store'));
    let existingFileUrl = '';
    let existingThumbnailUrl = '';

    // Mengembalikan data preview jika error terjadi pada mode edit
    if (isEditMode && oldId) {
        initialAction = `{{ route('admin.berita.update', ':id') }}`.replace(':id', oldId);
        const matchedItem = beritas.find(item => item.id == oldId);
        if (matchedItem) {
            existingFileUrl = matchedItem.media_file_url || '';
            existingThumbnailUrl = matchedItem.media_thumbnail_url || '';
        }
    }

    return {
        // ── State Modal ──
        isOpen: hasErrors,
        isEdit: isEditMode,
        formAction: initialAction,
        methodField: isEditMode ? 'PATCH' : 'POST',

        // ── Form Fields bound with x-model ──
        id: oldId,
        judul: @json(old('judul', '')),
        ringkasan: @json(old('ringkasan', '')),
        isi: @json(old('isi', '')),
        kategori: @json(old('kategori', 'berita')),
        status: @json(old('status', 'draf')),
        tanggalPublish: @json(old('tanggal_publish', now()->format('Y-m-d\TH:i'))),
        isPenting: @json(old('is_penting') ? true : false),

        // ── Media Attachment State ──
        mediaTipe: @json(old('media_tipe', 'none')),
        mediaLinkValue: @json(old('media_link', '')),
        existingFileUrl: existingFileUrl,
        existingThumbnailUrl: existingThumbnailUrl,
        photoPreview: null,
        videoPreview: null,
        youtubeThumbnail: null,

        // ── Opsi media ──
        mediaOptions: [
            { value: 'none',          icon: '🚫', label: 'Tanpa Media' },
            { value: 'photo',         icon: '🖼️', label: 'Foto' },
            { value: 'video',         icon: '🎥', label: 'Video' },
            { value: 'link_youtube',  icon: '▶️', label: 'YouTube' },
            { value: 'link_facebook', icon: '📘', label: 'Facebook' },
        ],

        init() {
            if (this.mediaTipe === 'link_youtube' && this.mediaLinkValue) {
                this.previewYoutube(this.mediaLinkValue);
            }
        },

        // ── Buka form mode Tambah ──
        openAdd() {
            this.isEdit = false;
            this.formAction = "{{ route('admin.berita.store') }}";
            this.methodField = 'POST';
            this.resetForm();
            this.isOpen = true;
        },

        // ── Buka form mode Edit ──
        openEdit(item) {
            this.isEdit = true;
            this.formAction = item.update_url;
            this.methodField = 'PATCH';

            this.id = item.id;
            this.judul = item.judul;
            this.ringkasan = item.ringkasan || '';
            this.isi = item.isi || '';
            this.kategori = item.kategori || 'berita';
            this.status = item.status || 'draf';
            this.tanggalPublish = item.tanggal_publish;
            this.isPenting = !!item.is_penting;

            this.mediaTipe = item.media_tipe || 'none';
            this.mediaLinkValue = item.media_link || '';
            this.existingFileUrl = item.media_file_url || '';
            this.existingThumbnailUrl = item.media_thumbnail_url || '';

            this.photoPreview = null;
            this.videoPreview = null;
            this.youtubeThumbnail = null;

            if (this.mediaTipe === 'link_youtube' && this.mediaLinkValue) {
                this.previewYoutube(this.mediaLinkValue);
            }

            this.isOpen = true;
        },

        // ── Batal / Tutup ──
        closeModal() {
            this.isOpen = false;
            this.resetForm();
        },

        // ── Reset semua field & state ──
        resetForm() {
            this.id = '';
            this.judul = '';
            this.ringkasan = '';
            this.isi = '';
            this.kategori = 'berita';
            this.status = 'draf';
            this.tanggalPublish = "{{ now()->format('Y-m-d\TH:i') }}";
            this.isPenting = false;

            this.mediaTipe = 'none';
            this.mediaLinkValue = '';
            this.existingFileUrl = '';
            this.existingThumbnailUrl = '';
            this.photoPreview = null;
            this.videoPreview = null;
            this.youtubeThumbnail = null;

            const filePhoto = document.getElementById('modal-media-photo');
            if (filePhoto) filePhoto.value = '';
            const fileVideo = document.getElementById('modal-media-video');
            if (fileVideo) fileVideo.value = '';
            const thumbInput = document.getElementById('modal-thumbnail');
            if (thumbInput) thumbInput.value = '';
        },

        // ── Ganti tipe media — reset link & preview ──
        onTipeChange() {
            this.photoPreview = null;
            this.videoPreview = null;
            this.youtubeThumbnail = null;
            this.existingFileUrl = '';
            this.mediaLinkValue = '';
        },

        // ── Preview foto ──
        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) this.photoPreview = URL.createObjectURL(file);
        },

        // ── Preview video ──
        previewVideo(event) {
            const file = event.target.files[0];
            if (file) this.videoPreview = URL.createObjectURL(file);
        },

        // ── Preview thumbnail YouTube ──
        previewYoutube(url) {
            if (!url) { this.youtubeThumbnail = null; return; }
            const patterns = [
                /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) {
                    this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg`;
                    return;
                }
            }
            this.youtubeThumbnail = null;
        }
    };
}
</script>