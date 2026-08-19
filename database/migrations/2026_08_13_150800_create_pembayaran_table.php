<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CATATAN: tabel ini tidak punya Model/Controller di repo, jadi kolomnya
        // hasil tebakan berdasarkan konvensi umum sistem pembayaran klinik.
        // Cross-check ke tim / rajwaw kalau ada field spesifik yang kepakai di frontend.
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->decimal('jumlah_bayar', 12, 2)->nullable();
            $table->string('metode_pembayaran', 30)->nullable();
            $table->string('status_pembayaran', 30)->default('Belum Bayar');
            $table->timestamps();

            $table->index('id_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};

