<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('periode_id')->nullable()->constrained('periode_akademik')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->string('nama_dosen');
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('periode_id')->nullable()->constrained('periode_akademik')->onDelete('cascade');
            $table->string('nim')->unique();
            $table->string('nama_mahasiswa');
            $table->string('no_hp')->nullable();
            $table->string('prodi')->nullable();
            $table->string('semester')->nullable();
            $table->string('angkatan')->nullable();
            $table->timestamps();
        });

        Schema::create('sekolah_mitra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->nullable()->constrained('periode_akademik')->onDelete('cascade');
            $table->string('npsn');
            $table->string('nama_sekolah');
            $table->string('jenjang')->nullable();
            $table->integer('kuota')->default(0);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('guru_pamong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sekolah_id')->nullable()->constrained('sekolah_mitra')->onDelete('set null');
            $table->string('nip_nik')->unique();
            $table->string('nama_guru_pamong');
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

        Schema::create('plotting_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_akademik')->onDelete('cascade');
            $table->foreignId('sekolah_id')->constrained('sekolah_mitra')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('plotting_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plotting_id')->constrained('plotting_bimbingan')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('modul_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->dateTime('tgl_upload');
            $table->timestamps();
        });

        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plotting_id')->constrained('plotting_bimbingan')->onDelete('cascade');
            $table->date('tanggal_konsul');
            $table->string('media_konsul');
            $table->text('topik_dibahas');
            $table->text('refleksi_mahasiswa')->nullable();
            $table->text('saran_dosen')->nullable();
            $table->string('paraf_dosen')->nullable();
            $table->enum('status_validasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });

        Schema::create('komentar_gupam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsultasi_id')->constrained('konsultasi')->onDelete('cascade');
            $table->foreignId('gupam_id')->constrained('guru_pamong')->onDelete('cascade');
            $table->text('catatan_umpan_balik');
            $table->dateTime('tgl_komentar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_gupam');
        Schema::dropIfExists('konsultasi');
        Schema::dropIfExists('modul_materi');
        Schema::dropIfExists('plotting_mahasiswa');
        Schema::dropIfExists('plotting_bimbingan');
        Schema::dropIfExists('guru_pamong');
        Schema::dropIfExists('sekolah_mitra');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
        Schema::dropIfExists('periode_akademik');
    }
};