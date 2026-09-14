<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\Refleksi;
use App\Models\SubBagian;
use App\Models\TrackerGizi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminExportTest extends TestCase
{
    use RefreshDatabase;

    private function csv(?string $format = null): string
    {
        $admin = Admin::factory()->create();

        return $this->actingAs($admin, 'admin')
            ->get(route('admin.export', $format ? ['format' => $format] : []))
            ->streamedContent();
    }

    public function test_guest_cannot_export(): void
    {
        $this->get(route('admin.export'))->assertRedirect(route('admin.login'));
    }

    public function test_student_cannot_export(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.export'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_export_puts_pre_and_post_scores_on_one_row(): void
    {
        $user = User::factory()->create(['name' => 'Siti', 'email' => 'siti@example.com']);
        HasilKuesioner::forceCreate([
            'user_id' => $user->id, 'tipe_sesi' => 'pre',
            'skor_pengetahuan' => 50, 'kategori_pengetahuan' => 'Kurang', 'skor_sikap' => 55,
            'submitted_at' => now(),
        ]);
        HasilKuesioner::forceCreate([
            'user_id' => $user->id, 'tipe_sesi' => 'post',
            'skor_pengetahuan' => 90, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 72,
            'submitted_at' => now(),
        ]);

        $csv = $this->csv();
        $rows = array_values(array_filter(explode("\n", trim($csv))));

        $this->assertCount(2, $rows, 'header + 1 baris siswa');
        $this->assertStringContainsString('siti@example.com', $rows[1]);
        // pre (Kurang) dan post (Baik) sejajar di baris yang sama.
        $this->assertStringContainsString('Kurang', $rows[1]);
        $this->assertStringContainsString('Baik', $rows[1]);
    }

    public function test_student_without_submission_still_exported_with_empty_scores(): void
    {
        User::factory()->create(['name' => 'Budi', 'email' => 'budi@example.com']);

        $rows = array_values(array_filter(explode("\n", trim($this->csv()))));

        $this->assertCount(2, $rows);
        $this->assertStringContainsString('budi@example.com', $rows[1]);
        $this->assertStringEndsWith(',,,,,,,,', trim($rows[1]), '8 kolom skor pre+post kosong');
    }

    public function test_reflection_and_tracker_data_are_never_exported(): void
    {
        $user = User::factory()->create();
        $subBagian = SubBagian::create([
            'modul_id' => Modul::create(['nama' => 'Investasi Gizi', 'slug' => 'investasi-gizi', 'urutan' => 1])->id,
            'judul' => 'Bab 1', 'konten_view' => 'modul.investasi-gizi.pendahuluan', 'urutan' => 1,
        ]);
        Refleksi::forceCreate(['user_id' => $user->id, 'sub_bagian_id' => $subBagian->id, 'jawaban' => 'RAHASIA_REFLEKSI_XYZ']);
        TrackerGizi::forceCreate(['user_id' => $user->id, 'data' => ['senin' => ['sayur_buah' => true]]]);

        $csv = $this->csv();

        $this->assertStringNotContainsString('RAHASIA_REFLEKSI_XYZ', $csv);
        $this->assertStringNotContainsString('sayur_buah', $csv);
    }

    public function test_excel_format_uses_bom_and_semicolon(): void
    {
        User::factory()->create(['name' => 'Siti']);

        $csv = $this->csv('excel');

        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Nama;Email;', $csv);
    }
}
