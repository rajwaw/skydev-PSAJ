<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $isSingleVisit ? 'Rangkuman_Rekam_Medis_' : 'Rekam_Medis_Lengkap_' }}{{ preg_replace('/[^A-Za-z0-9_-]/', '_', $pasien->nama_lengkap) }}_{{ $pasien->no_rm }}{{ $isSingleVisit && $riwayatKunjungan->first() ? '_' . \Carbon\Carbon::parse($riwayatKunjungan->first()->tgl_pemeriksaan)->format('Ymd') : '' }}</title>
    <style>
        @page {
            margin: 10mm 14mm 12mm 14mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }

        .kop-logo {
            width: 130px;
            text-align: left;
        }

        .kop-logo img {
            max-width: 125px;
            max-height: 60px;
            height: auto;
            display: block;
        }

        .kop-text {
            text-align: center;
            padding-right: 15px;
        }

        .kop-text h1 {
            font-size: 18px;
            font-weight: 800;
            color: #006C4C; /* Hijau Utama Mandalacare */
            letter-spacing: 0.5px;
            margin: 0 0 1px 0;
            text-transform: uppercase;
        }

        .kop-text .tagline {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
        }

        .kop-text .address {
            font-size: 9px;
            color: #475569;
            margin: 0 0 1px 0;
            line-height: 1.3;
        }

        .kop-text .contact {
            font-size: 9px;
            color: #334155;
            font-weight: 600;
            margin: 0;
        }

        /* Garis Pemisah Kop Surat Resmi */
        .kop-separator-thick {
            height: 2px;
            background-color: #006C4C;
            margin-top: 6px;
            margin-bottom: 1.5px;
        }

        .kop-separator-thin {
            height: 0.8px;
            background-color: #006C4C;
            margin-bottom: 10px;
        }

        /* Judul Dokumen */
        .doc-title-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .doc-title {
            font-size: 12.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
            display: inline-block;
            border-bottom: 1.5px solid #006C4C;
            padding-bottom: 2px;
            margin: 0 0 3px 0;
        }

        .doc-subtitle {
            font-size: 9.5px;
            color: #64748b;
            margin: 0;
        }

        /* Section Header Box */
        .section-box {
            background-color: #E6F4EA;
            border: 1px solid #C4E5CE;
            border-left: 4px solid #006C4C;
            padding: 4px 7px;
            font-weight: bold;
            font-size: 10.5px;
            color: #004d35;
            margin-top: 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Table Data Pasien */
        .table-pasien {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .table-pasien td {
            padding: 2.5px 5px;
            vertical-align: top;
        }

        .table-pasien .label {
            color: #64748b;
            width: 17%;
            font-weight: 500;
        }

        .table-pasien .colon {
            width: 2%;
            text-align: center;
            color: #64748b;
        }

        .table-pasien .value {
            color: #0f172a;
            font-weight: 600;
            width: 31%;
        }

        /* Badge Alergi */
        .badge-allergy {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 1px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9.5px;
            display: inline-block;
        }

        .badge-safe {
            color: #047857;
            font-weight: 600;
        }

        /* Card Kunjungan */
        .visit-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            margin-bottom: 8px;
            page-break-inside: avoid;
            background-color: #ffffff;
        }

        .visit-header {
            background-color: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 4px 8px;
        }

        .visit-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .visit-title {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
        }

        .visit-date {
            font-size: 9.5px;
            font-weight: 600;
            color: #006C4C;
            text-align: right;
        }

        .visit-body {
            padding: 6px 8px;
        }

        /* Grid / Sub-tables inside card */
        .sub-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 9.5px;
        }

        .sub-table td, .sub-table th {
            padding: 2px 4px;
            vertical-align: top;
        }

        .sub-header {
            font-weight: bold;
            font-size: 9.5px;
            color: #006C4C;
            margin-top: 4px;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 1px;
        }

        /* Vital Signs Box */
        .vital-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            margin: 2px 0 4px 0;
            font-size: 9px;
        }

        .vital-table td {
            padding: 3px 4px;
            text-align: center;
            border-right: 1px solid #e2e8f0;
            width: 12.5%;
        }

        .vital-table td:last-child {
            border-right: none;
        }

        .vital-table .v-label {
            color: #64748b;
            font-size: 8px;
            display: block;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .vital-table .v-val {
            font-weight: bold;
            color: #0f172a;
            font-size: 9.5px;
        }

        /* SOAP Box */
        .soap-container {
            background-color: #fafafa;
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            margin-top: 3px;
            border-radius: 3px;
        }

        .soap-row {
            margin-bottom: 2px;
            font-size: 9.5px;
        }

        .soap-badge {
            display: inline-block;
            width: 15px;
            height: 15px;
            line-height: 15px;
            text-align: center;
            background-color: #006C4C;
            color: #ffffff;
            font-weight: bold;
            font-size: 8.5px;
            border-radius: 2px;
            margin-right: 4px;
        }

        /* Footer & Tanda Tangan */
        .signature-wrapper {
            margin-top: 10px;
            page-break-inside: avoid;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sig-left {
            width: 55%;
            vertical-align: top;
            padding-right: 15px;
            font-size: 9px;
            color: #64748b;
        }

        .sig-notice-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 7px;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.35;
        }

        .sig-right {
            width: 45%;
            vertical-align: top;
            text-align: center;
        }

        .sig-city-date {
            font-size: 10px;
            color: #334155;
            margin-bottom: 2px;
        }

        .sig-title {
            font-size: 10px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .sig-image-container {
            height: 52px;
            margin: 1px auto;
            text-align: center;
        }

        .sig-image-container img {
            max-height: 48px;
            width: auto;
            display: inline-block;
        }

        .sig-name {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            display: inline-block;
            padding-bottom: 1px;
            margin-top: 2px;
        }

        .sig-sub {
            font-size: 9.5px;
            color: #475569;
            margin-top: 2px;
        }

        .watermark-footer {
            margin-top: 15px;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 5px;
            font-size: 8.5px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ======================================================== --}}
    {{-- KOP SURAT RESMI KLINIK MANDALACARE --}}
    {{-- ======================================================== --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Mandalacare">
                @else
                    <div style="font-size: 15px; font-weight: 800; color: #006C4C; border: 1.5px solid #006C4C; padding: 4px 6px; text-align: center; border-radius: 4px;">
                        MANDALACARE
                    </div>
                @endif
            </td>
            <td class="kop-text">
                <h1>KLINIK MANDALACARE</h1>
                <div class="tagline">Layanan Kesehatan & Rawat Jalan Profesional</div>
                <div class="address">
                    Jl. Menara, Gg. Puter, RT 10/RW 06, Kedungwringin, Kec. Patikraja, Kab. Banyumas, Jawa Tengah 53171
                </div>
                <div class="contact">
                    Telepon / WhatsApp: +62 881-8080-805 &bull; Email: mandalacare.official@gmail.com
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-separator-thick"></div>
    <div class="kop-separator-thin"></div>

    {{-- ======================================================== --}}
    {{-- JUDUL DOKUMEN --}}
    {{-- ======================================================== --}}
    <div class="doc-title-container">
        <div class="doc-title">
            {{ $isSingleVisit ? 'RANGKUMAN REKAM MEDIS & HASIL PEMERIKSAAN' : 'RINGKASAN REKAM MEDIS LENGKAP & KONSULTASI PASIEN' }}
        </div>
        <div class="doc-subtitle">
            Nomor Dokumen: RM/MC/{{ date('Y') }}/{{ str_pad($pasien->id_pasien, 4, '0', STR_PAD_LEFT) }} &bull; Tanggal Cetak: {{ $tglCetak }}
            @if($isSingleVisit && $riwayatKunjungan->first())
                &bull; Kunjungan: {{ \Carbon\Carbon::parse($riwayatKunjungan->first()->tgl_pemeriksaan)->translatedFormat('d F Y, H:i') }} WIB
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- BAGIAN I: DATA IDENTITAS PASIEN --}}
    {{-- ======================================================== --}}
    <div class="section-box">
        I. Data Identitas Pasien
    </div>

    <table class="table-pasien">
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->nama_lengkap }}</td>

            <td class="label">No. Rekam Medis</td>
            <td class="colon">:</td>
            <td class="value" style="color: #006C4C;">{{ $pasien->no_rm }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->nik ?: '-' }}</td>

            <td class="label">Jenis Kelamin / Umur</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->formatted_jk }} / {{ $pasien->age }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->formatted_tgl_lahir ?: '-' }}</td>

            <td class="label">Golongan Darah</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->golongan_darah ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Telepon / HP</td>
            <td class="colon">:</td>
            <td class="value">{{ $pasien->no_telp ?: '-' }}</td>

            <td class="label">Riwayat Alergi Obat</td>
            <td class="colon">:</td>
            <td class="value">
                @if(!empty($alergiText) && $alergiText !== 'Tidak Ada')
                    <span class="badge-allergy">Peringatan: {{ $alergiText }}</span>
                @else
                    <span class="badge-safe">Tidak Ada Alergi</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Alamat Lengkap</td>
            <td class="colon">:</td>
            <td class="value" colspan="4">{{ $pasien->alamat ?: '-' }}</td>
        </tr>
    </table>

    {{-- ======================================================== --}}
    {{-- BAGIAN II: RIWAYAT KONSULTASI & PEMERIKSAAN MEDIS --}}
    {{-- ======================================================== --}}
    <div class="section-box">
        II. {{ $isSingleVisit ? 'Rangkuman Hasil Pemeriksaan & Rekam Medis (Kunjungan Terpilih)' : 'Riwayat Catatan Medis & Konsultasi (Semua Kunjungan)' }}
    </div>

    @if($riwayatKunjungan->isEmpty())
        <div style="text-align: center; padding: 20px; color: #64748b; font-style: italic; border: 1px dashed #cbd5e1; border-radius: 4px;">
            Belum ada catatan riwayat konsultasi atau pemeriksaan medis yang tersimpan untuk pasien ini.
        </div>
    @else
        @foreach($riwayatKunjungan as $idx => $rm)
            @php
                $asuhan = $rm->asuhanMedis;
                $intervensiList = $rm->intervensi;
                $firstIntervensi = $intervensiList->first();
                $diagnosa = $firstIntervensi ? $firstIntervensi->diagnosa_awal : ($asuhan ? $asuhan->keluhan_utama : '-');
                $prioritas = $intervensiList->pluck('prioritas_diagnosa')->filter()->first();
                $planList = $intervensiList->pluck('rencana_tindakan')->filter()->values();
                $implementasi = $rm->implementasi;
                $evaluasi = $rm->evaluasi;
            @endphp

            <div class="visit-card">
                {{-- Header Kunjungan --}}
                <div class="visit-header">
                    <table>
                        <tr>
                            <td class="visit-title">
                                @if($isSingleVisit)
                                    Rangkuman Pemeriksaan Pasien &bull; No. Pendaftaran: {{ $rm->pendaftaran ? $rm->pendaftaran->no_antrian ?? 'P-'.$rm->id_pendaftaran : 'Kunjungan Langsung' }}
                                @else
                                    Pemeriksaan #{{ $idx + 1 }} &bull; No. Pendaftaran: {{ $rm->pendaftaran ? $rm->pendaftaran->no_antrian ?? 'P-'.$rm->id_pendaftaran : 'Kunjungan Langsung' }}
                                @endif
                            </td>
                            <td class="visit-date">
                                {{ \Carbon\Carbon::parse($rm->tgl_pemeriksaan)->translatedFormat('l, d F Y - H:i') }} WIB
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="visit-body">
                    {{-- Anamnesis / Keluhan --}}
                    <table class="sub-table">
                        <tr>
                            <td style="width: 18%; font-weight: bold; color: #475569;">Keluhan Utama</td>
                            <td style="width: 2%;">:</td>
                            <td style="font-weight: 600; color: #0f172a;">
                                {{ $asuhan && $asuhan->keluhan_utama ? $asuhan->keluhan_utama : 'Pemeriksaan rutin / tidak ada keluhan khusus' }}
                            </td>
                        </tr>
                        @if($asuhan && $asuhan->riwayat_keluhan)
                            <tr>
                                <td style="font-weight: bold; color: #475569;">Riwayat Penyakit</td>
                                <td>:</td>
                                <td style="color: #334155;">{{ $asuhan->riwayat_keluhan }}</td>
                            </tr>
                        @endif
                        @if($asuhan && ($asuhan->kondisi_umum || $asuhan->kesadaran))
                            <tr>
                                <td style="font-weight: bold; color: #475569;">Kondisi &amp; Kesadaran</td>
                                <td>:</td>
                                <td style="color: #334155;">
                                    @if($asuhan->kondisi_umum) Kondisi: {{ $asuhan->kondisi_umum }} @endif
                                    @if($asuhan->kondisi_umum && $asuhan->kesadaran) &bull; @endif
                                    @if($asuhan->kesadaran) Kesadaran: {{ $asuhan->kesadaran }} @endif
                                </td>
                            </tr>
                        @endif
                    </table>

                    {{-- Tanda-Tanda Vital (TTV) --}}
                    <div class="sub-header">Tanda-Tanda Vital & Pengukuran Fisik</div>
                    <table class="vital-table">
                        <tr>
                            <td>
                                <span class="v-label">Tekanan Darah</span>
                                <span class="v-val">{{ $asuhan && $asuhan->tekanan_darah ? $asuhan->tekanan_darah : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Suhu Tubuh</span>
                                <span class="v-val">{{ $asuhan && $asuhan->suhu_tubuh ? $asuhan->suhu_tubuh . ' °C' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Denyut Nadi</span>
                                <span class="v-val">{{ $asuhan && $asuhan->nadi ? $asuhan->nadi . ' x/m' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Pernapasan (RR)</span>
                                <span class="v-val">{{ $asuhan && $asuhan->rr ? $asuhan->rr . ' x/m' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">SpO2</span>
                                <span class="v-val">{{ $asuhan && $asuhan->spo2 ? $asuhan->spo2 . ' %' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Tinggi Badan</span>
                                <span class="v-val">{{ $asuhan && $asuhan->tinggi_badan ? $asuhan->tinggi_badan . ' cm' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Berat Badan</span>
                                <span class="v-val">{{ $asuhan && $asuhan->berat_badan ? $asuhan->berat_badan . ' kg' : '-' }}</span>
                            </td>
                            <td>
                                <span class="v-label">Indeks IMT</span>
                                <span class="v-val">{{ $asuhan && $asuhan->imt ? $asuhan->imt : '-' }}</span>
                            </td>
                        </tr>
                    </table>

                    {{-- Diagnosis & Rencana Tindakan --}}
                    <table class="sub-table" style="margin-top: 4px;">
                        <tr>
                            <td style="width: 50%;">
                                <div class="sub-header">Diagnosis Keperawatan</div>
                                <div style="font-weight: 600; color: #0f172a; padding: 2px 0;">
                                    {{ $diagnosa }}
                                </div>
                                @if($prioritas)
                                    <div style="font-size: 9px; color: #854d0e; background-color: #fef9c3; border: 1px solid #fef08a; padding: 3px 6px; border-radius: 3px; margin-top: 3px;">
                                        <strong>Prioritas:</strong> {{ $prioritas }}
                                    </div>
                                @endif
                            </td>
                            <td style="width: 50%;">
                                <div class="sub-header">Rencana Tindakan (Intervensi)</div>
                                @if($planList->isNotEmpty())
                                    <ul style="margin: 2px 0; padding-left: 15px; color: #0f172a;">
                                        @foreach($planList as $plan)
                                            <li>{{ $plan }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div style="color: #64748b; font-style: italic;">Observasi berkala dan edukasi istirahat yang cukup.</div>
                                @endif
                            </td>
                        </tr>
                    </table>

                    {{-- Tindakan & Terapi Resep Obat --}}
                    @if($implementasi)
                        <div class="sub-header" style="margin-top: 4px;">Tindakan Yang Dilakukan &amp; Implementasi Keperawatan</div>
                        <table class="sub-table">
                            <tr>
                                <td style="width: 18%; font-weight: bold; color: #475569;">Tindakan Perawat</td>
                                <td style="width: 2%;">:</td>
                                <td style="font-weight: 600; color: #0f172a;">{{ $implementasi->tindakan_dilakukan }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; color: #475569;">Resep Terapi Obat</td>
                                <td>:</td>
                                <td style="font-weight: 600; color: #006C4C;">{{ $implementasi->resep_obat ?: '-' }}</td>
                            </tr>
                        </table>
                    @endif

                    {{-- Evaluasi Perkembangan Pasien (SOAP) --}}
                    @if($evaluasi)
                        <div class="sub-header" style="margin-top: 4px;">
                            Evaluasi Catatan Perkembangan (SOAP)
                            <span style="float: right; font-size: 9px; font-weight: normal; color: #475569;">
                                Status: <strong>{{ $evaluasi->status_kondisi }}</strong> &bull; {{ $evaluasi->status_evaluasi }}
                            </span>
                        </div>
                        <div class="soap-container">
                            <div class="soap-row">
                                <span class="soap-badge">S</span>
                                <strong>Subjektif:</strong> {{ $evaluasi->soap_s ?: ($evaluasi->keluhan_setelah_tindakan ?: '-') }}
                            </div>
                            <div class="soap-row">
                                <span class="soap-badge">O</span>
                                <strong>Objektif:</strong> {{ $evaluasi->soap_o ?: ($evaluasi->respon_pasien ?: '-') }}
                            </div>
                            <div class="soap-row">
                                <span class="soap-badge">A</span>
                                <strong>Asesmen:</strong> {{ $evaluasi->soap_a ?: ($evaluasi->hasil_evaluasi ?: '-') }}
                            </div>
                            <div class="soap-row" style="margin-bottom: 0;">
                                <span class="soap-badge">P</span>
                                <strong>Planning:</strong> {{ $evaluasi->soap_p ?: ($evaluasi->rencana_selanjutnya ?: '-') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    {{-- ======================================================== --}}
    {{-- TANDA TANGAN & PENGESAHAN DOKUMEN (KANAN BAWAH) --}}
    {{-- ======================================================== --}}
    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <td class="sig-left">
                    <div class="sig-notice-box">
                        <strong>Perhatian / Petunjuk Pasien:</strong><br>
                        1. Dokumen ini adalah lembar ringkasan rekam medis dan konsultasi resmi dari <strong>Klinik Mandalacare</strong>.<br>
                        2. Simpan dokumen ini dengan baik dan bawa kembali saat kontrol ulang atau rujukan kesehatan.<br>
                        3. Patuhi aturan minum obat dan petunjuk perawatan yang telah dianjurkan oleh tenaga kesehatan.
                    </div>
                </td>
                <td class="sig-right">
                    <div class="sig-city-date">Banyumas, {{ $tglCetak }}</div>
                    <div class="sig-title">Penanggung Jawab Pelayanan / Perawat Pemeriksa,</div>

                    <div class="sig-image-container">
                        @if(!empty($ttdBase64))
                            <img src="{{ $ttdBase64 }}" alt="Tanda Tangan Yudha Tama Mandala Putra S. Kep., Ns">
                        @else
                            <div style="height: 50px;"></div>
                        @endif
                    </div>

                    <div class="sig-name">Yudha Tama Mandala Putra S. Kep., Ns</div>
                    <div class="sig-sub">Penanggung Jawab Klinis Mandalacare</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Watermark / Footer --}}
    <div class="watermark-footer">
        Dicetak secara otomatis melalui Sistem Informasi Manajemen Klinik Mandalacare &bull; Waktu Cetak: {{ date('d/m/Y H:i:s') }} WIB
    </div>

</body>
</html>
