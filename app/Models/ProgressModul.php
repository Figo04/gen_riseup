<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressModul extends Model
{
    protected $table = 'progress_modul';

    // Lihat catatan di Refleksi: kepemilikan hanya lewat relasi.
    protected $fillable = ['sub_bagian_id', 'materi_selesai', 'materi_selesai_at'];

    protected function casts(): array
    {
        return [
            'materi_selesai' => 'boolean',
            'materi_selesai_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subBagian()
    {
        return $this->belongsTo(SubBagian::class);
    }
}
