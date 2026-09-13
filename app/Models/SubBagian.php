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
     * Lembar refleksi cuma ada satu per modul, menempel di sub-bagian terakhir.
     */
    public function adalahTerakhir(): bool
    {
        return $this->urutan === static::where('modul_id', $this->modul_id)->max('urutan');
    }
}
