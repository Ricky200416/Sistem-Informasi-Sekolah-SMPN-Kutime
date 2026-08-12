<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class TenagaPendidikController extends Controller
{
    public function index()
    {
        $guruList = Guru::with('user')
            ->orderBy('urutan_tampil')
            ->orderBy('nama')
            ->get();

        return view('admin.tenaga-pendidik.index', compact('guruList'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'jabatan'        => ['nullable', 'string', 'max:100'],
            'mata_pelajaran' => ['nullable', 'string', 'max:150'],
            'no_hp'          => ['nullable', 'string', 'max:30'],
            'tampil_website' => ['nullable', 'boolean'],
        ]);

        $validated['tampil_website'] = $request->boolean('tampil_website');

        $guru->update($validated);

        return back()->with('success', 'Data tenaga pendidik "'.($guru->nama ?? $guru->user->name).'" berhasil diperbarui.');
    }

    public function toggle(Guru $guru)
    {
        $guru->update(['tampil_website' => ! $guru->tampil_website]);

        return back()->with('success', 'Status tampilan website berhasil diubah.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:gurus,id'],
        ]);

        foreach ($validated['order'] as $index => $guruId) {
            Guru::where('id', $guruId)->update(['urutan_tampil' => $index]);
        }

        return response()->json(['success' => true]);
    }
}