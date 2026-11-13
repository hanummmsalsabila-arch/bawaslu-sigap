const masterPekerjaan = {
    Pramubakti: [
        "1. Kebersihan ruang kerja — Ruangan bersih dan rapi",
        "2. Kebersihan toilet — Toilet bersih dan tersedia perlengkapan",
        "3. Kebersihan halaman — Halaman bebas sampah",
        "4. Penataan ruang rapat — Ruangan siap digunakan",
        "5. Pelayanan konsumsi — Konsumsi tersedia sesuai kebutuhan",
        "6. Pemeliharaan fasilitas — Kerusakan dilaporkan",
        "7. Pengelolaan sampah — Sampah terkelola dengan baik",
        "8. Pelayanan kegiatan kantor — Kegiatan kantor terfasilitasi"
    ],
    Satpam: [
        "1. Penjagaan kantor — Pos penjagaan terisi",
        "2. Pemeriksaan tamu — Identitas dan tujuan tercatat",
        "3. Pengawasan kendaraan — Kendaraan terpantau",
        "4. Patroli lingkungan — Area kantor diperiksa",
        "5. Pengawasan akses kantor — Akses terkontrol",
        "6. Pemeriksaan fasilitas keamanan — Kondisi fasilitas diperiksa",
        "7. Penanganan kejadian — Insiden dicatat dan dilaporkan",
        "8. Serah terima tugas — Informasi pergantian jaga tercatat"
    ]
};

window.ubahKategoriForm = function () {
    const katEl = document.getElementById('input-kategori');
    if (!katEl) return;
    const kat = katEl.value;
    const selectJenis = document.getElementById('input-jenis-pekerjaan');
    selectJenis.innerHTML = '';

    masterPekerjaan[kat].forEach(item => {
        const opt = document.createElement('option');
        opt.value = item;
        opt.textContent = item;
        selectJenis.appendChild(opt);
    });

    document.getElementById('box-form-pramubakti')?.classList.toggle('hidden', kat !== 'Pramubakti');
    document.getElementById('box-form-satpam')?.classList.toggle('hidden', kat !== 'Satpam');
};

window.buatWatermarkOtomatis = function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {
            const canvas = document.getElementById('canvas-watermark');
            const ctx = canvas.getContext('2d');

            const maxW = 1000;
            const scale = img.width > maxW ? maxW / img.width : 1;
            canvas.width = img.width * scale;
            canvas.height = img.height * scale;

            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            const barH = 84;
            ctx.fillStyle = 'rgba(10, 37, 64, 0.90)';
            ctx.fillRect(0, canvas.height - barH, canvas.width, barH);

            ctx.fillStyle = '#F59E0B';
            ctx.fillRect(0, canvas.height - barH, canvas.width, 4);

            const kat = document.getElementById('input-kategori').value;
            const selPetugas = document.getElementById('input-petugas');
            const namaPetugas = selPetugas.options[selPetugas.selectedIndex].text;
            const lokasi = document.getElementById('input-lokasi').value || 'Kantor Bawaslu Kota Surabaya';
            const waktu = new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'medium' });

            ctx.fillStyle = '#F59E0B';
            ctx.font = 'bold 16px sans-serif';
            ctx.fillText(`BAWASLU-SIGAP KOTA SURABAYA • DOKUMENTASI ${kat.toUpperCase()}`, 16, canvas.height - 54);

            ctx.fillStyle = '#FFFFFF';
            ctx.font = '14px sans-serif';
            ctx.fillText(`Petugas: ${namaPetugas} | Lokasi: ${lokasi}`, 16, canvas.height - 32);

            ctx.fillStyle = '#93C5FD';
            ctx.font = '12px sans-serif';
            ctx.fillText(`Waktu: ${waktu} WIB • Verified Digital Evidence`, 16, canvas.height - 12);

            canvas.classList.remove('hidden');
            document.getElementById('watermarked-base64').value = canvas.toDataURL('image/jpeg', 0.82);
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
};

window.kirimLaporanKeGDrive = async function (event) {
    event.preventDefault();
    const form = document.getElementById('form-sigap-laporan');
    const btn = document.getElementById('btn-submit-laporan');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span>Menyimpan Laporan...</span>';

    try {
        const res = await fetch('/api/laporan/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        const result = await res.json();
        if (result.status === 'success') {
            window.location.reload();
        }
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span>Simpan Laporan & Bukti Foto</span>';
    }
};