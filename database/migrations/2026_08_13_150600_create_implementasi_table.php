<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementasi', function (Blueprint $table) {
            $table->id('id_implementasi');
            $table->unsignedBigInteger('id_rekam_medis');
            $table->text('tindakan_dilakukan')->nullable();
            $table->text('resep_obat')->nullable();
            $table->timestamps();

            $table->index('id_rekam_medis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementasi');
    }
};

