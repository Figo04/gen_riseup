<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\KuesionerSoal;
use App\Models\Modul;
use App\Models\SubBagian;
use App\Models\User;
use Database\Seeders\KuesionerSoalSeeder;
use Database\Seeders\ModulSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 21 — satu perjalanan utuh di atas data seeder ASLI (22+20 soal, 4 modul),
 * bukan fixture kecil seperti test lain.
 *
 * Bedanya dengan test per-fitur: di sini setiap langkah memakai state yang
 * ditinggalkan langkah sebelumnya, jadi yang diuji adalah SAMBUNGAN antar fase —
 * tempat yang tidak terlihat kalau tiap fitur cuma diuji sendiri-sendiri.
 */
class AlurLengkapTest extends TestCase
{
    use RefreshDatabase;

    private function jawabanLengkap(): array
    {
        return KuesionerSoal::where('is_aktif', true)->get()
            ->mapWithKeys(fn (KuesionerSoal $s) => [
                $s->id => $s->tipe === 'pengetahuan' ? $s->jawaban_benar : 'SS',
            ])->all();
    }

    public function test_perjalanan_siswa_dari_daftar_sampai_posttest(): void
    {
        $this->seed(KuesionerSoalSeeder::class);
        $this->seed(ModulSeeder::class);

        $this->assertSame(22, KuesionerSoal::where('tipe', 'pengetahuan')->count(), 'Seeder soal pengetahuan harus 22 (PRD §3).');
        $this->assertSame(20, KuesionerSoal::where('tipe', 'sikap')->count(), 'Seeder soal sikap harus 20 (PRD §3).');
        $this->assertSame(4, Modul::count(), 'Harus ada 4 modul.');

        // 1. Daftar -> otomatis login
        $this->post(route('register'), [
            'name' => 'Nadia',
            'email' => 'nadia@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'usia' => 15,
            'jenis_kelamin' => 'P',
            'sekolah' => 'SMPN 4 Bandung',
            'kelas' => '9B',
        ])->assertRedirect(route('dashboard'));

        $siswa = User::where('email', 'nadia@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($siswa);

        // 2. Materi terkunci sebelum pre-test
        $this->get(route('modul.index'))->assertRedirect(route('kuesioner.pretest.create'));
        $this->get(route('kalender-haid.index'))->assertRedirect(route('kuesioner.pretest.create'));

        // 3. Pre-test (semua benar + semua SS) -> skor & kategori terisi
        $this->post(route('kuesioner.pretest.store'), ['jawaban' => $this->jawabanLengkap()])
            ->assertRedirect(route('dashboard'));

        $pre = $siswa->hasilKuesioner()->where('tipe_sesi', 'pre')->firstOrFail();
        $this->assertSame('Baik', $pre->kategori_pengetahuan);
        $this->assertEqualsWithDelta(100, (float) $pre->skor_pengetahuan, 0.01);
        $this->assertSame(42, $pre->details()->count(), 'Semua 22+20 jawaban harus tersimpan di hasil_kuesioner_detail.');

        // Pre-test final: submit kedua ditolak dan tidak menimpa yang pertama
        $this->post(route('kuesioner.pretest.store'), ['jawaban' => $this->jawabanLengkap()]);
        $this->assertSame(1, $siswa->hasilKuesioner()->where('tipe_sesi', 'pre')->count());

        // 4. Post-test belum boleh dibuka
        $this->get(route('kuesioner.posttest.create'))->assertRedirect(route('dashboard'));

        // 5. Semua modul & sub-bagian: baca -> tandai selesai -> refleksi
        $this->get(route('modul.index'))->assertOk();

        foreach (Modul::with('subBagian')->orderBy('urutan')->get() as $modul) {
            $this->get(route('modul.show', $modul))->assertOk();

            $terakhir = $modul->subBagian->last();

            foreach ($modul->subBagian as $sub) {
                $this->get(route('modul.sub-bagian.show', [$modul, $sub]))->assertOk();

                // Refleksi hanya ada di sub-bagian terakhir (sisanya 404), dan di
                // sub-bagian itu pun masih terkunci sebelum materi ditandai selesai.
                $this->get(route('modul.sub-bagian.refleksi', [$modul, $sub]))
                    ->when(
                        $sub->is($terakhir),
                        fn ($r) => $r->assertRedirect(route('modul.sub-bagian.show', [$modul, $sub])),
                        fn ($r) => $r->assertNotFound(),
                    );

                $this->post(route('modul.sub-bagian.selesai', [$modul, $sub]))
                    ->assertRedirect(route('modul.show', $modul));
            }

            $this->get(route('modul.sub-bagian.refleksi', [$modul, $terakhir]))->assertOk();
            $this->post(route('modul.sub-bagian.refleksi.store', [$modul, $terakhir]), [
                'pertanyaan' => array_map(
                    fn (string $p) => 'Jawaban untuk: '.$p,
                    config("refleksi.{$modul->slug}.pertanyaan"),
                ),
            ])->assertRedirect(route('modul.sub-bagian.refleksi', [$modul, $terakhir]));
        }

        $this->assertSame(SubBagian::count(), $siswa->progressModul()->where('materi_selesai', true)->count());
        $this->assertSame(Modul::count(), $siswa->refleksi()->count(), 'Satu lembar refleksi per modul.');

        // Refleksi sekali kirim: percobaan kedua tidak menambah baris & tidak menimpa
        $modulPertama = Modul::with('subBagian')->orderBy('urutan')->firstOrFail();
        $subRefleksi = $modulPertama->subBagian->last();
        $pertanyaanPertama = config("refleksi.{$modulPertama->slug}.pertanyaan")[0];

        $this->post(route('modul.sub-bagian.refleksi.store', [$modulPertama, $subRefleksi]), [
            'pertanyaan' => ['Coba ubah', 'Coba ubah', 'Coba ubah'],
        ]);
        $this->assertSame(Modul::count(), $siswa->refleksi()->count());
        $this->assertSame(
            'Jawaban untuk: '.$pertanyaanPertama,
            $siswa->refleksi()->where('sub_bagian_id', $subRefleksi->id)->first()->jawaban['pertanyaan'][0],
        );

        // 6. Tracker gizi — sekali isi
        $this->post(route('tracker-gizi.store'), ['data' => ['Senin' => ['sayur_buah' => '1', 'protein' => '1']]])
            ->assertRedirect(route('tracker-gizi.show'));
        $this->post(route('tracker-gizi.store'), ['data' => ['Selasa' => ['air_putih' => '1']]]);
        $this->assertSame(1, $siswa->trackerGizi()->count());
        $this->assertTrue($siswa->trackerGizi()->first()->data['Senin']['sayur_buah']);

        // 7. Kalender haid — boleh banyak entri, riwayat tersimpan
        foreach ([['2026-07-02', '2026-07-07'], ['2026-08-04', '2026-08-09'], ['2026-09-01', null]] as [$mulai, $selesai]) {
            $this->post(route('kalender-haid.store'), [
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $selesai,
                'catatan' => 'Kram ringan',
            ])->assertRedirect(route('kalender-haid.index'));
        }
        $this->assertSame(3, $siswa->kalenderHaid()->count());
        $this->get(route('kalender-haid.index'))->assertOk()->assertSee('Riwayat');

        // 8. Post-test terbuka setelah semua materi selesai, dan final
        $this->get(route('kuesioner.posttest.create'))->assertOk();
        $this->post(route('kuesioner.posttest.store'), ['jawaban' => $this->jawabanLengkap()])
            ->assertRedirect(route('dashboard'));
        $this->post(route('kuesioner.posttest.store'), ['jawaban' => $this->jawabanLengkap()]);
        $this->assertSame(1, $siswa->hasilKuesioner()->where('tipe_sesi', 'post')->count());

        // 9. Profil menampilkan data pribadi
        $this->get(route('profile.edit'))->assertOk()
            ->assertSee('Nadia')
            ->assertSee('SMPN 4 Bandung')
            ->assertSee('4 dari 4 modul selesai');
    }

    public function test_perjalanan_admin_di_atas_data_siswa_yang_sama(): void
    {
        $this->seed(KuesionerSoalSeeder::class);
        $this->seed(ModulSeeder::class);

        // Siswa yang sudah pre+post, supaya dashboard & export punya isi.
        $siswa = User::factory()->create(['name' => 'Nadia', 'usia' => 15, 'jenis_kelamin' => 'P']);
        foreach (['pre', 'post'] as $tipe) {
            $siswa->hasilKuesioner()->create([
                'tipe_sesi' => $tipe,
                'skor_pengetahuan' => 100,
                'kategori_pengetahuan' => 'Baik',
                'skor_sikap' => 80,
                'submitted_at' => now(),
            ]);
        }
        $sub = SubBagian::orderBy('id')->firstOrFail();
        $siswa->progressModul()->create(['sub_bagian_id' => $sub->id, 'materi_selesai' => true, 'materi_selesai_at' => now()]);
        $siswa->refleksi()->create(['sub_bagian_id' => $sub->id, 'jawaban' => 'RAHASIA REFLEKSI', 'is_locked' => true, 'submitted_at' => now()]);
        $siswa->trackerGizi()->create(['data' => ['Senin' => ['sayur_buah' => true]], 'is_locked' => true, 'submitted_at' => now()]);
        $siswa->kalenderHaid()->create(['tanggal_mulai' => '2026-08-04', 'tanggal_selesai' => '2026-08-09', 'catatan' => 'Kram ringan']);

        // Admin belum login: semua halaman admin tertutup
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        // Siswa TIDAK boleh masuk area admin
        $this->actingAs($siswa)->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->post(route('logout'));

        Admin::create(['nama' => 'Admin', 'email' => 'admin@genresilup.test', 'password' => 'password']);
        $this->post(route('admin.login'), ['email' => 'admin@genresilup.test', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        // Admin TIDAK boleh masuk halaman siswa
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $dashboard = $this->get(route('admin.dashboard'))->assertOk()->assertSee('Nadia');
        $this->assertStringNotContainsString('RAHASIA REFLEKSI', $dashboard->getContent());

        // CRUD soal
        $jumlahAwal = KuesionerSoal::where('is_aktif', true)->count();
        $this->post(route('admin.soal.store'), [
            'tipe' => 'pengetahuan', 'pertanyaan' => 'Soal uji coba E2E', 'jawaban_benar' => 'B', 'urutan' => 99, 'is_aktif' => '1',
        ])->assertRedirect(route('admin.soal.index'));
        $baru = KuesionerSoal::where('pertanyaan', 'Soal uji coba E2E')->firstOrFail();
        $this->assertSame($jumlahAwal + 1, KuesionerSoal::where('is_aktif', true)->count());

        $this->put(route('admin.soal.update', $baru), [
            'tipe' => 'pengetahuan', 'pertanyaan' => 'Soal uji coba E2E (diubah)', 'jawaban_benar' => 'S', 'urutan' => 99, 'is_aktif' => '1',
        ])->assertRedirect(route('admin.soal.index'));
        $this->assertSame('Soal uji coba E2E (diubah)', $baru->refresh()->pertanyaan);

        // "Hapus" = nonaktifkan (is_aktif=false), bukan hapus baris — FK
        // hasil_kuesioner_detail.soal_id harus tetap utuh untuk jawaban historis.
        $this->delete(route('admin.soal.destroy', $baru))->assertRedirect(route('admin.soal.index'));
        $this->assertFalse((bool) $baru->refresh()->is_aktif);
        $this->assertSame($jumlahAwal, KuesionerSoal::where('is_aktif', true)->count());

        // View-only: materi & kalender haid bisa dilihat, refleksi/tracker tidak pernah bocor
        $materi = $this->get(route('admin.materi.index'))->assertOk();
        $kalender = $this->get(route('admin.kalender-haid.index'))->assertOk()->assertSee('Kram ringan');
        foreach ([$materi, $kalender] as $halaman) {
            $this->assertStringNotContainsString('RAHASIA REFLEKSI', $halaman->getContent());
        }

        // Export: 1 baris siswa, kolom pre & post terisi, tanpa data refleksi/tracker
        $csv = $this->get(route('admin.export'))->assertOk()->streamedContent();
        $this->assertStringContainsString('Pre Skor Pengetahuan', $csv);
        $this->assertStringContainsString('Nadia', $csv);
        $this->assertStringNotContainsString('RAHASIA REFLEKSI', $csv);
        $this->assertStringNotContainsString('sayur_buah', $csv);
        $this->assertSame(2, substr_count(trim($csv), "\n") + 1, 'Header + 1 baris siswa.');

        $excel = $this->get(route('admin.export', ['format' => 'excel']))->assertOk()->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $excel, 'Varian Excel harus ber-BOM UTF-8.');
        $this->assertStringContainsString(';', $excel);
    }
}
