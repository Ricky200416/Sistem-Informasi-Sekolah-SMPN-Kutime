<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\StudySubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudySubjectController extends Controller
{
    /** Tambah mata pelajaran baru milik guru yang login */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20',
            'color'       => 'nullable|string|max:7',
            'description' => 'nullable|string|max:200',
        ]);

        // Opsional: kaitkan ke guru yang sedang login
        $data['teacher_id'] = Auth::id(); // sesuaikan dengan kolom di tabel Anda

        StudySubject::create($data);

        return back()->with('success', "Mata pelajaran \"{$data['name']}\" berhasil ditambahkan.");
    }

    /** Edit mata pelajaran milik guru */
    public function update(Request $request, StudySubject $studySubject)
    {
        // Pastikan hanya pemilik yang bisa edit
        if ($studySubject->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20',
            'color'       => 'nullable|string|max:7',
            'description' => 'nullable|string|max:200',
        ]);

        $studySubject->update($data);

        return back()->with('success', "Mata pelajaran \"{$data['name']}\" berhasil diperbarui.");
    }

    /** Hapus mata pelajaran milik guru */
    public function destroy(StudySubject $studySubject)
    {
        if ($studySubject->teacher_id !== Auth::id()) {
            abort(403);
        }

        $nama = $studySubject->name;
        $studySubject->delete();

        return back()->with('success', "Mata pelajaran \"{$nama}\" berhasil dihapus.");
    }
}