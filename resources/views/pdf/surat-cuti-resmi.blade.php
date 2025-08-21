<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan dan Pemberian Cuti</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.2;
            margin: 0;
            padding: 15px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }
        .header h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0;
        }
        .nomor-section {
            margin: 15px 0;
        }
        .content-section {
            margin: 10px 0;
            border: 1px solid #000;
            padding: 10px;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        table.main-table td {
            padding: 3px 5px;
            vertical-align: top;
            border: none;
        }
        table.bordered td, table.bordered th {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }
        .label {
            width: 200px;
            font-weight: normal;
        }
        .colon {
            width: 20px;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 10px;
            font-size: 10px;
        }
        .signature-section {
            margin-top: 20px;
        }
        .signature-table {
            width: 100%;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .signature-box {
            height: 80px;
            border-bottom: 1px solid #000;
            margin: 40px 0 5px 0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .underline {
            text-decoration: underline;
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>PERMINTAAN DAN PEMBERIAN CUTI</h1>
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-section">
        <table class="main-table">
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td>{{ $nomor_surat ?? '800.1.11.4/___/' . date('Y') }}</td>
                <td class="text-right" style="width: 200px;">
                    {{ $tempat ?? 'Banjarmasin' }}, {{ $tanggal_surat ?? now()->format('d F Y') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN I: DATA PEGAWAI -->
    <div class="content-section">
        <div class="section-title">I. DATA PEGAWAI</div>
        <table class="main-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $pegawai->nama ?? '[Nama Lengkap]' }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td>{{ $pegawai->nip ?? '[NIP Pegawai]' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td>{{ $pegawai->jabatan ?? '[Jabatan]' }}</td>
            </tr>
            <tr>
                <td class="label">Masa kerja</td>
                <td class="colon">:</td>
                <td>{{ $masa_kerja ?? '[Tahun] Tahun [Bulan] Bulan' }}</td>
            </tr>
            <tr>
                <td class="label">Unit kerja</td>
                <td class="colon">:</td>
                <td>{{ $pegawai->unit_kerja ?? '[Unit Kerja]' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan/Ruang</td>
                <td class="colon">:</td>
                <td>{{ $golongan ?? '[Golongan/Ruang]' }}</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN II: JENIS CUTI -->
    <div class="content-section">
        <div class="section-title">II. JENIS CUTI YANG DIAMBIL</div>
        <table class="main-table">
            <tr>
                <td>
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti Tahunan' ? '✓' : '' }}</span>
                    Cuti Tahunan
                </td>
                <td>
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti Besar' ? '✓' : '' }}</span>
                    Cuti Besar
                </td>
            </tr>
            <tr>
                <td>
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti Sakit' ? '✓' : '' }}</span>
                    Cuti Sakit
                </td>
                <td>
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti Melahirkan' ? '✓' : '' }}</span>
                    Cuti Melahirkan
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti Alasan Penting' ? '✓' : '' }}</span>
                    Cuti Karena Alasan Penting
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="checkbox">{{ $jenis_cuti === 'Cuti di Luar Tanggungan Negara' ? '✓' : '' }}</span>
                    Cuti di Luar Tanggungan Negara
                </td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN III: ALASAN CUTI -->
    <div class="content-section">
        <div class="section-title">III. ALASAN CUTI</div>
        <p>{{ $alasan_cuti ?? '[Alasan cuti]' }}</p>
    </div>

    <!-- BAGIAN IV: LAMA CUTI -->
    <div class="content-section">
        <div class="section-title">IV. LAMANYA CUTI</div>
        <table class="main-table">
            <tr>
                <td class="label">Selama</td>
                <td class="colon">:</td>
                <td>{{ $lama_cuti ?? '[Jumlah Hari]' }} hari</td>
            </tr>
            <tr>
                <td class="label">Mulai tanggal</td>
                <td class="colon">:</td>
                <td>{{ $tanggal_mulai ?? '[Tanggal Mulai]' }}</td>
            </tr>
            <tr>
                <td class="label">Sampai dengan tanggal</td>
                <td class="colon">:</td>
                <td>{{ $tanggal_selesai ?? '[Tanggal Selesai]' }}</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN V: CATATAN CUTI -->
    <div class="content-section">
        <div class="section-title">V. CATATAN CUTI</div>
        <table class="bordered">
            <tr>
                <th rowspan="2">TAHUN</th>
                <th colspan="2">CUTI TAHUNAN</th>
                <th rowspan="2">KETERANGAN</th>
            </tr>
            <tr>
                <th>HAKI</th>
                <th>DIAMBIL</th>
            </tr>
            <tr>
                <td>N-2 ({{ date('Y') - 2 }})</td>
                <td>{{ $sisa_cuti_n_2 ?? 12 }}</td>
                <td>{{ $cuti_diambil_n_2 ?? 0 }}</td>
                <td>{{ $sisa_cuti_n_2 - $cuti_diambil_n_2 ?? 12 }}</td>
            </tr>
            <tr>
                <td>N-1 ({{ date('Y') - 1 }})</td>
                <td>{{ $sisa_cuti_n_1 ?? 12 }}</td>
                <td>{{ $cuti_diambil_n_1 ?? 0 }}</td>
                <td>{{ $sisa_cuti_n_1 - $cuti_diambil_n_1 ?? 12 }}</td>
            </tr>
            <tr>
                <td>N ({{ date('Y') }})</td>
                <td>{{ $sisa_cuti_n ?? 12 }}</td>
                <td>{{ $cuti_diambil_n ?? 0 }}</td>
                <td>{{ $sisa_cuti_n - $cuti_diambil_n ?? 12 }}</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN VI: ALAMAT SELAMA CUTI -->
    <div class="content-section">
        <div class="section-title">VI. ALAMAT SELAMA MENJALANKAN CUTI</div>
        <table class="main-table">
            <tr>
                <td class="label">Alamat lengkap</td>
                <td class="colon">:</td>
                <td>{{ $alamat_cuti ?? '[Alamat Lengkap]' }}</td>
            </tr>
            <tr>
                <td class="label">Telepon</td>
                <td class="colon">:</td>
                <td>{{ $telepon ?? '[Nomor Telepon]' }}</td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN VII: PERTIMBANGAN ATASAN LANGSUNG -->
    <div class="content-section">
        <div class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG</div>
        <table class="main-table">
            <tr>
                <td>
                    <span class="checkbox">{{ $pertimbangan_atasan === 'disetujui' ? '✓' : '' }}</span>
                    DISETUJUI
                </td>
                <td>
                    <span class="checkbox">{{ $pertimbangan_atasan === 'perubahan' ? '✓' : '' }}</span>
                    PERUBAHAN
                </td>
                <td>
                    <span class="checkbox">{{ $pertimbangan_atasan === 'ditangguhkan' ? '✓' : '' }}</span>
                    DITANGGUHKAN
                </td>
                <td>
                    <span class="checkbox">{{ $pertimbangan_atasan === 'tidak_disetujui' ? '✓' : '' }}</span>
                    TIDAK DISETUJUI
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 15px;">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="text-center">
                            <div>{{ $tempat ?? 'Banjarmasin' }}, {{ $tanggal_surat ?? now()->format('d F Y') }}</div>
                            <div style="margin: 5px 0;">PEMOHON</div>
                            @if($pegawai->tanda_tangan ?? false)
                                <img src="{{ storage_path('app/public/' . $pegawai->tanda_tangan) }}" 
                                     style="max-width: 80px; max-height: 40px; margin: 10px 0;">
                            @endif
                            <div class="signature-box"></div>
                            <div class="bold underline">{{ $pegawai->nama ?? '[Nama Pegawai]' }}</div>
                            <div>NIP. {{ $pegawai->nip ?? '[NIP Pegawai]' }}</div>
                        </div>
                    </td>
                    <td></td>
                    <td>
                        <div class="text-center">
                            <div>{{ $tempat ?? 'Banjarmasin' }}, _______________</div>
                            <div style="margin: 5px 0;">ATASAN LANGSUNG</div>
                            <div class="signature-box"></div>
                            <div class="bold underline">{{ $atasan_langsung ?? '[Nama Atasan]' }}</div>
                            <div>NIP. {{ $nip_atasan ?? '[NIP Atasan]' }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- BAGIAN VIII: KEPUTUSAN PEJABAT BERWENANG -->
    <div class="content-section">
        <div class="section-title">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</div>
        <table class="main-table">
            <tr>
                <td>
                    <span class="checkbox">{{ $keputusan_pejabat === 'disetujui' ? '✓' : '' }}</span>
                    DISETUJUI
                </td>
                <td>
                    <span class="checkbox">{{ $keputusan_pejabat === 'perubahan' ? '✓' : '' }}</span>
                    PERUBAHAN
                </td>
                <td>
                    <span class="checkbox">{{ $keputusan_pejabat === 'ditangguhkan' ? '✓' : '' }}</span>
                    DITANGGUHKAN
                </td>
                <td>
                    <span class="checkbox">{{ $keputusan_pejabat === 'tidak_disetujui' ? '✓' : '' }}</span>
                    TIDAK DISETUJUI
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 15px;">
            <div class="text-center" style="width: 50%; margin-left: auto;">
                <div>{{ $tempat ?? 'Banjarmasin' }}, _______________</div>
                <div style="margin: 5px 0;">PEJABAT YANG BERWENANG</div>
                @if($pejabat_cap ?? false)
                    <img src="{{ storage_path('app/public/' . $pejabat_cap) }}" 
                         style="max-width: 100px; max-height: 60px; margin: 10px 0;">
                @endif
                <div class="signature-box"></div>
                <div class="bold underline">{{ $pejabat_berwenang ?? '[Nama Pejabat]' }}</div>
                <div>NIP. {{ $nip_pejabat ?? '[NIP Pejabat]' }}</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div style="margin-top: 20px; font-size: 9pt; color: #666;">
        <hr style="border: 0.5px solid #ccc;">
        <div class="text-center">
            <p>Dokumen ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
            <p>Sistem Informasi Surat Cuti - {{ $instansi ?? 'Dinas Kesehatan Provinsi Kalimantan Selatan' }}</p>
        </div>
    </div>
</body>
</html>
