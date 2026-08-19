<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alergi_obat', function (Blueprint $table) {
            $table->id('id_alergi');
            $table->unsignedBigInteger('id_pasien');
            $table->string('nama_obat', 100);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('id_pasien');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alergi_obat');
    }
};

