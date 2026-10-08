<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('header_1')->nullable();
            $table->string('header_2')->nullable();
            $table->string('header_3')->nullable();
            $table->string('header_4')->nullable();
            $table->string('header_5')->nullable();
            $table->string('ttd_jabatan')->nullable();
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_nip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};