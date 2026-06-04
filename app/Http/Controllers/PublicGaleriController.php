<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;

class PublicGaleriController extends Controller
{
    // ── Halaman daftar galeri ─────────────────────────────────────
    public function index(Request $request)
    {
        $query = Galeri::aktif()->terurut();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%');
        }

        $galeris         = $query->paginate(12)->withQueryString();
        $kategoriOptions = Galeri::kategoriOptions();

        return view('website.galeri', compact('galeris', 'kategoriOptions'));
    }

    // ── Halaman detail galeri ─────────────────────────────────────
    public function show(Galeri $galeri)
    {
        // Hanya tampilkan yang aktif
        abort_if($galeri->status !== 'aktif', 404);

        // Media lainnya dari kategori yang sama (kecuali item ini)
        $lainnya = Galeri::aktif()
            ->where('kategori', $galeri->kategori)
            ->where('id', '!=', $galeri->id)
            ->terurut()
            ->take(8)
            ->get();

        return view('website.galeri-detail', compact('galeri', 'lainnya'));
    }
}