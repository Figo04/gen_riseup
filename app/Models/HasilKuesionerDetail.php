<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['hasil_kuesioner_id', 'soal_id', 'jawaban_siswa'])]
class HasilKuesionerDetail extends Model
{
    protected $table = 'hasil_kuesioner_detail';

    public function hasilKuesioner(): BelongsTo
    {
        return $this->belongsTo(HasilKuesioner::class);
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(KuesionerSoal::class, 'soal_id');
    }
}
