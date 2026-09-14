<?php

namespace Tests\Feature;

use App\Models\SubBagian;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class SubBagianViewKontenTest extends TestCase
{
    public function test_menerima_pola_yang_dihasilkan_seeder(): void
    {
        $sub = new SubBagian;
        $sub->konten_view = 'modul.kejar-mimpi.ingat-lagi-mimpimu';

        $this->assertSame('modul.kejar-mimpi.ingat-lagi-mimpimu', $sub->viewKonten());
    }

    public function test_menolak_nama_view_di_luar_pola_seeder(): void
    {
        $ditolak = [
            'auth.login',                    // view lain dalam aplikasi
            'modul..auth.login',             // titik ganda
            '../../.env',                    // path traversal
            'modul.kejar-mimpi',             // kurang satu segmen
            'modul.a.b.c',                   // kelebihan segmen
            'modul.Kejar.Mimpi',             // huruf besar, di luar pola slug
            'modul.kejar-mimpi.a b',         // spasi
            '',
        ];

        foreach ($ditolak as $nilai) {
            $sub = new SubBagian;
            $sub->konten_view = $nilai;

            try {
                $sub->viewKonten();
                $this->fail("konten_view '$nilai' seharusnya ditolak, tapi diterima.");
            } catch (NotFoundHttpException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
