<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->unsignedBigInteger('id_pasien');
            $table->dateTime('tgl_daftar');
            $table->integer('no_antrean')->nullable();
            $table->string('status_kunjungan', 30)->default('Menunggu');
            $table->timestamps();

            $table->index('id_pasien');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};

