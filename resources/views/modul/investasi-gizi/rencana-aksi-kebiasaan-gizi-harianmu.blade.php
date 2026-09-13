<div class="space-y-6">
    <p>
        Yuk, coba pantau kebiasaan makanmu selama seminggu. Isi tabel ini setiap hari sebagai
        pengingat sederhana:
    </p>

    <div class="overflow-x-auto rounded-2xl border border-brand-line">
        <table class="w-full text-sm text-left">
            <thead class="bg-brand-mint-soft text-brand-ink font-semibold">
                <tr>
                    <th class="p-3">Hari</th>
                    <th class="p-3">Sayur &amp; Buah?</th>
                    <th class="p-3">Sumber Protein?</th>
                    <th class="p-3">Air Putih Cukup?</th>
                    <th class="p-3">Camilan Sehat?</th>
                </tr>
            </thead>
            <tbody class="text-brand-ink/80">
                <tr class="border-t border-brand-line">
                    <td class="p-3">Contoh: Senin</td>
                    <td class="p-3">✅ Ya</td>
                    <td class="p-3">✅ Ya (telur)</td>
                    <td class="p-3">✅ 8 gelas</td>
                    <td class="p-3">✅ Kacang tanah</td>
                </tr>
                @for ($i = 0; $i < 6; $i++)
                    <tr class="border-t border-brand-line">
                        <td class="p-3">&nbsp;</td>
                        <td class="p-3">&nbsp;</td>
                        <td class="p-3">&nbsp;</td>
                        <td class="p-3">&nbsp;</td>
                        <td class="p-3">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="{{ route('tracker-gizi.show') }}" class="inline-block px-4 py-2 rounded-lg bg-brand-mint font-semibold text-brand-ink hover:opacity-90">
            📋 Isi Tracker Gizi Mingguanmu
        </a>
    </div>

    <x-highlight title="Ingat!" icon="✅">
        <p>Tidak perlu sempurna setiap hari — konsistensi kecil lebih baik daripada perubahan drastis yang sulit bertahan.</p>
        <p>Gizi baik hari ini adalah investasi untuk versi dirimu yang lebih sehat, fokus, dan siap mengejar mimpi di masa depan.</p>
    </x-highlight>

    <h3 class="font-semibold text-brand-ink text-lg">Penutup: #healthyhabits</h3>

    <p>
        Investasi terbaik bukan cuma soal uang atau pendidikan — tapi juga soal tubuhmu sendiri.
        Kebiasaan gizi yang kamu bangun hari ini akan menentukan seberapa siap kamu menghadapi
        masa depan: fisik yang kuat, otak yang tajam, dan tubuh yang siap mendukung
        mimpi-mimpimu.
    </p>

    <figure>
        <img src="{{ asset('images/modul-2/healthy-habits.jpg') }}" alt="#healthyhabits" class="rounded-2xl w-full">
        <figcaption class="text-sm text-brand-ink/60 italic mt-2">#healthyhabits — mulai dari langkah kecil, konsisten setiap hari.</figcaption>
    </figure>

    <x-tip-box title="Butuh Info Lebih Lanjut?" icon="🤝">
        <p>Konsultasikan kebutuhan gizi pribadimu ke Puskesmas terdekat atau ahli gizi.</p>
        <p>Ikuti program PIK-R (Pusat Informasi dan Konseling Remaja) di sekolah/komunitasmu.</p>
        <p>Bagi remaja putri: jangan lupa konsumsi tablet tambah darah sesuai anjuran tenaga kesehatan.</p>
    </x-tip-box>

    <p class="text-center font-semibold text-brand-ink italic">Yuk, jadikan gizi sebagai investasi harianmu. 🥗🌱</p>
</div>
