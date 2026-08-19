<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catatan: kolom faktor_terkait, prioritas_diagnosa, target, keterangan
        // SENGAJA tidak dimasukkan di sini karena sudah ditambahkan oleh
        // migration 2026_08_18_220000_add_fields_to_asuhan_medis_and_intervensi_tables.php
        Schema::create('intervensi', function (Blueprint $table) {
            $table->id('id_intervensi');
            $table->unsignedBigInteger('id_rekam_medis');
            $table->text('rencana_tindakan')->nullable();
            $table->text('diagnosa_awal')->nullable();
            $table->timestamps();

            $table->index('id_rekam_medis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervensi');
    }
};

