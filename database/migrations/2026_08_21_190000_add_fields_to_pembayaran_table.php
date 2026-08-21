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
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 50)->nullable()->after('status_bayar');
            }
            if (!Schema::hasColumn('pembayaran', 'uang_dibayar')) {
                $table->decimal('uang_dibayar', 12, 2)->nullable()->default(0)->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('pembayaran', 'kembalian')) {
                $table->decimal('kembalian', 12, 2)->nullable()->default(0)->after('uang_dibayar');
            }
            if (!Schema::hasColumn('pembayaran', 'catatan')) {
                $table->text('catatan')->nullable()->after('kembalian');
            }
            if (!Schema::hasColumn('pembayaran', 'rincian_obat')) {
                $table->json('rincian_obat')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('pembayaran', 'rincian_tindakan')) {
                $table->json('rincian_tindakan')->nullable()->after('rincian_obat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $cols = [];
            foreach (['metode_pembayaran', 'uang_dibayar', 'kembalian', 'catatan', 'rincian_obat', 'rincian_tindakan'] as $col) {
                if (Schema::hasColumn('pembayaran', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
