<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('plain_password')->nullable()->after('email');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('plain_password')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('plain_password');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('plain_password');
        });
    }
};