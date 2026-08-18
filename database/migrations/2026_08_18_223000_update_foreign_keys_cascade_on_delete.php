<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Helper to safely drop and re-add foreign key with ON DELETE CASCADE
        $this->updateForeignKey('alergi_obat', 'fk_alergi_pasien', 'id_pasien', 'pasien', 'id_pasien');
        $this->updateForeignKey('pendaftaran', 'fk_pendaftaran_pasien', 'id_pasien', 'pasien', 'id_pasien');
        $this->updateForeignKey('pembayaran', 'fk_pembayaran_pendaftaran', 'id_pendaftaran', 'pendaftaran', 'id_pendaftaran');
        $this->updateForeignKey('rekam_medis', 'fk_rekam_pasien', 'id_pasien', 'pasien', 'id_pasien');
        $this->updateForeignKey('rekam_medis', 'fk_rekam_pendaftaran', 'id_pendaftaran', 'pendaftaran', 'id_pendaftaran');
        $this->updateForeignKey('asuhan_medis', 'fk_asuhan_rekam', 'id_rekam_medis', 'rekam_medis', 'id_rekam_medis');
        $this->updateForeignKey('intervensi', 'fk_intervensi_rekam', 'id_rekam_medis', 'rekam_medis', 'id_rekam_medis');
        $this->updateForeignKey('implementasi', 'fk_implementasi_rekam', 'id_rekam_medis', 'rekam_medis', 'id_rekam_medis');
        $this->updateForeignKey('evaluasi', 'fk_evaluasi_rekam', 'id_rekam_medis', 'rekam_medis', 'id_rekam_medis');
    }

    private function updateForeignKey($table, $fkName, $column, $refTable, $refColumn)
    {
        try {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fkName}`");
        } catch (\Exception $e) {
            // Ignore if FK does not exist with that exact name
        }

        try {
            DB::statement("
                ALTER TABLE `{$table}` 
                ADD CONSTRAINT `{$fkName}` 
                FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}` (`{$refColumn}`) 
                ON DELETE CASCADE ON UPDATE CASCADE
            ");
        } catch (\Exception $e) {
            // Log or fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep cascade
    }
};
