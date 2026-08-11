<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'foto_path',
        'komentar',
        'is_active',
    ];

    /**
     * Accessor untuk URL foto profil atau default avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->foto_path) {
            return asset('storage/' . $this->foto_path);
        }
        
        // Avatar default SVG/Placeholder UI
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama ?? 'Unknown') . '&background=0e2356&color=fff';
    }
}