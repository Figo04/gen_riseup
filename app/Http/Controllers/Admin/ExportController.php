<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export hasil kuesioner pre vs post, 1 baris = 1 siswa.
     *
     * Sumber data HANYA users + hasil_kuesioner (PRD §5). Refleksi &
     * tracker gizi permanen out of scope (PRD §4.3 / CLAUDE.md rule #9) —
     * tidak di-query di sini sama sekali.
     */
    public function __invoke(Request $request): StreamedResponse
    {
        // format=excel: delimiter ';' + BOM UTF-8 supaya Excel locale ID
        // langsung membagi kolom dengan benar; isinya tetap CSV.
        $forExcel = $request->query('format') === 'excel';
        $delimiter = $forExcel ? ';' : ',';
        $filename = 'hasil-kuesioner-'.now()->format('Y-m-d').'.csv';

        $siswa = User::with('hasilKuesioner')->orderBy('name')->get();

        return response()->streamDownload(function () use ($siswa, $delimiter, $forExcel) {
            $out = fopen('php://output', 'w');

            if ($forExcel) {
                fwrite($out, "\xEF\xBB\xBF");
            }

            fputcsv($out, [
                'Nama', 'Email', 'Usia', 'Jenis Kelamin', 'Sekolah', 'Kelas',
                'Pre Skor Pengetahuan', 'Pre Kategori', 'Pre Skor Sikap', 'Pre Submitted At',
                'Post Skor Pengetahuan', 'Post Kategori', 'Post Skor Sikap', 'Post Submitted At',
            ], $delimiter);

            foreach ($siswa as $s) {
                // Unique(user_id, tipe_sesi) di hasil_kuesioner → maks 1 baris per tipe.
                $pre = $s->hasilKuesioner->firstWhere('tipe_sesi', 'pre');
                $post = $s->hasilKuesioner->firstWhere('tipe_sesi', 'post');

                fputcsv($out, [
                    $s->name, $s->email, $s->usia, $s->jenis_kelamin, $s->sekolah, $s->kelas,
                    $pre?->skor_pengetahuan, $pre?->kategori_pengetahuan, $pre?->skor_sikap,
                    $pre?->submitted_at?->format('Y-m-d H:i'),
                    $post?->skor_pengetahuan, $post?->kategori_pengetahuan, $post?->skor_sikap,
                    $post?->submitted_at?->format('Y-m-d H:i'),
                ], $delimiter);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
