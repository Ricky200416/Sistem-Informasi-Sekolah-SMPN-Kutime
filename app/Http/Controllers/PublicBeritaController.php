<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;

class PublicBeritaController extends Controller
{
    public function index(Request $request)
    {
        // Pengumuman penting (maks. 3, tampil di atas)
        $pengumuman = Berita::query()
            ->when(method_exists(Berita::class, 'scopeAktif'), fn($q) => $q->aktif())
            ->when(method_exists(Berita::class, 'scopePengumuman'), fn($q) => $q->pengumuman())
            ->when(method_exists(Berita::class, 'scopePenting'), fn($q) => $q->penting())
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();

        // Query berita dengan filter
        $query = Berita::query()
            ->when(method_exists(Berita::class, 'scopeAktif'), fn($q) => $q->aktif())
            ->orderBy('tanggal_publish', 'desc');

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Pencarian judul
        if ($request->filled('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%');
        }

        $beritas = $query->paginate(8)->withQueryString();

        return view('website.berita', compact('pengumuman', 'beritas'));
    }

    public function show(string $slug)
    {
        $query = Berita::query();

        if (method_exists(Berita::class, 'scopeAktif')) {
            $query->aktif();
        }

        $berita = $query->where('slug', $slug)->firstOrFail();

        // Berita terkait – kategori sama
        $terkaitQuery = Berita::query();

        if (method_exists(Berita::class, 'scopeAktif')) {
            $terkaitQuery->aktif();
        }

        $terkait = $terkaitQuery
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderByRaw("
                CASE 
                    WHEN media_tipe != 'none' AND media_tipe IS NOT NULL THEN 0 
                    ELSE 1 
                END
            ")
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();

        return view('website.berita-detail', compact('berita', 'terkait'));
    }
}