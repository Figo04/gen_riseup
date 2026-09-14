<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// user_id sengaja tidak fillable — lihat catatan di Refleksi.
#[Fillable(['tipe_sesi', 'skor_pengetahuan', 'kategori_pengetahuan', 'skor_sikap', 'submitted_at'])]
class HasilKuesioner extends Model
{
    protected $table = 'hasil_kuesioner';

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(HasilKuesionerDetail::class);
    }
}
