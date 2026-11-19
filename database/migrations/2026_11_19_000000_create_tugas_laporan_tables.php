<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'jabatan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('jabatan')->nullable();
            });
        }

        if (! Schema::hasTable('tugas')) {
            Schema::create('tugas', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('jabatan');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('batas_waktu')->nullable();
                $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('laporan')) {
            Schema::create('laporan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('status')->default('diterima'); // diterima, menunggu, revisi, disetujui
                $table->dateTime('diterima_at')->nullable();
                $table->dateTime('dikirim_at')->nullable();
                $table->text('catatan')->nullable();
                $table->text('catatan_admin')->nullable();
                $table->unsignedTinyInteger('nilai_kualitas')->nullable();
                $table->unsignedTinyInteger('nilai_kehadiran')->nullable();
                $table->unsignedTinyInteger('nilai_kuantitas')->nullable();
                $table->unsignedTinyInteger('nilai_ketepatan')->nullable();
                $table->unsignedTinyInteger('nilai_tanggung_jawab')->nullable();
                $table->decimal('nilai_total', 5, 2)->nullable();
                $table->dateTime('dinilai_at')->nullable();
                $table->unsignedInteger('jumlah_revisi')->default(0);
                $table->timestamps();
                $table->unique(['tugas_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('laporan_foto')) {
            Schema::create('laporan_foto', function (Blueprint $table) {
                $table->id();
                $table->foreignId('laporan_id')->constrained('laporan')->cascadeOnDelete();
                $table->string('path');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('laporan_riwayat')) {
            Schema::create('laporan_riwayat', function (Blueprint $table) {
                $table->id();
                $table->foreignId('laporan_id')->constrained('laporan')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('aksi');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_riwayat');
        Schema::dropIfExists('laporan_foto');
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('tugas');
    }
};