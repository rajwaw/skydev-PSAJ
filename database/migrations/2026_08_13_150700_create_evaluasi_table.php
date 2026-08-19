<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->unsignedBigInteger('id_rekam_medis');
            $table->string('status_kondisi', 50)->nullable();
            $table->string('status_evaluasi', 50)->nullable();
            $table->text('keluhan_setelah_tindakan')->nullable();
            $table->text('respon_pasien')->nullable();
            $table->text('hasil_evaluasi')->nullable();
            $table->text('rencana_selanjutnya')->nullable();
            $table->text('catatan_soap')->nullable();
            $table->timestamps();

            $table->index('id_rekam_medis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};

