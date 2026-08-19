<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catatan: kolom riwayat_keluhan, kondisi_umum, kesadaran, spo2
        // SENGAJA tidak dimasukkan di sini karena sudah ditambahkan oleh
        // migration 2026_08_18_220000_add_fields_to_asuhan_medis_and_intervensi_tables.php
        Schema::create('asuhan_medis', function (Blueprint $table) {
            $table->id('id_asuhan_medis');
            $table->unsignedBigInteger('id_rekam_medis');
            $table->text('keluhan_utama')->nullable();
            $table->string('tekanan_darah', 20)->nullable();
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->integer('nadi')->nullable();
            $table->integer('rr')->nullable();
            $table->timestamps();

            $table->index('id_rekam_medis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asuhan_medis');
    }
};

