<?php

namespace Tests\Feature;

use App\Models\HasilKuesioner;
use App\Models\Modul;
use App\Models\ProgressModul;
use App\Models\Refleksi;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefleksiTest extends TestCase
{
    use RefreshDatabase;

    /** Jawaban valid untuk lembar refleksi modul kejar-mimpi (3 pertanyaan + tabel 4 kolom). */
    private const JAWABAN = [
        'pertanyaan' => [
            'Jawaban pertama.',
            'Tekanan dari media sosial.',
            'Ibu dan sahabatku.',
        ],
        'tabel' => [
            ['Kuliah kedokteran', 'Perbaiki nilai Biologi', 'Buku latihan soal', 'Guru les'],
            ['', '', '', ''],
            ['', '', '', ''],
        ],
    ];

    private function siswaSiapMateri(): array
    {
        $user = User::factory()->create();
        HasilKuesioner::forceCreate(['user_id' => $user->id, 'tipe_sesi' => 'pre', 'skor_pengetahuan' => 80, 'kategori_pengetahuan' => 'Baik', 'skor_sikap' => 50, 'submitted_at' => now()]);
        $modul = Modul::create(['nama' => 'Kejar Mimpi', 'slug' => 'kejar-mimpi', 'urutan' => 1]);
        $sub = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 1', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 1]);

        return [$user, $modul, $sub];
    }

    private function tandaiSelesai(User $user, SubBagian $sub): void
    {
        ProgressModul::forceCreate(['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
    }

    public function test_refleksi_redirect_ke_materi_kalau_materi_belum_selesai(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();

        $response = $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $response->assertRedirect(route('modul.sub-bagian.show', [$modul, $sub]));
    }

    public function test_tab_refleksi_terkunci_di_halaman_materi_sampai_materi_ditandai_selesai(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modul, $sub]))
            ->assertDontSee(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $this->tandaiSelesai($user, $sub);

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modul, $sub]))
            ->assertSee(route('modul.sub-bagian.refleksi', [$modul, $sub]));
    }

    public function test_sub_bagian_bukan_terakhir_tidak_punya_lembar_refleksi(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        $terakhir = SubBagian::create(['modul_id' => $modul->id, 'judul' => 'Bab 2', 'konten_view' => 'modul.kejar-mimpi.ingat-lagi-mimpimu', 'urutan' => 2]);
        $this->tandaiSelesai($user, $sub);

        $this->actingAs($user)->get(route('modul.sub-bagian.show', [$modul, $sub]))
            ->assertDontSee(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $sub]))
            ->assertNotFound();

        $this->tandaiSelesai($user, $terakhir);
        $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $terakhir]))
            ->assertOk();
    }

    public function test_setiap_modul_punya_pertanyaan_refleksi_yang_berbeda(): void
    {
        $semua = collect(config('refleksi'))->pluck('pertanyaan');

        $this->assertCount(4, $semua);
        $this->assertSame($semua->flatten()->count(), $semua->flatten()->unique()->count());
    }

    public function test_submit_refleksi_sukses_setelah_materi_selesai(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        $this->tandaiSelesai($user, $sub);

        $response = $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), self::JAWABAN);

        $response->assertRedirect(route('modul.sub-bagian.refleksi', [$modul, $sub]));
        $this->assertDatabaseHas('refleksi', ['user_id' => $user->id, 'sub_bagian_id' => $sub->id, 'is_locked' => true]);

        $jawaban = Refleksi::first()->jawaban;
        $this->assertSame(self::JAWABAN['pertanyaan'], $jawaban['pertanyaan']);
        // Dua baris tabel yang dikosongkan siswa dibuang, tinggal satu.
        $this->assertSame([self::JAWABAN['tabel'][0]], $jawaban['tabel']);
    }

    public function test_halaman_refleksi_tampil_read_only_setelah_submit(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        $this->tandaiSelesai($user, $sub);
        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), self::JAWABAN);

        $response = $this->actingAs($user)->get(route('modul.sub-bagian.refleksi', [$modul, $sub]));

        $response->assertSee('Jawaban pertama.');
        $response->assertSee('Perbaiki nilai Biologi');
        $response->assertSee('disabled', false);
        $response->assertDontSee('Kirim Refleksi');
    }

    public function test_submit_kedua_kali_ditolak_backend_tidak_mengubah_jawaban(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        $this->tandaiSelesai($user, $sub);
        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), self::JAWABAN);

        $ubah = self::JAWABAN;
        $ubah['pertanyaan'][0] = 'Coba ubah jawaban.';
        $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), $ubah);

        $this->assertDatabaseCount('refleksi', 1);
        $this->assertSame('Jawaban pertama.', Refleksi::first()->jawaban['pertanyaan'][0]);
    }

    public function test_submit_refleksi_kosong_ditolak_validasi(): void
    {
        [$user, $modul, $sub] = $this->siswaSiapMateri();
        $this->tandaiSelesai($user, $sub);

        $response = $this->actingAs($user)->post(route('modul.sub-bagian.refleksi.store', [$modul, $sub]), [
            'pertanyaan' => ['', '', ''],
        ]);

        $response->assertSessionHasErrors('pertanyaan.0');
        $this->assertDatabaseCount('refleksi', 0);
    }
}
