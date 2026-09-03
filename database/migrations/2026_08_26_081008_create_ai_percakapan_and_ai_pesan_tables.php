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
        Schema::create('ai_percakapan', function (Blueprint $table) {
            $table->id('id_percakapan');
            $table->unsignedBigInteger('id_pasien')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('judul')->nullable();
            $table->timestamps();

            $table->foreign('id_pasien')->references('id_pasien')->on('pasien')->onDelete('set null');
        });

        Schema::create('ai_pesan', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->unsignedBigInteger('id_percakapan');
            $table->enum('role', ['user', 'assistant']);
            $table->text('isi');
            $table->timestamps();

            $table->foreign('id_percakapan')->references('id_percakapan')->on('ai_percakapan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_pesan');
        Schema::dropIfExists('ai_percakapan');
    }
};