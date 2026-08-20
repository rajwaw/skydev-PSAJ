<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id('id_pasien');
            $table->string('nik', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->date('tgl_lahir');
            $table->string('jk', 10);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};

