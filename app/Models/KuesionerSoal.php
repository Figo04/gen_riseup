<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tipe', 'pertanyaan', 'jawaban_benar', 'reverse_scored', 'urutan', 'is_aktif'])]
class KuesionerSoal extends Model
{
    protected $table = 'kuesioner_soal';

    protected function casts(): array
    {
        return [
            'reverse_scored' => 'boolean',
            'is_aktif' => 'boolean',
        ];
    }
}
