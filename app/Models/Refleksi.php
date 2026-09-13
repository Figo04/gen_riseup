<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refleksi extends Model
{
    protected $table = 'refleksi';

    protected $fillable = ['user_id', 'sub_bagian_id', 'jawaban', 'is_locked', 'submitted_at'];

    protected function casts(): array
    {
        return [
            // Satu lembar refleksi berisi beberapa pertanyaan + worksheet,
            // disimpan sebagai JSON di kolom text yang sudah ada.
            'jawaban' => 'array',
            'is_locked' => 'boolean',
            'submitted_at' => 'datetime',
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
