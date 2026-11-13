<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenaga_pendukung', function (Blueprint $table) {
            $table->id();
            $table->string('kode_petugas', 25)->unique();
            $table->string('nama_lengkap', 100);
            $table->enum('kategori', ['Pramubakti', 'Satpam']);
            $table->string('area_tanggung_jawab', 120);
            $table->string('jadwal_shift', 60)->default('Reguler (07.00 - 16.00)');
            $table->string('no_hp', 20)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan', 30)->unique();
            $table->foreignId('tenaga_pendukung_id')->constrained('tenaga_pendukung')->cascadeOnDelete();
            $table->date('tanggal_kegiatan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('kategori', ['Pramubakti', 'Satpam']);
            $table->string('jenis_pekerjaan', 150);
            $table->string('lokasi_pekerjaan', 120);
            $table->text('uraian_pekerjaan');

            $table->string('status_penyelesaian', 50)->default('Selesai 100%');
            $table->text('catatan_kendala')->nullable();

            $table->string('shift_jaga', 50)->nullable();
            $table->string('kondisi_keamanan', 50)->default('Aman & Kondusif');
            $table->integer('jumlah_tamu')->default(0);

            $table->string('gdrive_file_id')->nullable();
            $table->string('gdrive_view_link')->nullable();

            $table->enum('status_verifikasi', ['Menunggu Verifikasi', 'Disetujui', 'Dikembalikan'])->default('Menunggu Verifikasi');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenaga_pendukung_id')->constrained('tenaga_pendukung')->cascadeOnDelete();
            $table->string('periode_bulan', 20);
            $table->decimal('skor_kehadiran', 5, 2);
            $table->decimal('skor_kualitas', 5, 2);
            $table->decimal('skor_kuantitas', 5, 2);
            $table->decimal('skor_ketepatan', 5, 2);
            $table->decimal('skor_tanggung_jawab', 5, 2);
            $table->decimal('nilai_akhir', 5, 2);
            $table->string('predikat', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerja');
        Schema::dropIfExists('laporan_harian');
        Schema::dropIfExists('tenaga_pendukung');
    }
};