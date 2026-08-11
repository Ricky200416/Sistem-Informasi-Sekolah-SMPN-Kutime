<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar dari user di website resmi
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'nullable|string|max:100',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'komentar' => 'required|string|max:1000',
        ], [
            'komentar.required' => 'Masukan/komentar tidak boleh kosong.',
            'foto.image'        => 'File foto harus berupa gambar.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('comments', 'public');
        }

        Comment::create([
            'nama'      => $request->filled('nama') ? $request->nama : null,
            'foto_path' => $fotoPath,
            'komentar'  => $request->komentar,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success_comment', 'Terima kasih! Masukan Anda telah berhasil terposting.');
    }

    /**
     * Menampilkan daftar komentar di Dashboard Admin
     */
    public function indexAdmin()
    {
        $comments = Comment::latest()->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Menghapus komentar dari Dashboard Admin
     */
    public function destroyAdmin($id)
    {
        $comment = Comment::findOrFail($id);
        
        if ($comment->foto_path && Storage::disk('public')->exists($comment->foto_path)) {
            Storage::disk('public')->delete($comment->foto_path);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Mengubah status tampil/sembunyi komentar oleh Admin
     */
    public function toggleStatusAdmin($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->is_active = !$comment->is_active;
        $comment->save();

        return redirect()->back()->with('success', 'Status komentar berhasil diperbarui.');
    }
}