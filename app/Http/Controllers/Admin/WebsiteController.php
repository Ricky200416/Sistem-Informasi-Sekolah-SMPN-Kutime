<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PageContent;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // INDEX — Halaman utama kelola website (semua tab)
    // ══════════════════════════════════════════════════════════════

    public function kelolaWebsite(Request $request)
    {
        $tab       = $request->query('tab', 'home');
        $contents  = PageContent::all()->keyBy('key');
        $heroMedia = PageContent::getHeroMedia();
        $kontak    = PageContent::getKontak();
        $stats     = PageContent::getStats();

        // ── Berita ────────────────────────────────────────────────
        $beritaQuery = Berita::with('user')->latest();

        if ($request->filled('search')) {
            $beritaQuery->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $beritaQuery->where('status', $request->status);
        }
        if ($request->filled('kategori') && $tab === 'berita') {
            $beritaQuery->where('kategori', $request->kategori);
        }

        $beritas     = $beritaQuery->paginate(8, ['*'], 'berita_page')->withQueryString();
        $beritaStats = [
            'total'   => Berita::count(),
            'aktif'   => Berita::where('status', 'aktif')->count(),
            'draf'    => Berita::where('status', 'draf')->count(),
            'penting' => Berita::where('is_penting', true)->count(),
        ];

        // ── Galeri ────────────────────────────────────────────────
        $galeriQuery = Galeri::with('user')->terurut();

        if ($request->filled('galeri_kategori')) {
            $galeriQuery->where('kategori', $request->galeri_kategori);
        }
        if ($request->filled('galeri_tipe')) {
            $galeriQuery->where('tipe', $request->galeri_tipe);
        }
        if ($request->filled('galeri_status')) {
            $galeriQuery->where('status', $request->galeri_status);
        }

        $galeris     = $galeriQuery->paginate(12, ['*'], 'galeri_page')->withQueryString();
        $galeriStats = [
            'total' => Galeri::count(),
            'aktif' => Galeri::where('status', 'aktif')->count(),
            'draf'  => Galeri::where('status', 'draf')->count(),
        ];
        
        // Mengambil opsi kategori untuk filter & form input modal
        $galeriKategoriOptions = Galeri::kategoriOptions();
        $kategoriOptions       = $galeriKategoriOptions; 

        // Menambahkan opsi tipe media yang dibutuhkan oleh template radio button di form modal
        $tipeOptions = [
            'photo'         => '🖼️ Foto',
            'video'         => '🎥 Video',
            'link_youtube'  => '▶️ YouTube',
            'link_facebook' => '📘 Facebook',
        ];

        return view('admin.kelola-website.index', compact(
            'tab', 'contents', 'heroMedia', 'kontak', 'stats',
            'beritas', 'beritaStats',
            'galeris', 'galeriStats', 'galeriKategoriOptions', 'kategoriOptions', 'tipeOptions'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    // STORE BERITA — Simpan berita/pengumuman baru
    // ══════════════════════════════════════════════════════════════

    public function storeBerita(Request $request)
    {
        $mediaTipe = $request->input('media_tipe', 'none');

        // Validasi murni melonggarkan semua aturan file & size untuk mencegah error upload
        $rules = [
            'judul'           => 'required|string|max:255',
            'ringkasan'       => 'nullable|string|max:500',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:berita,pengumuman',
            'status'          => 'required|in:aktif,draf',
            'tanggal_publish' => 'nullable|date',
            'is_penting'      => 'nullable|boolean',
            'media_tipe'      => 'required|in:none,photo,video,link_youtube,link_facebook',
            'media_link'      => in_array($mediaTipe, ['link_youtube', 'link_facebook']) ? 'required|url' : 'nullable|url',
            'media_file'      => 'nullable', // Nullable mutlak tanpa batasan tipe atau ukuran
            'media_thumbnail' => 'nullable', 
        ];

        $validated = $request->validate($rules);

        $berita = new Berita();
        $berita->judul           = $validated['judul'];
        $berita->ringkasan       = $validated['ringkasan'] ?? null;
        $berita->isi             = $validated['isi'];
        $berita->kategori        = $validated['kategori'];
        $berita->status          = $validated['status'];
        $berita->tanggal_publish = $validated['tanggal_publish'] ?? now();
        $berita->is_penting      = $request->boolean('is_penting');
        $berita->media_tipe      = $mediaTipe;
        $berita->user_id         = auth()->id();

        // Simpan link jika tipe link
        if (in_array($mediaTipe, ['link_youtube', 'link_facebook'])) {
            $berita->media_link = $validated['media_link'];
        }

        // Upload file media secara aman jika dilampirkan
        if ($request->hasFile('media_file') && $request->file('media_file')->isValid()) {
            $folder = $mediaTipe === 'video' ? 'berita/videos' : 'berita/photos';
            $berita->media_file = $request->file('media_file')->store($folder, 'public');
        }

        // Upload thumbnail jika dilampirkan
        if ($request->hasFile('media_thumbnail') && $request->file('media_thumbnail')->isValid()) {
            $berita->media_thumbnail = $request->file('media_thumbnail')->store('berita/thumbnails', 'public');
        }

        $berita->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'berita'])
            ->with('success', 'Berita/Pengumuman berhasil ditambahkan.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE BERITA — Perbarui berita/pengumuman
    // ══════════════════════════════════════════════════════════════

    public function updateBerita(Request $request, Berita $berita)
    {
        $mediaTipe = $request->input('media_tipe', 'none');

        // Melonggarkan semua format dan ukuran file
        $rules = [
            'judul'           => 'required|string|max:255',
            'ringkasan'       => 'nullable|string|max:500',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:berita,pengumuman',
            'status'          => 'required|in:aktif,draf',
            'tanggal_publish' => 'nullable|date',
            'is_penting'      => 'nullable|boolean',
            'media_tipe'      => 'required|in:none,photo,video,link_youtube,link_facebook',
            'media_link'      => in_array($mediaTipe, ['link_youtube', 'link_facebook']) ? 'required|url' : 'nullable|url',
            'media_file'      => 'nullable',
            'media_thumbnail' => 'nullable',
        ];

        $validated = $request->validate($rules);

        $berita->judul           = $validated['judul'];
        $berita->ringkasan       = $validated['ringkasan'] ?? null;
        $berita->isi             = $validated['isi'];
        $berita->kategori        = $validated['kategori'];
        $berita->status          = $validated['status'];
        $berita->tanggal_publish = $validated['tanggal_publish'] ?? $berita->tanggal_publish;
        $berita->is_penting      = $request->boolean('is_penting');
        $berita->media_tipe      = $mediaTipe;

        // Simpan link jika tipe link; kosongkan jika bukan
        $berita->media_link = in_array($mediaTipe, ['link_youtube', 'link_facebook'])
            ? $validated['media_link']
            : null;

        // Ganti tipe media — hapus file lama jika tipe berubah
        if ($berita->isDirty('media_tipe')) {
            if ($berita->getOriginal('media_file')) {
                Storage::disk('public')->delete($berita->getOriginal('media_file'));
                $berita->media_file = null;
            }
        }

        // Upload file media baru jika ada
        if ($request->hasFile('media_file') && $request->file('media_file')->isValid()) {
            if ($berita->media_file) {
                Storage::disk('public')->delete($berita->media_file);
            }
            $folder = $mediaTipe === 'video' ? 'berita/videos' : 'berita/photos';
            $berita->media_file = $request->file('media_file')->store($folder, 'public');
        }

        // Upload thumbnail baru jika ada
        if ($request->hasFile('media_thumbnail') && $request->file('media_thumbnail')->isValid()) {
            if ($berita->media_thumbnail) {
                Storage::disk('public')->delete($berita->media_thumbnail);
            }
            $berita->media_thumbnail = $request->file('media_thumbnail')->store('berita/thumbnails', 'public');
        }

        $berita->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'berita'])
            ->with('success', 'Berita/Pengumuman berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // TOGGLE STATUS BERITA
    // ══════════════════════════════════════════════════════════════

    public function toggleStatusBerita(Berita $berita)
    {
        $berita->status = $berita->status === 'aktif' ? 'draf' : 'aktif';
        $berita->save();

        return back()->with('success', 'Status berita berhasil diubah.');
    }

    // ══════════════════════════════════════════════════════════════
    // DESTROY BERITA
    // ══════════════════════════════════════════════════════════════

    public function destroyBerita(Berita $berita)
    {
        if ($berita->media_file) {
            Storage::disk('public')->delete($berita->media_file);
        }
        if ($berita->media_thumbnail) {
            Storage::disk('public')->delete($berita->media_thumbnail);
        }
        $berita->delete();

        return back()->with('success', 'Berita/Pengumuman berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════
    // STORE GALERI — Simpan galeri baru tanpa validasi ketat file
    // ══════════════════════════════════════════════════════════════

    public function storeGaleri(Request $request)
    {
        $validated = $request->validate([
            'tipe'      => 'required|string',
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori'  => 'required|string',
            'urutan'    => 'nullable|integer',
            'status'    => 'required|string',
            'link_url'  => 'nullable|url',
            'file_path' => 'nullable', // Longgar tanpa batasan ukuran & tipe
            'thumbnail' => 'nullable',
        ]);

        $galeri = new Galeri();
        $galeri->tipe      = $validated['tipe'];
        $galeri->judul     = $validated['judul'];
        $galeri->deskripsi = $validated['deskripsi'] ?? null;
        $galeri->kategori  = $validated['kategori'];
        $galeri->urutan    = $validated['urutan'] ?? 0;
        $galeri->status    = $validated['status'];
        $galeri->user_id   = auth()->id();

        if (in_array($validated['tipe'], ['link_youtube', 'link_facebook'])) {
            $galeri->link_url = $validated['link_url'] ?? null;
        }

        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            $folder = $validated['tipe'] === 'video' ? 'galeri/videos' : 'galeri/photos';
            $galeri->file_path = $request->file('file_path')->store($folder, 'public');
        }

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $galeri->thumbnail = $request->file('thumbnail')->store('galeri/thumbnails', 'public');
        }

        $galeri->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'galeri'])
            ->with('success', 'Media galeri berhasil ditambahkan.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE GALERI — Perbarui media galeri tanpa validasi ketat file
    // ══════════════════════════════════════════════════════════════

    public function updateGaleri(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'tipe'      => 'required|string',
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori'  => 'required|string',
            'urutan'    => 'nullable|integer',
            'status'    => 'required|string',
            'link_url'  => 'nullable|url',
            'file_path' => 'nullable',
            'thumbnail' => 'nullable',
        ]);

        $galeri->tipe      = $validated['tipe'];
        $galeri->judul     = $validated['judul'];
        $galeri->deskripsi = $validated['deskripsi'] ?? null;
        $galeri->kategori  = $validated['kategori'];
        $galeri->urutan    = $validated['urutan'] ?? 0;
        $galeri->status    = $validated['status'];

        $galeri->link_url = in_array($validated['tipe'], ['link_youtube', 'link_facebook'])
            ? ($validated['link_url'] ?? null)
            : null;

        if ($galeri->isDirty('tipe')) {
            if ($galeri->getOriginal('file_path')) {
                Storage::disk('public')->delete($galeri->getOriginal('file_path'));
                $galeri->file_path = null;
            }
        }

        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            if ($galeri->file_path) {
                Storage::disk('public')->delete($galeri->file_path);
            }
            $folder = $validated['tipe'] === 'video' ? 'galeri/videos' : 'galeri/photos';
            $galeri->file_path = $request->file('file_path')->store($folder, 'public');
        }

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            if ($galeri->thumbnail) {
                Storage::disk('public')->delete($galeri->thumbnail);
            }
            $galeri->thumbnail = $request->file('thumbnail')->store('galeri/thumbnails', 'public');
        }

        $galeri->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'galeri'])
            ->with('success', 'Media galeri berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // TOGGLE STATUS GALERI
    // ══════════════════════════════════════════════════════════════

    public function toggleStatusGaleri(Galeri $galeri)
    {
        $galeri->status = $galeri->status === 'aktif' ? 'draf' : 'aktif';
        $galeri->save();

        return back()->with('success', 'Status media galeri berhasil diubah.');
    }

    // ══════════════════════════════════════════════════════════════
    // DESTROY GALERI
    // ══════════════════════════════════════════════════════════════

    public function destroyGaleri(Galeri $galeri)
    {
        if ($galeri->file_path) {
            Storage::disk('public')->delete($galeri->file_path);
        }
        if ($galeri->thumbnail) {
            Storage::disk('public')->delete($galeri->thumbnail);
        }
        $galeri->delete();

        return back()->with('success', 'Media galeri berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════
    // METHOD-METHOD LAIN
    // ══════════════════════════════════════════════════════════════

    public function updateHome(Request $request)
    {
        $validated = $request->validate([
            'hero_title'             => 'nullable|string|max:255',
            'hero_description'       => 'nullable|string|max:1000',
            'tentang'                => 'nullable|string',
            'visi'                   => 'nullable|string',
            'misi'                   => 'nullable|string',
            'fasilitas_ruang_kelas'  => 'nullable|string|max:500',
            'fasilitas_perpustakaan' => 'nullable|string|max:500',
            'fasilitas_lapangan'     => 'nullable|string|max:500',
            'fasilitas_laboratorium' => 'nullable|string|max:500',
            'sambutan_nama'          => 'nullable|string|max:255',
            'sambutan_jabatan'       => 'nullable|string|max:255',
            'sambutan_teks'          => 'nullable|string',
            'sambutan_foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'info_ppdb'              => 'nullable|string',
            'info_kalender'          => 'nullable|string',
        ]);

        $simpleKeys = [
            'hero_title', 'hero_description',
            'fasilitas_ruang_kelas', 'fasilitas_perpustakaan',
            'fasilitas_lapangan', 'fasilitas_laboratorium',
            'sambutan_nama', 'sambutan_jabatan', 'sambutan_teks',
            'info_ppdb', 'info_kalender',
        ];

        foreach ($simpleKeys as $key) {
            if ($request->has($key)) {
                PageContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $validated[$key] ?? '', 'section' => explode('_', $key)[0] ?? 'general']
                );
            }
        }

        foreach (['tentang', 'visi', 'misi'] as $key) {
            if ($request->has($key)) {
                PageContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $validated[$key] ?? '', 'section' => $key]
                );
            }
        }

        if ($request->hasFile('sambutan_foto')) {
            $oldPath = PageContent::where('key', 'sambutan_foto_path')->first()?->value;
            if ($oldPath) Storage::disk('public')->delete($oldPath);
            $newPath = $request->file('sambutan_foto')->store('sambutan', 'public');
            PageContent::updateOrCreate(
                ['key' => 'sambutan_foto_path'],
                ['value' => $newPath, 'section' => 'sambutan']
            );
        }

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Konten halaman Beranda berhasil diperbarui.');
    }

    public function updateHeroMedia(Request $request)
    {
        $tipe  = $request->input('hero_media_tipe', 'none');
        $rules = [
            'hero_media_tipe'     => 'required|in:none,image,video,youtube,slideshow',
            'hero_slide_interval' => 'nullable|integer|min:1000|max:30000',
        ];

        if ($tipe === 'image') {
            $rules['hero_files']   = 'nullable|array|max:1';
            $rules['hero_files.*'] = 'image|mimes:jpg,jpeg,png,webp|max:102400';
        } elseif ($tipe === 'video') {
            $rules['hero_files']   = 'nullable|array|max:1';
            $rules['hero_files.*'] = 'mimes:mp4,mov,webm|max:204800';
        } elseif ($tipe === 'youtube') {
            $rules['hero_media_link'] = 'required|url';
        } elseif ($tipe === 'slideshow') {
            $rules['hero_files']   = 'nullable|array|max:10';
            $rules['hero_files.*'] = 'image|mimes:jpg,jpeg,png,webp|max:102400';
        }

        $request->validate($rules);

        $heroMedia     = PageContent::firstOrNew(['key' => 'hero_media_settings'], ['section' => 'hero_media']);
        $existingFiles = $heroMedia->heroFilesArray ?? [];
        $keepFiles     = $request->input('keep_files', []);

        foreach ($existingFiles as $path) {
            if (!in_array($path, $keepFiles)) Storage::disk('public')->delete($path);
        }

        $filesToKeep = array_values(array_filter($existingFiles, fn($p) => in_array($p, $keepFiles)));
        $newPaths    = [];

        if ($request->hasFile('hero_files')) {
            foreach ($request->file('hero_files') as $file) {
                $folder     = $tipe === 'video' ? 'hero/videos' : 'hero/images';
                $newPaths[] = $file->store($folder, 'public');
            }
        }

        $allFiles = array_merge($filesToKeep, $newPaths);

        $heroMedia->hero_media_tipe     = $tipe;
        $heroMedia->hero_media_files    = $tipe !== 'none' ? json_encode(array_values($allFiles)) : null;
        $heroMedia->hero_media_link     = $tipe === 'youtube' ? $request->input('hero_media_link') : null;
        $heroMedia->hero_slide_interval = (int) $request->input('hero_slide_interval', 4000);
        $heroMedia->value               = 'hero_media_settings';
        $heroMedia->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Media hero berhasil diperbarui.');
    }

    public function deleteHeroFile(Request $request)
    {
        $path      = $request->input('path');
        $heroMedia = PageContent::where('key', 'hero_media_settings')->first();

        if (!$heroMedia || !$path) return back()->with('error', 'File tidak ditemukan.');

        Storage::disk('public')->delete($path);
        $files = array_values(array_filter($heroMedia->heroFilesArray, fn($f) => $f !== $path));
        $heroMedia->hero_media_files = json_encode($files);
        $heroMedia->save();

        return back()->with('success', 'File berhasil dihapus.');
    }

    public function updateStats(Request $request)
    {
        $validated = $request->validate([
            'stat_siswa'    => 'nullable|string|max:50',
            'stat_guru'     => 'nullable|string|max:50',
            'stat_prestasi' => 'nullable|string|max:50',
            'stat_ekskul'   => 'nullable|string|max:50',
        ]);

        $row = PageContent::firstOrNew(['key' => 'stats_settings'], ['section' => 'stats']);
        $row->fill($validated);
        $row->value = 'stats_settings';
        $row->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Statistik sekolah berhasil diperbarui.');
    }

    public function updateKontak(Request $request)
    {
        $validated = $request->validate([
            'kontak_telepon'    => 'nullable|string|max:30',
            'kontak_email'      => 'nullable|email|max:100',
            'kontak_alamat'     => 'nullable|string|max:500',
            'kontak_maps_embed' => 'nullable|string|max:5000',
            'sosmed_instagram'  => ['nullable', 'url', 'max:255'],
            'sosmed_facebook'   => ['nullable', 'url', 'max:255'],
            'sosmed_youtube'    => ['nullable', 'url', 'max:255'],
            'sosmed_twitter'    => ['nullable', 'url', 'max:255'],
        ]);

        if (!empty($validated['kontak_maps_embed'])) {
            $validated['kontak_maps_embed'] = html_entity_decode(
                $validated['kontak_maps_embed'], ENT_QUOTES | ENT_HTML5, 'UTF-8'
            );
        }

        $row = PageContent::firstOrNew(['key' => 'kontak_settings'], ['section' => 'kontak']);
        $row->fill($validated);
        $row->value = 'kontak_settings';
        $row->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'kontak'])
            ->with('success', 'Kontak & media sosial berhasil diperbarui.');
    }

    public function updateSchoolSettings(Request $request)
    {
        $request->validate([
            'nama_sekolah'   => 'nullable|string|max:255',
            'singkatan'      => 'nullable|string|max:100',
            'tagline_footer' => 'nullable|string|max:500',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon'        => 'nullable|mimes:png,jpg,jpeg,ico|max:512',
        ]);

        if ($request->filled('nama_sekolah')) SchoolSetting::set('nama_sekolah', $request->nama_sekolah);
        if ($request->filled('singkatan'))    SchoolSetting::set('singkatan', $request->singkatan);
        if ($request->filled('tagline_footer')) SchoolSetting::set('tagline_footer', $request->tagline_footer);

        if ($request->hasFile('logo')) {
            $oldLogo = SchoolSetting::get('logo_path');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            SchoolSetting::set('logo_path', $request->file('logo')->store('school', 'public'));
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = SchoolSetting::get('favicon_path');
            if ($oldFavicon) Storage::disk('public')->delete($oldFavicon);
            SchoolSetting::set('favicon_path', $request->file('favicon')->store('school', 'public'));
        }

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'identitas'])
            ->with('success', 'Identitas sekolah berhasil diperbarui.');
    }

    public function deleteSambutanFoto(Request $request)
    {
        $record = PageContent::where('key', 'sambutan_foto_path')->first();
        if (!$record || !$record->value) return back()->with('error', 'Foto tidak ditemukan.');

        Storage::disk('public')->delete($record->value);
        $record->update(['value' => null]);

        return back()->with('success', 'Foto sambutan berhasil dihapus.');
    }

    public function deleteLogo(Request $request)
    {
        $logoPath = SchoolSetting::get('logo_path');
        if (!$logoPath) return back()->with('error', 'Logo tidak ditemukan.');

        Storage::disk('public')->delete($logoPath);
        SchoolSetting::set('logo_path', null);

        return back()->with('success', 'Logo sekolah berhasil dihapus.');
    }
}