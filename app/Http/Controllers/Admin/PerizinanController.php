<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $perizinans = Perizinan::with('guru.user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->get();

        $ringkasan = [
            'total'     => Perizinan::count(),
            'pending'   => Perizinan::where('status', 'pending')->count(),
            'disetujui' => Perizinan::where('status', 'disetujui')->count(),
            'ditolak'   => Perizinan::where('status', 'ditolak')->count(),
        ];

        return view('admin.perizinan.index', compact('perizinans', 'ringkasan', 'status'));
    }

    public function approve(Request $request, Perizinan $perizinan)
    {
        $perizinan->update([
            'status'        => 'disetujui',
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return back()->with('success', 'Izin ' . $perizinan->nama . ' telah disetujui.');
    }

    public function reject(Request $request, Perizinan $perizinan)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $perizinan->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return back()->with('success', 'Izin ' . $perizinan->nama . ' telah ditolak.');
    }
}