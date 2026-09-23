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
        Schema::table('evaluasi', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluasi', 'soap_s')) {
                $table->text('soap_s')->nullable()->after('catatan_soap')->comment('Subjektif SOAP');
            }
            if (!Schema::hasColumn('evaluasi', 'soap_o')) {
                $table->text('soap_o')->nullable()->after('soap_s')->comment('Objektif SOAP');
            }
            if (!Schema::hasColumn('evaluasi', 'soap_a')) {
                $table->text('soap_a')->nullable()->after('soap_o')->comment('Asesmen SOAP');
            }
            if (!Schema::hasColumn('evaluasi', 'soap_p')) {
                $table->text('soap_p')->nullable()->after('soap_a')->comment('Plan SOAP');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi', function (Blueprint $table) {
            $cols = ['soap_s', 'soap_o', 'soap_a', 'soap_p'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('evaluasi', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
