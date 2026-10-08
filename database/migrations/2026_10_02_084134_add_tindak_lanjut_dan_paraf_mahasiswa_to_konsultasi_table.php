<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('konsultasi', function (Blueprint $table) {
            if (!Schema::hasColumn('konsultasi', 'refleksi_mahasiswa')) {
                $table->text('refleksi_mahasiswa')->nullable();
            }
            if (!Schema::hasColumn('konsultasi', 'umpan_balik_dosen')) {
                $table->text('umpan_balik_dosen')->nullable();
            }
            if (!Schema::hasColumn('konsultasi', 'tindak_lanjut_mahasiswa')) {
                $table->text('tindak_lanjut_mahasiswa')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropColumn([
                'refleksi_mahasiswa', 
                'umpan_balik_dosen', 
                'tindak_lanjut_mahasiswa'
            ]);
        });
    }
};