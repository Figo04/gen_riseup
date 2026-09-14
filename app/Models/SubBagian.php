<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBagian extends Model
{
    protected $table = 'sub_bagian';

    protected $fillable = ['modul_id', 'judul', 'konten_view', 'urutan', 'video_youtube_id'];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    /**
     * Nama Blade view materi, divalidasi sebelum boleh di-include.
     *
     * Nilainya datang dari kolom DB, jadi tidak dipercaya mentah-mentah:
     * meng-include nama view dari database berarti siapa pun yang bisa menulis
     * kolom ini bisa me-render view mana pun di aplikasi. Formatnya dikunci ke
     * pola yang dihasilkan ModulSeeder — modul.<slug>.<slug> — sehingga titik
     * ekstra atau '..' langsung ditolak.
     */
    public function viewKonten(): string
    {
        abort_unless((bool) preg_match('/^modul\.[a-z0-9-]+\.[a-z0-9-]+$/', (string) $this->konten_view), 404);

        return $this->konten_view;
    }

    /**
     * Lembar refleksi cuma ada satu per modul, menempel di sub-bagian terakhir.
     */
    public function adalahTerakhir(): bool
    {
        return $this->urutan === static::where('modul_id', $this->modul_id)->max('urutan');
    }
}
