<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function create()
    {
        $kategoriOptions = Galeri::kategoriOptions();
        $tipeOptions     = Galeri::tipeOptions();
        return view('admin.galeri.create', compact('kategoriOptions', 'tipeOptions'));
    }

    public function store(Request $request)
    {
        $tipe = $request->input('tipe', 'photo');

        $rules = [
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe'      => 'required|in:photo,video,link_youtube,link_facebook',
            'kategori'  => 'required|in:kegiatan,akademik,prestasi,lainnya',
            'status'    => 'required|in:aktif,draf',
            'urutan'    => 'nullable|integer|min:0',
        ];

        // Validasi berbeda tergantung tipe
        if (in_array($tipe, ['photo', 'video'])) {
            $rules['file_path'] = 'required|file|max:2097152'; // 2GB = 2*1024*1024 KB
            if ($tipe === 'photo') {
                $rules['file_path'] = 'required|image|mimes:jpg,jpeg,png,webp,gif';
            } else {
                $rules['file_path'] = 'required|mimes:mp4,mov,avi,mkv,webm';
            }
            $rules['thumbnail'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:9999999';
        } else {
            $rules['link_url']  = 'required|url';
            $rules['thumbnail'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:9999999';
        }

        $validated = $request->validate($rules);

        $data = [
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tipe'      => $tipe,
            'kategori'  => $validated['kategori'],
            'status'    => $validated['status'],
            'urutan'    => $validated['urutan'] ?? 0,
            'user_id'   => Auth::id(),
        ];

        if (in_array($tipe, ['photo', 'video'])) {
            $folder = $tipe === 'photo' ? 'galeri/photos' : 'galeri/videos';
            $data['file_path'] = $request->file('file_path')->store($folder, 'public');
        } else {
            $data['link_url'] = $validated['link_url'];
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('galeri/thumbnails', 'public');
        }

        Galeri::create($data);

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'galeri'])
            ->with('success', 'Media galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri)
    {
        $kategoriOptions = Galeri::kategoriOptions();
        $tipeOptions     = Galeri::tipeOptions();
        return view('admin.galeri.edit', compact('galeri', 'kategoriOptions', 'tipeOptions'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $tipe = $request->input('tipe', $galeri->tipe);

        $rules = [
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe'      => 'required|in:photo,video,link_youtube,link_facebook',
            'kategori'  => 'required|in:kegiatan,akademik,prestasi,lainnya',
            'status'    => 'required|in:aktif,draf',
            'urutan'    => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:9999999',
        ];

        if (in_array($tipe, ['photo', 'video'])) {
            if ($tipe === 'photo') {
                $rules['file_path'] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif';
            } else {
                $rules['file_path'] = 'nullable|mimes:mp4,mov,avi,mkv,webm';
            }
        } else {
            $rules['link_url'] = 'required|url';
        }

        $validated = $request->validate($rules);

        $data = [
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tipe'      => $tipe,
            'kategori'  => $validated['kategori'],
            'status'    => $validated['status'],
            'urutan'    => $validated['urutan'] ?? $galeri->urutan,
        ];

        // Tipe berubah – hapus file lama
        if ($tipe !== $galeri->tipe) {
            if ($galeri->file_path) Storage::disk('public')->delete($galeri->file_path);
            $data['file_path'] = null;
            $data['link_url']  = null;
        }

        if (in_array($tipe, ['photo', 'video']) && $request->hasFile('file_path')) {
            if ($galeri->file_path) Storage::disk('public')->delete($galeri->file_path);
            $folder = $tipe === 'photo' ? 'galeri/photos' : 'galeri/videos';
            $data['file_path'] = $request->file('file_path')->store($folder, 'public');
        }

        if (!in_array($tipe, ['photo', 'video'])) {
            $data['link_url'] = $validated['link_url'];
        }

        if ($request->hasFile('thumbnail')) {
            if ($galeri->thumbnail) Storage::disk('public')->delete($galeri->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('galeri/thumbnails', 'public');
        }

        $galeri->update($data);

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'galeri'])
            ->with('success', 'Media galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->file_path) Storage::disk('public')->delete($galeri->file_path);
        if ($galeri->thumbnail) Storage::disk('public')->delete($galeri->thumbnail);
        $galeri->delete();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'galeri'])
            ->with('success', 'Media galeri berhasil dihapus.');
    }

    public function toggleStatus(Galeri $galeri)
    {
        $galeri->update(['status' => $galeri->status === 'aktif' ? 'draf' : 'aktif']);
        return back()->with('success', 'Status media diperbarui.');
    }
}