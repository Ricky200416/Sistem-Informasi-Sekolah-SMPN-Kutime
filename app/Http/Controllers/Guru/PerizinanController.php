<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        $riwayat = Perizinan::where('guru_id', $guru->id)
            ->orderByDesc('created_at')
            ->get();

        return view('guru.perizinan.index', compact('riwayat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:100',
            'jabatan'      => 'required|in:Guru,Wali Kelas',
            'no_hp'        => 'required|string|max:20',
            'tujuan'       => 'required|string|max:150',
            'alasan'       => 'required|string|max:1000',
            'lama_izin'    => 'required|string|max:50',
            'tanggal_izin' => 'required|date',
        ]);

        $validated['guru_id'] = Auth::user()->guru->id;
        $validated['status']  = 'pending';

        Perizinan::create($validated);

        return redirect()->route('guru.perizinan.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan Kepala Sekolah.');
    }
}