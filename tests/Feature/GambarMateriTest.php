<?php

namespace Tests\Feature;

use Tests\TestCase;

class GambarMateriTest extends TestCase
{
    public function test_semua_gambar_yang_dirujuk_view_modul_ada_filenya(): void
    {
        $hilang = [];

        foreach (glob(resource_path('views/modul/*/*.blade.php')) as $view) {
            preg_match_all("#asset\('(images/[^']+)'\)#", file_get_contents($view), $cocok);

            foreach ($cocok[1] as $path) {
                if (! file_exists(public_path($path))) {
                    $hilang[] = basename($view).' → '.$path;
                }
            }
        }

        $this->assertSame([], $hilang, "Gambar dirujuk tapi filenya tidak ada:\n".implode("\n", $hilang));
    }
}
