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

    /** Emoji penanda modul (mockup materi-1). */
    public function getIkonAttribute(): string
    {
        return [
            'kejar-mimpi' => '🚀',
            'investasi-gizi' => '🥗',
            'berpikir-kritis' => '🧠',
            'kenali-tubuhmu' => '🌸',
        ][$this->slug] ?? '📘';
    }

    /**
     * Kunci warna aksen per modul. Sengaja bukan class Tailwind: file PHP
     * tidak di-scan oleh Tailwind, jadi class-nya harus ditulis di Blade.
     */
    public function getWarnaAttribute(): string
    {
        return [
            'kejar-mimpi' => 'mint',
            'investasi-gizi' => 'amber',
            'berpikir-kritis' => 'lilac',
            'kenali-tubuhmu' => 'pink',
        ][$this->slug] ?? 'mint';
    }
}
