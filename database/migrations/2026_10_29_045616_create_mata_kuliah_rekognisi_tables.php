<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table Master Matkul Rekognisi yang Diinput Admin per Periode
        Schema::create('matkul_rekognisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_akademik')->onDelete('cascade');
            $table->string('kode_mk');
            $table->string('nama_mk');
            $table->integer('sks');
            $table->dateTime('batas_waktu'); // Waktu rekognisi diakhiri
            $table->timestamps();
        });

        // Table Pivot Pilihan Matkul Rekognisi Mahasiswa
        Schema::create('pengajuan_rekognisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('matkul_rekognisi_id')->constrained('matkul_rekognisi')->onDelete('cascade');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_rekognisi');
        Schema::dropIfExists('matkul_rekognisi');
    }
};