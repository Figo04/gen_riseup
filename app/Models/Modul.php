<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';

    protected $fillable = ['nama', 'slug', 'urutan', 'cover_image'];

    public function subBagian()
    {
        return $this->hasMany(SubBagian::class)->orderBy('urutan');
    }

    /** Tagline per modul dari mockup — hardcoded, tidak ada di DB. */
    public function getSubtitleAttribute(): string
    {
        return [
            'kejar-mimpi' => 'Kenali mimpimu, susun langkah kecilnya',
            'investasi-gizi' => 'Makanmu hari ini, tenagamu nanti',
            'berpikir-kritis' => 'Saring dulu, baru percaya',
            'kenali-tubuhmu' => 'Tubuhmu berubah, kamu berhak paham',
        ][$this->slug] ?? '';
    }
}
