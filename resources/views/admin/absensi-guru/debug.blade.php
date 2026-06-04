<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Debug Absensi</title>
<style>
body{font-family:monospace;font-size:13px;padding:20px;background:#f8fafc;}
table{border-collapse:collapse;width:100%;margin-bottom:20px;}
th,td{border:1px solid #e2e8f0;padding:6px 10px;text-align:left;}
th{background:#f1f5f9;}
.ok{color:#16a34a;font-weight:bold;}
.err{color:#dc2626;font-weight:bold;}
.warn{color:#d97706;font-weight:bold;}
h2{margin-top:30px;color:#1e293b;}
pre{background:#1e293b;color:#e2e8f0;padding:15px;border-radius:8px;overflow-x:auto;}
</style>
</head>
<body>

<h1>🔍 Debug Halaman Absensi Guru</h1>
<p style="color:#64748b;">Jalankan halaman ini untuk menemukan root cause masalah. Hapus setelah selesai debug.</p>

<?php

use App\Models\User;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ── 1. CEK TABEL ────────────────────────────────────────
echo '<h2>1. Cek Struktur Tabel</h2>';
echo '<table><tr><th>Tabel</th><th>Ada?</th><th>Kolom Penting</th></tr>';

$tables = ['users', 'gurus', 'absensi_gurus'];
foreach ($tables as $tbl) {
    $ada = Schema::hasTable($tbl);
    echo '<tr>';
    echo '<td>' . $tbl . '</td>';
    echo '<td class="' . ($ada ? 'ok' : 'err') . '">' . ($ada ? '✓ Ada' : '✗ Tidak Ada') . '</td>';
    if ($ada) {
        $cols = Schema::getColumnListing($tbl);
        echo '<td>' . implode(', ', $cols) . '</td>';
    } else {
        echo '<td class="err">Tabel tidak ditemukan!</td>';
    }
    echo '</tr>';
}
echo '</table>';

// ── 2. CEK KOLOM role DI users ─────────────────────────
echo '<h2>2. Cek Kolom <code>role</code> di Tabel users</h2>';
$hasRole = Schema::hasColumn('users', 'role');
echo '<p class="' . ($hasRole ? 'ok' : 'err') . '">';
echo $hasRole
    ? '✓ Kolom <code>role</code> ada di tabel users'
    : '✗ Kolom <code>role</code> TIDAK ADA di tabel users — ini penyebab utama!';
echo '</p>';

if ($hasRole) {
    // Lihat nilai unik di kolom role
    $roles = DB::table('users')->select('role')->distinct()->get();
    echo '<p>Nilai unik di kolom role: ';
    foreach ($roles as $r) {
        echo '<code>' . ($r->role ?? 'NULL') . '</code> ';
    }
    echo '</p>';
}

// ── 3. CEK USER DENGAN ROLE guru ───────────────────────
echo '<h2>3. User dengan role = \'guru\'</h2>';
try {
    $guruUsers = User::where('role', 'guru')->get();
    echo '<p>Jumlah: <strong>' . $guruUsers->count() . '</strong></p>';

    if ($guruUsers->count() === 0) {
        echo '<p class="err">✗ Tidak ada user dengan role=\'guru\'!</p>';
        echo '<p>Semua user yang ada:</p>';
        $allUsers = User::select('id','name','email','role')->limit(10)->get();
        echo '<table><tr><th>id</th><th>name</th><th>email</th><th>role</th></tr>';
        foreach ($allUsers as $u) {
            echo '<tr><td>'.$u->id.'</td><td>'.$u->name.'</td><td>'.$u->email.'</td><td><strong>'.$u->role.'</strong></td></tr>';
        }
        echo '</table>';
    } else {
        echo '<table><tr><th>users.id</th><th>name</th><th>email</th><th>role</th><th>guru_id (gurus.id)</th><th>guru.nama</th><th>guru.nip</th></tr>';
        foreach ($guruUsers as $u) {
            $g = $u->guru;
            echo '<tr>';
            echo '<td>'.$u->id.'</td>';
            echo '<td>'.$u->name.'</td>';
            echo '<td>'.$u->email.'</td>';
            echo '<td class="ok">'.$u->role.'</td>';
            echo '<td class="'.($g ? 'ok':'err').'">' . ($g ? $g->id : '✗ NULL - profil belum ada') . '</td>';
            echo '<td class="'.($g && $g->nama ? 'ok':'warn').'">' . ($g ? ($g->nama ?: '⚠ kosong (akan pakai user.name)') : '—') . '</td>';
            echo '<td>' . ($g ? ($g->nip ?: '—') : '—') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (\Exception $e) {
    echo '<p class="err">ERROR: ' . $e->getMessage() . '</p>';
}

// ── 4. CEK RELASI User->guru ───────────────────────────
echo '<h2>4. Cek Relasi User hasOne Guru</h2>';
try {
    $u = User::where('role', 'guru')->first();
    if ($u) {
        echo '<p>Test pada user: <strong>'.$u->name.'</strong> (id='.$u->id.')</p>';
        $g = $u->guru;
        if ($g) {
            echo '<p class="ok">✓ Relasi ->guru bekerja. gurus.id = '.$g->id.'</p>';
        } else {
            echo '<p class="warn">⚠ Relasi ->guru = NULL. Artinya tidak ada record di tabel gurus dengan user_id='.$u->id.'</p>';
            // Cek langsung di DB
            $rawGuru = DB::table('gurus')->where('user_id', $u->id)->first();
            if ($rawGuru) {
                echo '<p class="warn">⚠ Record di tabel gurus ada (id='.$rawGuru->id.') tapi relasi Eloquent tidak load. Cek method guru() di model User.</p>';
            } else {
                echo '<p class="err">✗ Tidak ada record di tabel gurus dengan user_id='.$u->id.'</p>';
            }
        }
    } else {
        echo '<p class="err">Tidak ada user role=guru untuk ditest.</p>';
    }
} catch (\Exception $e) {
    echo '<p class="err">ERROR: ' . $e->getMessage() . '</p>';
}

// ── 5. CEK ISI TABEL gurus ─────────────────────────────
echo '<h2>5. Isi Tabel gurus (10 data pertama)</h2>';
try {
    $gurus = DB::table('gurus')->limit(10)->get();
    if ($gurus->count() === 0) {
        echo '<p class="err">✗ Tabel gurus KOSONG!</p>';
    } else {
        echo '<table><tr>';
        foreach (array_keys((array)$gurus->first()) as $col) {
            echo '<th>'.$col.'</th>';
        }
        echo '</tr>';
        foreach ($gurus as $row) {
            echo '<tr>';
            foreach ((array)$row as $val) {
                echo '<td>'.($val ?? '<em style="color:#94a3b8">NULL</em>').'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (\Exception $e) {
    echo '<p class="err">ERROR: ' . $e->getMessage() . '</p>';
}

// ── 6. CEK Model User - method guru() ──────────────────
echo '<h2>6. Cek Method guru() di Model User</h2>';
try {
    $u = new User();
    if (method_exists($u, 'guru')) {
        echo '<p class="ok">✓ Method guru() ada di model User</p>';
        // Cek return type
        $rel = $u->guru();
        echo '<p>Tipe relasi: <code>'.get_class($rel).'</code></p>';
    } else {
        echo '<p class="err">✗ Method guru() TIDAK ADA di model User! Tambahkan relasi hasOne ke model User.</p>';
    }
} catch (\Exception $e) {
    echo '<p class="warn">Tidak bisa cek: ' . $e->getMessage() . '</p>';
}

// ── 7. SIMULASI QUERY CONTROLLER ───────────────────────
echo '<h2>7. Simulasi Query AbsensiGuruController</h2>';
try {
    $result = User::where('role', 'guru')->with(['guru'])->orderBy('name')->get();
    echo '<p>Hasil query: <strong>'.$result->count().' user</strong></p>';
    if ($result->count() > 0) {
        echo '<table><tr><th>users.id</th><th>user.name</th><th>guru null?</th><th>Nama tampil di absensi</th></tr>';
        foreach ($result as $u) {
            $g = $u->guru;
            $namaTampil = ($g && $g->nama) ? $g->nama : $u->name;
            echo '<tr>';
            echo '<td>'.$u->id.'</td>';
            echo '<td>'.$u->name.'</td>';
            echo '<td class="'.($g?'ok':'warn').'">'.($g ? '✓ Ada (id='.$g->id.')' : '⚠ NULL').'</td>';
            echo '<td class="ok"><strong>'.$namaTampil.'</strong></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (\Exception $e) {
    echo '<p class="err">ERROR saat query: ' . $e->getMessage() . '</p>';
}

// ── 8. CEK VARIABLE $daftarGuru DARI VIEW ──────────────
echo '<h2>8. Variabel yang Dikirim ke View</h2>';
echo '<p>Cek apakah view menerima <code>$daftarGuru</code>:</p>';
echo '<pre>';
// Simulasi compact
$bulan = now()->month;
$tahun = now()->year;
$daftarGuru = User::where('role', 'guru')->with(['guru'])->orderBy('name')->get();
echo 'count($daftarGuru) = ' . $daftarGuru->count() . PHP_EOL;
echo PHP_EOL;
foreach ($daftarGuru as $user) {
    $guru = $user->guru;
    echo 'user.id='.$user->id.' | user.name="'.$user->name.'" | guru='.($guru ? 'id='.$guru->id.', nama="'.($guru->nama??'NULL').'"' : 'NULL').PHP_EOL;
}
echo '</pre>';

?>

<h2>📋 Kesimpulan & Solusi</h2>
<div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:15px;margin-top:10px;">
<p><strong>Berdasarkan hasil di atas, cek:</strong></p>
<ol>
<li>Jika "Kolom role TIDAK ADA" → jalankan migration atau tambah kolom role ke tabel users</li>
<li>Jika "Tidak ada user role=guru" → cek nilai kolom role, mungkin berbeda (misal: 'Guru' bukan 'guru')</li>
<li>Jika "Tabel gurus KOSONG" → profil guru belum dibuat, cek UserController::store()</li>
<li>Jika "Method guru() TIDAK ADA" → tambah relasi ke model User</li>
<li>Jika semua OK tapi view kosong → masalah di nama variabel (lihat solusi di bawah)</li>
</ol>
</div>

</body>
</html>