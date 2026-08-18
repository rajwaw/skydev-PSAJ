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
        Schema::table('asuhan_medis', function (Blueprint $table) {
            if (!Schema::hasColumn('asuhan_medis', 'riwayat_keluhan')) {
                $table->text('riwayat_keluhan')->nullable()->after('keluhan_utama');
            }
            if (!Schema::hasColumn('asuhan_medis', 'kondisi_umum')) {
                $table->string('kondisi_umum', 50)->nullable()->after('riwayat_keluhan');
            }
            if (!Schema::hasColumn('asuhan_medis', 'kesadaran')) {
                $table->string('kesadaran', 50)->nullable()->after('kondisi_umum');
            }
            if (!Schema::hasColumn('asuhan_medis', 'spo2')) {
                $table->integer('spo2')->nullable()->after('rr');
            }
        });

        Schema::table('intervensi', function (Blueprint $table) {
            if (!Schema::hasColumn('intervensi', 'faktor_terkait')) {
                $table->text('faktor_terkait')->nullable()->after('diagnosa_awal');
            }
            if (!Schema::hasColumn('intervensi', 'prioritas_diagnosa')) {
                $table->text('prioritas_diagnosa')->nullable()->after('faktor_terkait');
            }
            if (!Schema::hasColumn('intervensi', 'target')) {
                $table->text('target')->nullable()->after('rencana_tindakan');
            }
            if (!Schema::hasColumn('intervensi', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('target');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuhan_medis', function (Blueprint $table) {
            $table->dropColumn(['riwayat_keluhan', 'kondisi_umum', 'kesadaran', 'spo2']);
        });

        Schema::table('intervensi', function (Blueprint $table) {
            $table->dropColumn(['faktor_terkait', 'prioritas_diagnosa', 'target', 'keterangan']);
        });
    }
};
