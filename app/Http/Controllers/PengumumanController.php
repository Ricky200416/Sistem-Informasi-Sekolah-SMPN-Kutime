<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════
    //  ADMIN
    // ═══════════════════════════════════════════════════════════════════

    public function adminIndex(Request $request)
    {
        $query = Pengumuman::with('creator')->latest();

        if ($request->filled('filter_audience')) {
            $query->where('target_audience', $request->filter_audience);
        }
        if ($request->filled('filter_status')) {
            $query->where('is_active', $request->filter_status === 'aktif' ? 1 : 0);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $pengumuman = $query->paginate(10)->withQueryString();

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function adminCreate()
    {
        return view('admin.pengumuman.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'target_audience' => 'required|in:semua,guru,siswa',
            'tipe_konten'     => 'required|in:teks,gambar,dokumen,link',
            'isi'             => 'nullable|string',
            'file'            => 'nullable|file|max:20480',
            'link_url'        => 'nullable|url|max:500',
            'link_label'      => 'nullable|string|max:100',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $tipe = $request->tipe_konten;

        $data = $request->only([
            'judul', 'isi', 'target_audience', 'tipe_konten',
            'tanggal_mulai', 'tanggal_selesai',
        ]);

        // Checkbox hanya dikirim jika dicentang
        $data['is_active']         = $request->has('is_active') ? 1 : 0;
        $data['show_di_dashboard'] = $request->has('show_di_dashboard') ? 1 : 0;
        $data['created_by']        = Auth::id();

        // Field link hanya diisi jika tipe = link
        if ($tipe === 'link') {
            $data['link_url']   = $request->link_url;
            $data['link_label'] = $request->link_label;
        } else {
            $data['link_url']   = null;
            $data['link_label'] = null;
        }

        // Upload file hanya jika tipe = gambar atau dokumen
        if (in_array($tipe, ['gambar', 'dokumen']) && $request->hasFile('file')) {
            $file              = $request->file('file');
            $data['file_path'] = $file->store('pengumuman', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_mime'] = $file->getMimeType();
        }

        // Tipe teks/link tidak memiliki file
        if (in_array($tipe, ['teks', 'link'])) {
            $data['file_path'] = null;
            $data['file_name'] = null;
            $data['file_mime'] = null;
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman')
                         ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function adminShow(Pengumuman $pengumuman)
    {
        $pengumuman->load('creator');
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    public function adminEdit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function adminUpdate(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'target_audience' => 'required|in:semua,guru,siswa',
            'tipe_konten'     => 'required|in:teks,gambar,dokumen,link',
            'isi'             => 'nullable|string',
            'file'            => 'nullable|file|max:20480',
            'link_url'        => 'nullable|url|max:500',
            'link_label'      => 'nullable|string|max:100',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $tipe = $request->tipe_konten;

        $data = $request->only([
            'judul', 'isi', 'target_audience', 'tipe_konten',
            'tanggal_mulai', 'tanggal_selesai',
        ]);

        $data['is_active']         = $request->has('is_active') ? 1 : 0;
        $data['show_di_dashboard'] = $request->has('show_di_dashboard') ? 1 : 0;

        // Field link hanya diisi jika tipe = link
        if ($tipe === 'link') {
            $data['link_url']   = $request->link_url;
            $data['link_label'] = $request->link_label;
        } else {
            $data['link_url']   = null;
            $data['link_label'] = null;
        }

        // Jika tipe berubah menjadi teks/link, hapus file lama
        if (in_array($tipe, ['teks', 'link']) && $pengumuman->file_path) {
            Storage::disk('public')->delete($pengumuman->file_path);
            $data['file_path'] = null;
            $data['file_name'] = null;
            $data['file_mime'] = null;
        }

        // Upload file baru jika ada (untuk tipe gambar atau dokumen)
        if (in_array($tipe, ['gambar', 'dokumen']) && $request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($pengumuman->file_path) {
                Storage::disk('public')->delete($pengumuman->file_path);
            }
            $file              = $request->file('file');
            $data['file_path'] = $file->store('pengumuman', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_mime'] = $file->getMimeType();
        }

        // Hapus file secara eksplisit jika checkbox hapus_file dicentang
        if ($request->has('hapus_file') && $pengumuman->file_path) {
            Storage::disk('public')->delete($pengumuman->file_path);
            $data['file_path'] = null;
            $data['file_name'] = null;
            $data['file_mime'] = null;
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman')
                         ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function adminDestroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_path) {
            Storage::disk('public')->delete($pengumuman->file_path);
        }
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman')
                         ->with('success', 'Pengumuman berhasil dihapus!');
    }

    public function adminToggle(Pengumuman $pengumuman)
    {
        $newStatus = $pengumuman->is_active ? 0 : 1;
        $pengumuman->update(['is_active' => $newStatus]);

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $newStatus,
            'message'   => $newStatus ? 'Pengumuman diaktifkan.' : 'Pengumuman dinonaktifkan.',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    //  GURU
    // ═══════════════════════════════════════════════════════════════════

    public function guruIndex()
    {
        $pengumuman = Pengumuman::where('is_active', 1)
            ->whereIn('target_audience', ['guru', 'semua'])
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->latest()
            ->paginate(9);

        return view('guru.pengumuman.index', compact('pengumuman'));
    }

    public function guruShow(Pengumuman $pengumuman)
    {
        abort_if(
            ! in_array($pengumuman->target_audience, ['guru', 'semua']) || ! $pengumuman->is_active,
            404
        );
        return view('guru.pengumuman.show', compact('pengumuman'));
    }

    // ═══════════════════════════════════════════════════════════════════
    //  SISWA
    // ═══════════════════════════════════════════════════════════════════

    public function siswaIndex()
    {
        $pengumuman = Pengumuman::where('is_active', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->latest()
            ->paginate(9);

        return view('siswa.pengumuman.index', compact('pengumuman'));
    }

    public function siswaShow(Pengumuman $pengumuman)
    {
        abort_if(
            ! in_array($pengumuman->target_audience, ['siswa', 'semua']) || ! $pengumuman->is_active,
            404
        );
        return view('siswa.pengumuman.show', compact('pengumuman'));
    }

    // ═══════════════════════════════════════════════════════════════════
    //  WIDGET DASHBOARD (dipanggil dari DashboardController)
    // ═══════════════════════════════════════════════════════════════════

    public static function dashboardWidget(string $role, int $limit = 4)
    {
        return Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', [$role, 'semua'])
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:pengumuman,id']);

        $pengumuman = \App\Models\Pengumuman::whereIn('id', $request->ids)->get();

        foreach ($pengumuman as $item) {
            // Hapus file storage jika ada
            if ($item->file_path) {
                \Storage::disk('public')->delete($item->file_path);
            }
            $item->delete();
        }

        return redirect()->route('admin.pengumuman')
            ->with('success', count($request->ids) . ' pengumuman berhasil dihapus.');
    }
}