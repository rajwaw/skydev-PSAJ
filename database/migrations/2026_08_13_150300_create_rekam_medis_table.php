<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam_medis');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->unsignedBigInteger('id_pasien');
            $table->dateTime('tgl_pemeriksaan');
            $table->timestamps();

            $table->index('id_pendaftaran');
            $table->index('id_pasien');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};

