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
        Schema::table('asuhan_medis', function (Blueprint $table) {
            if (!Schema::hasColumn('asuhan_medis', 'tinggi_badan')) {
                $table->decimal('tinggi_badan', 5, 1)->nullable()->after('spo2')->comment('Tinggi badan dalam cm');
            }
            if (!Schema::hasColumn('asuhan_medis', 'berat_badan')) {
                $table->decimal('berat_badan', 5, 2)->nullable()->after('tinggi_badan')->comment('Berat badan dalam kg');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuhan_medis', function (Blueprint $table) {
            if (Schema::hasColumn('asuhan_medis', 'tinggi_badan')) {
                $table->dropColumn('tinggi_badan');
            }
            if (Schema::hasColumn('asuhan_medis', 'berat_badan')) {
                $table->dropColumn('berat_badan');
            }
        });
    }
};
