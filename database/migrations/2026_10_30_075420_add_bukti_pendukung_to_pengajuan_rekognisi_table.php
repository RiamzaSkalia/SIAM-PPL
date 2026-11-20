<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_rekognisi', function (Blueprint $table) {
            $table->string('bukti_pendukung')->nullable()->after('matkul_rekognisi_id');
            $table->string('status')->default('Pending')->after('bukti_pendukung');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_rekognisi', function (Blueprint $table) {
            $table->dropColumn(['bukti_pendukung', 'status']);
        });
    }
};
