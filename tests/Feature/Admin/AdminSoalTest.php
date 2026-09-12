<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\HasilKuesionerDetail;
use App\Models\KuesionerSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_soal_pengetahuan_with_auto_urutan(): void
    {
        $admin = Admin::factory()->create();
        KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Soal lama', 'jawaban_benar' => 'B', 'urutan' => 1]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.soal.store'), [
            'tipe' => 'pengetahuan',
            'pertanyaan' => 'Soal baru',
            'jawaban_benar' => 'S',
        ]);

        $response->assertRedirect(route('admin.soal.index'));
        $this->assertDatabaseHas('kuesioner_soal', [
            'pertanyaan' => 'Soal baru',
            'jawaban_benar' => 'S',
            'urutan' => 2,
            'is_aktif' => 1,
        ]);
    }

    public function test_admin_can_create_soal_sikap_with_reverse_scored(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.soal.store'), [
            'tipe' => 'sikap',
            'pertanyaan' => 'Soal sikap baru',
            'reverse_scored' => '1',
        ]);

        $response->assertRedirect(route('admin.soal.index'));
        $this->assertDatabaseHas('kuesioner_soal', [
            'pertanyaan' => 'Soal sikap baru',
            'jawaban_benar' => null,
            'reverse_scored' => 1,
            'urutan' => 1,
        ]);
    }

    public function test_admin_can_update_soal(): void
    {
        $admin = Admin::factory()->create();
        $soal = KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Lama', 'jawaban_benar' => 'B', 'urutan' => 1]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.soal.update', $soal), [
            'pertanyaan' => 'Sudah diedit',
            'jawaban_benar' => 'S',
        ]);

        $response->assertRedirect(route('admin.soal.index'));
        $this->assertDatabaseHas('kuesioner_soal', [
            'id' => $soal->id,
            'pertanyaan' => 'Sudah diedit',
            'jawaban_benar' => 'S',
            'tipe' => 'pengetahuan',
        ]);
    }

    public function test_editing_jawaban_benar_does_not_recompute_historical_score(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $soal = KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Soal', 'jawaban_benar' => 'B', 'urutan' => 1]);

        $hasil = HasilKuesioner::create([
            'user_id' => $user->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 100, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 0, 'submitted_at' => now(),
        ]);
        HasilKuesionerDetail::create(['hasil_kuesioner_id' => $hasil->id, 'soal_id' => $soal->id, 'jawaban_siswa' => 'B']);

        $this->actingAs($admin, 'admin')->put(route('admin.soal.update', $soal), [
            'pertanyaan' => 'Soal',
            'jawaban_benar' => 'S',
        ]);

        $hasil->refresh();
        $this->assertEquals(100, $hasil->skor_pengetahuan);
        $this->assertSame('Baik', $hasil->kategori_pengetahuan);
    }

    public function test_delete_soft_deletes_via_is_aktif_and_keeps_historical_answer_intact(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $soal = KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Soal', 'jawaban_benar' => 'B', 'urutan' => 1]);

        $hasil = HasilKuesioner::create([
            'user_id' => $user->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 100, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 0, 'submitted_at' => now(),
        ]);
        $detail = HasilKuesionerDetail::create(['hasil_kuesioner_id' => $hasil->id, 'soal_id' => $soal->id, 'jawaban_siswa' => 'B']);

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.soal.destroy', $soal));

        $response->assertRedirect(route('admin.soal.index'));
        $this->assertDatabaseHas('kuesioner_soal', ['id' => $soal->id, 'is_aktif' => 0]);
        $this->assertDatabaseHas('hasil_kuesioner_detail', ['id' => $detail->id, 'soal_id' => $soal->id]);
    }

    public function test_deactivated_soal_is_excluded_from_index_and_pretest(): void
    {
        $admin = Admin::factory()->create();
        $aktif = KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Aktif', 'jawaban_benar' => 'B', 'urutan' => 1, 'is_aktif' => true]);
        $nonaktif = KuesionerSoal::create(['tipe' => 'pengetahuan', 'pertanyaan' => 'Nonaktif', 'jawaban_benar' => 'B', 'urutan' => 2, 'is_aktif' => false]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.soal.index'));

        $response->assertSee('Aktif');
        $response->assertDontSee('Nonaktif');

        $user = User::factory()->create();
        $pretest = $this->actingAs($user)->get(route('kuesioner.pretest.create'));
        $pretest->assertDontSee('Nonaktif');
    }
}
