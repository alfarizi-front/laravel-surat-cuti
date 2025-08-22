<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan dan Pemberian Cuti - ASN</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 2cm;
            color: #000;
            background: #fff;
            text-align: justify;
        }

        .header-section { margin-bottom: 20px; }
        .header-right { text-align: right; margin-bottom: 15px; }
        .header-left { text-align: left; margin-bottom: 15px; }
        .title-center { text-align: center; font-weight: bold; text-decoration: underline; margin: 20px 0; font-size: 14pt; }
        .nomor-section { margin: 15px 0; text-align: center; }

        .content-section { margin: 15px 0; border: 1px solid #000; padding: 10px; }
        .section-title { font-weight: bold; text-decoration: underline; margin-bottom: 10px; text-align: center; }

        .data-row { display: table; width: 100%; margin: 5px 0; }
        .data-label { display: table-cell; width: 200px; vertical-align: top; padding-right: 10px; }
        .data-colon { display: table-cell; width: 20px; vertical-align: top; }
        .data-value { display: table-cell; vertical-align: top; }

        .checkbox-section { margin: 10px 0; }
        .checkbox-row { margin: 5px 0; display: flex; align-items: center; }
        .checkbox { display: inline-block; width: 15px; height: 15px; border: 1px solid #000; margin-right: 8px; text-align: center; line-height: 13px; font-size: 10pt; vertical-align: middle; font-family: 'DejaVu Sans', sans-serif; font-weight: bold; }
        .checkbox.checked { font-weight: bold; }

        .cuti-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .cuti-table th, .cuti-table td { border: 1px solid #000; padding: 5px; text-align: center; font-size: 11pt; }
        .cuti-table th { background-color: #f0f0f0; font-weight: bold; }

        .signature-section { margin-top: 30px; page-break-inside: avoid; }
        .signature-grid { display: table; width: 100%; }
        .signature-cell { display: table-cell; width: 33.33%; text-align: center; padding: 10px; vertical-align: top; }
        .signature-box { margin: 40px 0 10px 0; height: 60px; border-bottom: 1px solid #000; }
        .signature-name { font-weight: bold; text-decoration: underline; margin-top: 5px; }

        .status-options { display: flex; justify-content: space-around; margin: 10px 0; flex-wrap: wrap; }
        .status-option { display: flex; align-items: center; margin: 5px; }

        @page { margin: 2.5cm; size: A4; }
        @media print { 
            body { margin: 0; padding: 0; }
            .status-options { display: block; }
            .status-option { display: block; margin: 5px 0; }
        }
    </style>
</head>
<body>
@php
    // Sumber data robust untuk kompatibilitas berbagai controller
    $tempat = $tempat ?? ($tempat_surat ?? 'Purworejo');
    $tanggalSurat = $tanggal_surat ?? ($tanggal_cetak ?? now()->format('d F Y'));

    $nomorSurat = $nomor_surat ?? ('800.1.11.4/___/' . (isset($tahun_surat) ? $tahun_surat : now()->format('Y')));

    $namaPegawai = $nama_pegawai ?? ($pegawai->nama ?? '[Nama Lengkap]');
    $nipPegawai = $nip_pegawai ?? ($pegawai->nip ?? '[NIP Pegawai]');
    $jabatan = $jabatan ?? ($pegawai->jabatan ?? '[Jabatan]');
    $masaKerja = $masa_kerja ?? ($pegawai->masa_kerja ?? '[Tahun] Tahun [Bulan] Bulan');
    $unitKerja = $unit_kerja ?? ($pegawai->unit_kerja ?? '[Unit Kerja Lengkap]');
    $golongan = $golongan ?? ($pegawai->golongan ?? '[Golongan/Ruang]');

    $jenisCuti = $jenis_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->jenisCuti)) ? $surat_cuti->jenisCuti->nama : null);
    $alasanCuti = $alasan_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->alasan)) ? $surat_cuti->alasan : '[Alasan]');
    $lamaCuti = $lama_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->jumlah_hari)) ? $surat_cuti->jumlah_hari : '[Jumlah Hari]');

    $tanggalMulai = $tanggal_mulai ?? ((isset($surat_cuti) && isset($surat_cuti->tanggal_awal)) ? (\Carbon\Carbon::parse($surat_cuti->tanggal_awal)->translatedFormat('d F Y')) : '[Tanggal Mulai]');
    $tanggalSelesai = $tanggal_selesai ?? ((isset($surat_cuti) && isset($surat_cuti->tanggal_akhir)) ? (\Carbon\Carbon::parse($surat_cuti->tanggal_akhir)->translatedFormat('d F Y')) : '[Tanggal Selesai]');

    $haki_2023 = $haki_2023 ?? 12; 
    $diambil_2023 = $diambil_2023 ?? 0; 
    $ket_2023 = $keterangan_2023 ?? ($haki_2023 - $diambil_2023);
    
    $haki_2024 = $haki_2024 ?? 12; 
    $diambil_2024 = $diambil_2024 ?? 0; 
    $ket_2024 = $keterangan_2024 ?? ($haki_2024 - $diambil_2024);
    
    $haki_2025 = $haki_2025 ?? 12; 
    $diambil_2025 = $diambil_2025 ?? 0; 
    $ket_2025 = $keterangan_2025 ?? ($haki_2025 - $diambil_2025);

    $alamatCuti = $alamat_cuti ?? '[Alamat Lengkap]';
    $telepon = $telepon ?? ($telp ?? '[Nomor Telepon]');

    $atasanNama = $atasan_langsung ?? '[Nama Atasan]';
    $atasanNip = $nip_atasan ?? '[NIP Atasan]';

    $pejabatNama = $pejabat_berwenang ?? '[Nama Pejabat]';
    $pejabatNip = $nip_pejabat ?? '[NIP Pejabat]';

    $pertimbanganStatus = strtolower($pertimbangan_atasan ?? '');
    $keputusanStatus = strtolower($keputusan_pejabat ?? '');
@endphp

    <!-- HEADER -->
    <div class="header-section">
        <div class="header-right">
            {{ $tempat }}, {{ $tanggalSurat }}
        </div>
        <div class="header-left">
            Kepada Yth.<br>
            Kepala Dinas Kesehatan Daerah<br>
            Kabupaten {{ $kabupaten ?? 'Purworejo' }}<br>
            di tempat
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-center">PERMINTAAN DAN PEMBERIAN CUTI</div>

    <!-- NOMOR SURAT -->
    <div class="nomor-section">Nomor: {{ $nomorSurat }}</div>

    <!-- I. DATA PEGAWAI -->
    <div class="content-section">
        <div class="section-title">I. DATA PEGAWAI</div>
        <div class="data-row">
            <div class="data-label">Nama</div><div class="data-colon">:</div><div class="data-value">{{ $namaPegawai }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">NIP</div><div class="data-colon">:</div><div class="data-value">{{ $nipPegawai }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Jabatan</div><div class="data-colon">:</div><div class="data-value">{{ $jabatan }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Masa kerja</div><div class="data-colon">:</div><div class="data-value">{{ $masaKerja }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Unit kerja</div><div class="data-colon">:</div><div class="data-value">{{ $unitKerja }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Golongan/Ruang</div><div class="data-colon">:</div><div class="data-value">{{ $golongan }}</div>
        </div>
    </div>

    <!-- II. JENIS CUTI -->
    <div class="content-section">
        <div class="section-title">II. JENIS CUTI YANG DIAMBIL</div>
        <div class="checkbox-section">
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti Tahunan') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti Tahunan' ? '✓' : '' }}</span>
                Cuti Tahunan
            </div>
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti Besar') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti Besar' ? '✓' : '' }}</span>
                Cuti Besar
            </div>
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti Sakit') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti Sakit' ? '✓' : '' }}</span>
                Cuti Sakit
            </div>
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti Melahirkan') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti Melahirkan' ? '✓' : '' }}</span>
                Cuti Melahirkan
            </div>
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti Alasan Penting') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti Alasan Penting' ? '✓' : '' }}</span>
                Cuti Karena Alasan Penting
            </div>
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenisCuti === 'Cuti di Luar Tanggungan Negara') ? 'checked' : '' }}">{{ $jenisCuti === 'Cuti di Luar Tanggungan Negara' ? '✓' : '' }}</span>
                Cuti di Luar Tanggungan Negara
            </div>
        </div>
    </div>

    <!-- III. ALASAN CUTI -->
    <div class="content-section">
        <div class="section-title">III. ALASAN CUTI</div>
        <div>{{ $alasanCuti }}</div>
    </div>

    <!-- IV. LAMANYA CUTI -->
    <div class="content-section">
        <div class="section-title">IV. LAMANYA CUTI</div>
        <div class="data-row">
            <div class="data-label">Selama</div><div class="data-colon">:</div><div class="data-value">{{ $lamaCuti }} hari</div>
        </div>
        <div class="data-row">
            <div class="data-label">Mulai tanggal</div><div class="data-colon">:</div><div class="data-value">{{ $tanggalMulai }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Sampai dengan tanggal</div><div class="data-colon">:</div><div class="data-value">{{ $tanggalSelesai }}</div>
        </div>
    </div>

    <!-- V. CATATAN CUTI -->
    <div class="content-section">
        <div class="section-title">V. CATATAN CUTI</div>
        <table class="cuti-table">
            <thead>
                <tr>
                    <th rowspan="2">TAHUN</th>
                    <th colspan="2">CUTI TAHUNAN</th>
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th>HAKI</th>
                    <th>DIAMBIL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2023</td>
                    <td>{{ $haki_2023 }}</td>
                    <td>{{ $diambil_2023 }}</td>
                    <td>{{ $ket_2023 }}</td>
                </tr>
                <tr>
                    <td>2024</td>
                    <td>{{ $haki_2024 }}</td>
                    <td>{{ $diambil_2024 }}</td>
                    <td>{{ $ket_2024 }}</td>
                </tr>
                <tr>
                    <td>2025</td>
                    <td>{{ $haki_2025 }}</td>
                    <td>{{ $diambil_2025 }}</td>
                    <td>{{ $ket_2025 }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- VI. ALAMAT SELAMA MENJALANKAN CUTI -->
    <div class="content-section">
        <div class="section-title">VI. ALAMAT SELAMA MENJALANKAN CUTI</div>
        <div class="data-row">
            <div class="data-label">Alamat lengkap</div><div class="data-colon">:</div><div class="data-value">{{ $alamatCuti }}</div>
        </div>
        <div class="data-row">
            <div class="data-label">Telepon</div><div class="data-colon">:</div><div class="data-value">{{ $telepon }}</div>
        </div>
    </div>

    <!-- VII. PERTIMBANGAN ATASAN LANGSUNG -->
    <div class="content-section">
        <div class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG</div>
        <div class="status-options">
            <div class="status-option"><span class="checkbox {{ $pertimbanganStatus === 'disetujui' ? 'checked' : '' }}">{{ $pertimbanganStatus === 'disetujui' ? '✓' : '' }}</span> DISETUJUI</div>
            <div class="status-option"><span class="checkbox {{ $pertimbanganStatus === 'perubahan' ? 'checked' : '' }}">{{ $pertimbanganStatus === 'perubahan' ? '✓' : '' }}</span> PERUBAHAN</div>
            <div class="status-option"><span class="checkbox {{ $pertimbanganStatus === 'ditangguhkan' ? 'checked' : '' }}">{{ $pertimbanganStatus === 'ditangguhkan' ? '✓' : '' }}</span> DITANGGUHKAN</div>
            <div class="status-option"><span class="checkbox {{ $pertimbanganStatus === 'tidak_disetujui' ? 'checked' : '' }}">{{ $pertimbanganStatus === 'tidak_disetujui' ? '✓' : '' }}</span> TIDAK DISETUJUI</div>
        </div>

        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-cell">
                    <div>{{ $tempat }}, {{ $tanggalSurat }}</div>
                    <div><strong>PEMOHON</strong></div>
                    @if(isset($pegawai) && !empty($pegawai->tanda_tangan))
                        <div style="margin-top:10px;">
                            <img src="{{ storage_path('app/public/' . $pegawai->tanda_tangan) }}" style="max-width: 100px; max-height: 60px;" alt="Tanda Tangan Pemohon">
                        </div>
                    @endif
                    <div class="signature-box"></div>
                    <div class="signature-name">{{ $namaPegawai }}</div>
                    <div>NIP. {{ $nipPegawai }}</div>
                </div>
                <div class="signature-cell"></div>
                <div class="signature-cell">
                    <div>{{ $tempat }}, _______________</div>
                    <div><strong>ATASAN LANGSUNG</strong></div>
                    <div class="signature-box"></div>
                    <div class="signature-name">{{ $atasanNama }}</div>
                    <div>NIP. {{ $atasanNip }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI -->
    <div class="content-section">
        <div class="section-title">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</div>
        <div class="status-options">
            <div class="status-option"><span class="checkbox {{ $keputusanStatus === 'disetujui' ? 'checked' : '' }}">{{ $keputusanStatus === 'disetujui' ? '✓' : '' }}</span> DISETUJUI</div>
            <div class="status-option"><span class="checkbox {{ $keputusanStatus === 'perubahan' ? 'checked' : '' }}">{{ $keputusanStatus === 'perubahan' ? '✓' : '' }}</span> PERUBAHAN</div>
            <div class="status-option"><span class="checkbox {{ $keputusanStatus === 'ditangguhkan' ? 'checked' : '' }}">{{ $keputusanStatus === 'ditangguhkan' ? '✓' : '' }}</span> DITANGGUHKAN</div>
            <div class="status-option"><span class="checkbox {{ $keputusanStatus === 'tidak_disetujui' ? 'checked' : '' }}">{{ $keputusanStatus === 'tidak_disetujui' ? '✓' : '' }}</span> TIDAK DISETUJUI</div>
        </div>

        <div class="signature-section">
            <div style="text-align: center; width: 50%; margin-left: auto;">
                <div>{{ $tempat }}, _______________</div>
                <div><strong>PEJABAT YANG BERWENANG</strong></div>
                @if(isset($pejabat_tanda_tangan) && $pejabat_tanda_tangan)
                    <div style="margin-top:10px;">
                        <img src="{{ storage_path('app/public/' . $pejabat_tanda_tangan) }}" style="max-width: 120px; max-height: 70px;" alt="Tanda Tangan Pejabat">
                    </div>
                @endif
                <div class="signature-box"></div>
                <div class="signature-name">{{ $pejabatNama }}</div>
                <div>NIP. {{ $pejabatNip }}</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div style="margin-top: 30px; font-size: 10pt; color: #666; text-align: center;">
        <hr style="border: 0.5px solid #ccc; margin: 20px 0;">
        <p>Dokumen ini dicetak pada {{ ($tanggal_cetak ?? now()->format('d F Y, H:i')) }} WIB</p>
        <p>Sistem Informasi Surat Cuti - Dinas Kesehatan</p>
    </div>
</body>
</html>