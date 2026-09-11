<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\KalenderHaid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalenderHaidTest extends TestCase
{
    use RefreshDatabase;

    private function siswaSudahPretest(): User
    {
        $user = User::factory()->create();
        HasilKuesioner::create(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);

        return $user;
    }

    public function test_tambah_entri_sukses_dan_tampil_di_riwayat(): void
    {
        $user = $this->siswaSudahPretest();

        $response = $this->actingAs($user)->post(route('kalender-haid.store'), [
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-05',
            'catatan' => 'Kram ringan',
        ]);

        $response->assertRedirect(route('kalender-haid.index'));
        $this->assertDatabaseHas('kalender_haid', ['user_id' => $user->id, 'catatan' => 'Kram ringan']);

        $index = $this->actingAs($user)->get(route('kalender-haid.index'));
        $index->assertSee('Kram ringan');
    }

    public function test_siswa_bisa_update_entri_sendiri(): void
    {
        $user = $this->siswaSudahPretest();
        $entri = KalenderHaid::create(['user_id' => $user->id, 'tanggal_mulai' => '2026-09-01']);

        $response = $this->actingAs($user)->put(route('kalender-haid.update', $entri), [
            'tanggal_mulai' => '2026-09-02',
            'catatan' => 'Update',
        ]);

        $response->assertRedirect(route('kalender-haid.index'));
        $this->assertDatabaseHas('kalender_haid', ['id' => $entri->id, 'catatan' => 'Update']);
    }

    public function test_siswa_bisa_hapus_entri_sendiri(): void
    {
        $user = $this->siswaSudahPretest();
        $entri = KalenderHaid::create(['user_id' => $user->id, 'tanggal_mulai' => '2026-09-01']);

        $this->actingAs($user)->delete(route('kalender-haid.destroy', $entri));

        $this->assertDatabaseMissing('kalender_haid', ['id' => $entri->id]);
    }

    public function test_tidak_bisa_edit_atau_hapus_entri_user_lain(): void
    {
        $pemilik = $this->siswaSudahPretest();
        $penyerang = $this->siswaSudahPretest();
        $entri = KalenderHaid::create(['user_id' => $pemilik->id, 'tanggal_mulai' => '2026-09-01']);

        $this->actingAs($penyerang)->get(route('kalender-haid.edit', $entri))->assertForbidden();
        $this->actingAs($penyerang)->put(route('kalender-haid.update', $entri), ['tanggal_mulai' => '2026-09-03'])->assertForbidden();
        $this->actingAs($penyerang)->delete(route('kalender-haid.destroy', $entri))->assertForbidden();
        $this->assertSame('2026-09-01', $entri->fresh()->tanggal_mulai->format('Y-m-d'));
    }
}
