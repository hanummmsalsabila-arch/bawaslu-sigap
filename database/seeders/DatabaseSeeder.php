<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $personel = [
            ['kode_petugas' => 'PRB-01', 'nama_lengkap' => 'Slamet Riyadi', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Ruang Komisioner & Sekretariat Lt. 1', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456001'],
            ['kode_petugas' => 'PRB-02', 'nama_lengkap' => 'Siti Aminah', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Ruang Rapat Utama & Konsumsi', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456002'],
            ['kode_petugas' => 'PRB-03', 'nama_lengkap' => 'Moch. Taufik', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Halaman Kantor & Pemeliharaan Fasilitas', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456003'],
            ['kode_petugas' => 'PRB-04', 'nama_lengkap' => 'Rina Wahyuni', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Lobby, Ruang Gakkumdu & Toilet Lt. 1', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456004'],
            ['kode_petugas' => 'PRB-05', 'nama_lengkap' => 'Dedi Kurniawan', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Ruang Arsip, JDIH & Pengelolaan Sampah', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456005'],
            ['kode_petugas' => 'PRB-06', 'nama_lengkap' => 'Nurul Hidayah', 'kategori' => 'Pramubakti', 'area_tanggung_jawab' => 'Pelayanan Kegiatan Kantor & Ruang Sidang', 'jadwal_shift' => 'Reguler (07.00 - 16.00)', 'no_hp' => '08123456006'],
            ['kode_petugas' => 'STP-01', 'nama_lengkap' => 'Budi Santoso', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Komandan Regu & Pos Jaga Utama', 'jadwal_shift' => 'Shift Pagi (07.00 - 15.00)', 'no_hp' => '08123456007'],
            ['kode_petugas' => 'STP-02', 'nama_lengkap' => 'Agus Pratama', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Pemeriksaan Tamu & Pengawasan Akses', 'jadwal_shift' => 'Shift Pagi (07.00 - 15.00)', 'no_hp' => '08123456008'],
            ['kode_petugas' => 'STP-03', 'nama_lengkap' => 'Hendra Wijaya', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Patroli Lingkungan & Parkir Kendaraan', 'jadwal_shift' => 'Shift Sore (15.00 - 23.00)', 'no_hp' => '08123456009'],
            ['kode_petugas' => 'STP-04', 'nama_lengkap' => 'Eko Prasetyo', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Pos Penjagaan & CCTV Gedung', 'jadwal_shift' => 'Shift Sore (15.00 - 23.00)', 'no_hp' => '08123456010'],
            ['kode_petugas' => 'STP-05', 'nama_lengkap' => 'Wahyu Setiawan', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Patroli Malam & Fasilitas Keamanan', 'jadwal_shift' => 'Shift Malam (23.00 - 07.00)', 'no_hp' => '08123456011'],
            ['kode_petugas' => 'STP-06', 'nama_lengkap' => 'Rizky Ramadhan', 'kategori' => 'Satpam', 'area_tanggung_jawab' => 'Penjagaan Malam & Serah Terima Tugas', 'jadwal_shift' => 'Shift Malam (23.00 - 07.00)', 'no_hp' => '08123456012'],
        ];
        DB::table('tenaga_pendukung')->insert($personel);

        DB::table('laporan_harian')->insert([
            [
                'kode_laporan' => 'SIGAP-20261113-001',
                'tenaga_pendukung_id' => 1,
                'tanggal_kegiatan' => now()->toDateString(),
                'jam_mulai' => '07:00',
                'jam_selesai' => '08:30',
                'kategori' => 'Pramubakti',
                'jenis_pekerjaan' => '1. Kebersihan ruang kerja — Ruangan bersih dan rapi',
                'lokasi_pekerjaan' => 'Ruang Komisioner & Sekretariat Lt. 1',
                'uraian_pekerjaan' => 'Menyapu, mengepel lantai, membersihkan meja kerja, dan merapikan sofa tamu.',
                'status_penyelesaian' => 'Selesai 100%',
                'gdrive_view_link' => null,
                'status_verifikasi' => 'Disetujui',
                'created_at' => now(),
            ],
            [
                'kode_laporan' => 'SIGAP-20261113-002',
                'tenaga_pendukung_id' => 7,
                'tanggal_kegiatan' => now()->toDateString(),
                'jam_mulai' => '07:00',
                'jam_selesai' => '15:00',
                'kategori' => 'Satpam',
                'jenis_pekerjaan' => '2. Pemeriksaan tamu — Identitas dan tujuan tercatat',
                'lokasi_pekerjaan' => 'Pos Jaga Depan & Area Parkir',
                'uraian_pekerjaan' => 'Mencatat 9 tamu dinas, memeriksa kartu identitas, dan mengarahkan parkir kendaraan.',
                'status_penyelesaian' => 'Selesai 100%',
                'gdrive_view_link' => null,
                'status_verifikasi' => 'Menunggu Verifikasi',
                'created_at' => now(),
            ]
        ]);
    }
}