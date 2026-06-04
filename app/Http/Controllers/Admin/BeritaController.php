<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $mediaTipe = $request->input('media_tipe', 'none');

        $rules = [
            'judul'           => 'required|string|max:255',
            'ringkasan'       => 'nullable|string|max:500',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:berita,pengumuman',
            'status'          => 'required|in:aktif,draf',
            'tanggal_publish' => 'nullable|date',
            'media_tipe'      => 'required|in:none,photo,video,link_youtube,link_facebook',
            'media_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:9999999',
        ];

        // Validasi file hanya saat tipe photo/video
        if ($mediaTipe === 'photo') {
            $rules['media_file'] = 'required|image|mimes:jpg,jpeg,png,webp,gif';
        } elseif ($mediaTipe === 'video') {
            $rules['media_file'] = 'required|mimes:mp4,mov,avi,mkv,webm';
        } elseif (in_array($mediaTipe, ['link_youtube', 'link_facebook'])) {
            $rules['media_link'] = 'required|url';
        }

        $validated = $request->validate($rules);

        $data = [
            'judul'           => $validated['judul'],
            'ringkasan'       => $validated['ringkasan'] ?? null,
            'isi'             => $validated['isi'],
            'kategori'        => $validated['kategori'],
            'is_penting'      => $request->boolean('is_penting'),
            'status'          => $validated['status'],
            'tanggal_publish' => $validated['tanggal_publish'] ?? now(),
            'media_tipe'      => $mediaTipe,
            'media_file'      => null,
            'media_link'      => null,
            'media_thumbnail' => null,
            'user_id'         => Auth::id(),
            'slug'            => Str::slug($validated['judul']),
        ];

        // Simpan file upload (foto/video)
        if (in_array($mediaTipe, ['photo', 'video']) && $request->hasFile('media_file')) {
            $folder = $mediaTipe === 'photo' ? 'berita/photos' : 'berita/videos';
            $data['media_file'] = $request->file('media_file')->store($folder, 'public');
        }

        // Simpan link eksternal
        if (in_array($mediaTipe, ['link_youtube', 'link_facebook']) && $request->filled('media_link')) {
            $data['media_link'] = $request->input('media_link');
        }

        // Simpan thumbnail kustom
        if ($request->hasFile('media_thumbnail')) {
            $data['media_thumbnail'] = $request->file('media_thumbnail')->store('berita/thumbnails', 'public');
        }

        Berita::create($data);

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'berita'])
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $mediaTipe = $request->input('media_tipe', 'none');

        $rules = [
            'judul'           => 'required|string|max:255',
            'ringkasan'       => 'nullable|string|max:500',
            'isi'             => 'required|string',
            'kategori'        => 'required|in:berita,pengumuman',
            'status'          => 'required|in:aktif,draf',
            'tanggal_publish' => 'nullable|date',
            'media_tipe'      => 'required|in:none,photo,video,link_youtube,link_facebook',
            'media_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:9999999',
        ];

        if ($mediaTipe === 'photo') {
            $rules['media_file'] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif';
        } elseif ($mediaTipe === 'video') {
            $rules['media_file'] = 'nullable|mimes:mp4,mov,avi,mkv,webm';
        } elseif (in_array($mediaTipe, ['link_youtube', 'link_facebook'])) {
            $rules['media_link'] = 'required|url';
        }

        $validated = $request->validate($rules);

        $data = [
            'judul'           => $validated['judul'],
            'ringkasan'       => $validated['ringkasan'] ?? null,
            'isi'             => $validated['isi'],
            'kategori'        => $validated['kategori'],
            'is_penting'      => $request->boolean('is_penting'),
            'status'          => $validated['status'],
            'tanggal_publish' => $validated['tanggal_publish'] ?? $berita->tanggal_publish,
            'media_tipe'      => $mediaTipe,
        ];

        // Tipe media berubah → hapus file/link lama
        if ($mediaTipe !== $berita->media_tipe) {
            if ($berita->media_file) {
                Storage::disk('public')->delete($berita->media_file);
            }
            $data['media_file'] = null;
            $data['media_link'] = null;
        }

        // Upload file baru (foto/video)
        if (in_array($mediaTipe, ['photo', 'video']) && $request->hasFile('media_file')) {
            if ($berita->media_file) {
                Storage::disk('public')->delete($berita->media_file);
            }
            $folder = $mediaTipe === 'photo' ? 'berita/photos' : 'berita/videos';
            $data['media_file'] = $request->file('media_file')->store($folder, 'public');
        }

        // Simpan link eksternal
        if (in_array($mediaTipe, ['link_youtube', 'link_facebook']) && $request->filled('media_link')) {
            $data['media_link'] = $request->input('media_link');
        }

        // Hapus semua media jika tipe none
        if ($mediaTipe === 'none') {
            if ($berita->media_file) Storage::disk('public')->delete($berita->media_file);
            $data['media_file'] = null;
            $data['media_link'] = null;
        }

        // Thumbnail baru
        if ($request->hasFile('media_thumbnail')) {
            if ($berita->media_thumbnail) {
                Storage::disk('public')->delete($berita->media_thumbnail);
            }
            $data['media_thumbnail'] = $request->file('media_thumbnail')->store('berita/thumbnails', 'public');
        }

        $berita->update($data);

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'berita'])
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->media_file)      Storage::disk('public')->delete($berita->media_file);
        if ($berita->media_thumbnail) Storage::disk('public')->delete($berita->media_thumbnail);
        $berita->delete();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'berita'])
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function toggleStatus(Berita $berita)
    {
        $berita->update(['status' => $berita->status === 'aktif' ? 'draf' : 'aktif']);
        return back()->with('success', 'Status berita diperbarui.');
    }
}