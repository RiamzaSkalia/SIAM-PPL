<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table Master Matkul Rekognisi
        Schema::create('matkul_rekognisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_akademik')->onDelete('cascade');
            $table->string('kode_mk');
            $table->string('nama_mk');
            $table->integer('sks');
            $table->timestamps();
        });

        // Table Pengaturan Tenggat Rekognisi per Periode
        Schema::create('pengaturan_rekognisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->unique()->constrained('periode_akademik')->onDelete('cascade');
            $table->dateTime('tenggat_waktu')->nullable(); // <-- Disesuaikan ke tenggat_waktu
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });

        // Table Pivot Pilihan Rekognisi Mahasiswa
        Schema::create('pengajuan_rekognisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('matkul_rekognisi_id')->constrained('matkul_rekognisi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_rekognisi');
        Schema::dropIfExists('pengaturan_rekognisi');
        Schema::dropIfExists('matkul_rekognisi');
    }
};