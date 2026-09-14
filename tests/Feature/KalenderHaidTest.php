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
        HasilKuesioner::forceCreate(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);

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

    public function test_grid_menandai_hari_haid_dan_mengikuti_param_bulan(): void
    {
        $user = $this->siswaSudahPretest();
        KalenderHaid::forceCreate(['user_id' => $user->id, 'tanggal_mulai' => '2026-08-04', 'tanggal_selesai' => '2026-08-09']);

        $agustus = $this->actingAs($user)->get(route('kalender-haid.index', ['bulan' => '2026-08']))
            ->assertOk()
            ->assertSee('Agustus 2026');

        // 4-9 Agustus = 6 hari yang ditandai, tidak lebih.
        $this->assertSame(6, substr_count($agustus->getContent(), 'bg-brand-pink font-semibold text-rose-700'));

        // Bulan lain: tidak ada tanda sama sekali.
        $september = $this->actingAs($user)->get(route('kalender-haid.index', ['bulan' => '2026-09']))->assertOk();
        $this->assertSame(0, substr_count($september->getContent(), 'bg-brand-pink font-semibold text-rose-700'));

        // Param ngawur tidak boleh bikin error — jatuh ke bulan berjalan.
        $this->actingAs($user)->get(route('kalender-haid.index', ['bulan' => '2026-13']))
            ->assertOk()
            ->assertSee(now()->format('Y'));

        $this->actingAs($user)->get(route('kalender-haid.index', ['bulan' => 'bukan-tanggal']))
            ->assertOk();
    }

    public function test_siswa_bisa_update_entri_sendiri(): void
    {
        $user = $this->siswaSudahPretest();
        $entri = KalenderHaid::forceCreate(['user_id' => $user->id, 'tanggal_mulai' => '2026-09-01']);

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
        $entri = KalenderHaid::forceCreate(['user_id' => $user->id, 'tanggal_mulai' => '2026-09-01']);

        $this->actingAs($user)->delete(route('kalender-haid.destroy', $entri));

        $this->assertDatabaseMissing('kalender_haid', ['id' => $entri->id]);
    }

    public function test_tidak_bisa_edit_atau_hapus_entri_user_lain(): void
    {
        $pemilik = $this->siswaSudahPretest();
        $penyerang = $this->siswaSudahPretest();
        $entri = KalenderHaid::forceCreate(['user_id' => $pemilik->id, 'tanggal_mulai' => '2026-09-01']);

        $this->actingAs($penyerang)->get(route('kalender-haid.edit', $entri))->assertForbidden();
        $this->actingAs($penyerang)->put(route('kalender-haid.update', $entri), ['tanggal_mulai' => '2026-09-03'])->assertForbidden();
        $this->actingAs($penyerang)->delete(route('kalender-haid.destroy', $entri))->assertForbidden();
        $this->assertSame('2026-09-01', $entri->fresh()->tanggal_mulai->format('Y-m-d'));
    }
}
