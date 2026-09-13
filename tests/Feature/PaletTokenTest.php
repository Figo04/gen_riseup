<?php

namespace Tests\Feature;

use Tests\TestCase;

class PaletTokenTest extends TestCase
{
    /**
     * Tailwind diam saja kalau sebuah class tidak ada di palet — tidak ada CSS
     * yang di-generate dan elemennya render tanpa warna, tanpa error apa pun.
     * Tombol "Isi Tracker Gizi" sempat tak terlihat gara-gara ini.
     *
     * Token yang dibuang saat restyle (lihat DESIGN.md) tidak boleh muncul lagi.
     */
    public function test_token_warna_yang_sudah_dihapus_tidak_dipakai_lagi_di_blade(): void
    {
        $dihapus = ['brand-mint', 'brand-peach'];
        $temuan = [];

        foreach (glob(resource_path('views').'/{,*/,*/*/}*.blade.php', GLOB_BRACE) as $file) {
            $isi = file_get_contents($file);

            foreach ($dihapus as $token) {
                // Cocokkan token utuh: "brand-mint" kena, "brand-mint-soft" tidak.
                if (preg_match('/\b'.preg_quote($token, '/').'(?![\w-])/', $isi)) {
                    $temuan[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file).' → '.$token;
                }
            }
        }

        $this->assertSame([], $temuan, "Token warna yang sudah dihapus masih dipakai:\n".implode("\n", $temuan));
    }
}
