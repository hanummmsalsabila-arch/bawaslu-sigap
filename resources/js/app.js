import './chart-dashboard.js';
import './watermark-gdrive.js';

window.bukaModul = function (targetId, btnEl = null) {
    document.querySelectorAll('.modul-section').forEach(sec => sec.classList.add('hidden'));
    document.getElementById(targetId)?.classList.remove('hidden');

    if (btnEl) {
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('menu-active'));
        btnEl.classList.add('menu-active');
    }

    if (window.innerWidth < 1024) {
        document.getElementById('sidebar')?.classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay')?.classList.add('hidden');
    }
};

window.toggleMobileSidebar = function () {
    document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
    document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
};

window.openModalLaporan = function (defaultKategori = null) {
    if (defaultKategori) {
        document.getElementById('input-kategori').value = defaultKategori;
        window.ubahKategoriForm();
    }
    const modal = document.getElementById('modal-laporan');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closeModalLaporan = function () {
    const modal = document.getElementById('modal-laporan');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

window.verifikasiLaporan = async function (btn, id, statusBaru) {
    const statusCell = btn.closest('tr').querySelector('.status-badge-cell');
    statusCell.innerHTML = statusBaru === 'Disetujui'
        ? '<span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">Disetujui</span>'
        : '<span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 font-bold">Dikembalikan</span>';

    await fetch(`/api/verifikasi/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: statusBaru })
    });
};

window.hitungSkorEvaluasi = function () {
    const k1 = parseFloat(document.getElementById('range-k1').value);
    const k2 = parseFloat(document.getElementById('range-k2').value);
    const k3 = parseFloat(document.getElementById('range-k3').value);
    const k4 = parseFloat(document.getElementById('range-k4').value);
    const k5 = parseFloat(document.getElementById('range-k5').value);

    ['k1','k2','k3','k4','k5'].forEach((k, i) => {
        document.getElementById('label-' + k).textContent = [k1,k2,k3,k4,k5][i];
    });

    const total = (k1 * 0.20) + (k2 * 0.30) + (k3 * 0.20) + (k4 * 0.15) + (k5 * 0.15);
    document.getElementById('skor-akhir-angka').textContent = total.toFixed(2);

    const badge = document.getElementById('skor-akhir-predikat');
    if (total >= 91) {
        badge.textContent = '91–100: Sangat Baik';
        badge.className = 'mt-3 inline-block bg-emerald-500 text-white text-xs font-extrabold px-3.5 py-1 rounded-full';
    } else if (total >= 81) {
        badge.textContent = '81–90: Baik';
        badge.className = 'mt-3 inline-block bg-blue-500 text-white text-xs font-extrabold px-3.5 py-1 rounded-full';
    } else if (total >= 71) {
        badge.textContent = '71–80: Cukup';
        badge.className = 'mt-3 inline-block bg-amber-400 text-sigap-950 text-xs font-extrabold px-3.5 py-1 rounded-full';
    } else {
        badge.textContent = 'Di bawah 71: Perlu Pembinaan';
        badge.className = 'mt-3 inline-block bg-rose-600 text-white text-xs font-extrabold px-3.5 py-1 rounded-full';
    }
};

window.simpanEvaluasiKeDatabase = async function () {
    const btn = document.getElementById('btn-simpan-eval');
    const feedback = document.getElementById('eval-feedback-msg');
    const selectPetugas = document.getElementById('eval-tenaga-id');
    const namaPetugas = selectPetugas.options[selectPetugas.selectedIndex].text;

    const payload = {
        tenaga_pendukung_id: selectPetugas.value,
        skor_kehadiran: document.getElementById('range-k1').value,
        skor_kualitas: document.getElementById('range-k2').value,
        skor_kuantitas: document.getElementById('range-k3').value,
        skor_ketepatan: document.getElementById('range-k4').value,
        skor_tanggung_jawab: document.getElementById('range-k5').value,
    };

    btn.disabled = true;
    btn.textContent = 'Menyimpan ke Database...';

    try {
        const response = await fetch('/api/penilaian/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        if (result.status === 'success') {
            feedback.textContent = `Tersimpan! Nilai akhir ${namaPetugas}: ${result.nilai_akhir} (${result.predikat}).`;
            feedback.classList.remove('hidden');

            document.getElementById('row-eval-kosong')?.remove();
            const tbody = document.getElementById('tbody-evaluasi');
            const tr = document.createElement('tr');
            tr.className = 'bg-emerald-50/60';
            tr.innerHTML = `
                <td class="py-2.5 px-4 font-semibold">${new Date().toISOString().slice(0, 7)}</td>
                <td class="py-2.5 px-4 font-bold text-sigap-900">${namaPetugas}</td>
                <td class="py-2.5 px-4 font-extrabold text-sigap-700">${result.nilai_akhir}</td>
                <td class="py-2.5 px-4"><span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800">${result.predikat}</span></td>
            `;
            tbody.prepend(tr);
        }
    } finally {
        btn.disabled = false;
        btn.textContent = 'Simpan Hasil Evaluasi ke Database';
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.ubahKategoriForm === 'function') window.ubahKategoriForm();
});