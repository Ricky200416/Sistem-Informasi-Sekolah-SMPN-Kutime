<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
            'nama'     => 'nullable|string|max:100',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('foto')) {
            $avatarPath = $request->file('foto')->store('comments', 'public');
        }

        Comment::create([
            'nama'      => $request->nama ?? 'Anonim',
            'komentar'  => $request->komentar,
            'foto_path' => $avatarPath,
            'is_active' => true, // atau false jika butuh moderasi admin terlebih dahulu
        ]);

        return redirect()->back()->with('success_comment', 'Terima kasih! Komentar Anda berhasil dikirim.');
    }
}