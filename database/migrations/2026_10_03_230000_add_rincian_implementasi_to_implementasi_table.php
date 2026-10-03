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
        Schema::table('implementasi', function (Blueprint $table) {
            if (!Schema::hasColumn('implementasi', 'rincian_implementasi')) {
                $table->json('rincian_implementasi')->nullable()->after('resep_obat')->comment('Daftar dinamis tindakan keperawatan dan obat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('implementasi', function (Blueprint $table) {
            if (Schema::hasColumn('implementasi', 'rincian_implementasi')) {
                $table->dropColumn('rincian_implementasi');
            }
        });
    }
};
