<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderHaid extends Model
{
    protected $table = 'kalender_haid';

    protected $fillable = ['user_id', 'tanggal_mulai', 'tanggal_selesai', 'catatan'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
