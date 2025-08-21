<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan dan Pemberian Cuti - {{ $nama_pegawai ?? 'Pegawai' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 2cm;
            color: #000;
            background: #fff;
        }
        
        .header-section {
            margin-bottom: 20px;
        }
        
        .header-right {
            text-align: right;
            margin-bottom: 15px;
        }
        
        .header-left {
            text-align: left;
            margin-bottom: 15px;
        }
        
        .title-center {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            font-size: 14pt;
        }
        
        .nomor-section {
            margin: 15px 0;
            text-align: center;
        }
        
        .content-section {
            margin: 15px 0;
            border: 1px solid #000;
            padding: 10px;
        }
        
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .data-row {
            display: table;
            width: 100%;
            margin: 5px 0;
        }
        
        .data-label {
            display: table-cell;
            width: 200px;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .data-colon {
            display: table-cell;
            width: 20px;
            vertical-align: top;
        }
        
        .data-value {
            display: table-cell;
            vertical-align: top;
        }
        
        .checkbox-section {
            margin: 10px 0;
        }
        
        .checkbox-row {
            margin: 5px 0;
            display: flex;
            align-items: center;
        }
        
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 8px;
            text-align: center;
            line-height: 13px;
            font-size: 10pt;
            vertical-align: middle;
        }
        
        .checkbox.checked {
            font-weight: bold;
        }
        
        .cuti-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        .cuti-table th, .cuti-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 11pt;
        }
        
        .cuti-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .signature-grid {
            display: table;
            width: 100%;
        }
        
        .signature-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            vertical-align: top;
        }
        
        .signature-box {
            margin: 40px 0 10px 0;
            height: 60px;
            border-bottom: 1px solid #000;
        }
        .signature-image {
            max-width: 120px;
            max-height: 70px;
            margin: 8px 0;
        }
        .cap-image {
            max-width: 90px;
            max-height: 90px;
            margin: 8px 0;
        }
        
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 5px;
        }
        
        .status-section {
            margin: 15px 0;
        }
        
        .status-options {
            display: flex;
            justify-content: space-around;
            margin: 10px 0;
        }
        
        .status-option {
            display: flex;
            align-items: center;
        }
        
        .text-justify {
            text-align: justify;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .underline {
            text-decoration: underline;
        }
        
        @page {
            margin: 2.5cm;
            size: A4;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header-section">
        <div class="header-right">
            {{ $tempat ?? 'Purworejo' }}, {{ $tanggal_surat ?? '5 Agustus 2025' }}
        </div>
        
        <div class="header-left">
            Kepada Yth.<br>
            Kepala Dinas Kesehatan Daerah<br>
            Kabupaten {{ $kabupaten ?? 'Purworejo' }}<br>
            di tempat
        </div>
    </div>

    <!-- TITLE -->
    <div class="title-center">
        PERMINTAAN DAN PEMBERIAN CUTI
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-section">
        Nomor: {{ $nomor_surat ?? '800.1.11.4/___/2025' }}
    </div>

    <!-- BAGIAN I: DATA PEGAWAI -->
    <div class="content-section">
        <div class="section-title">I. DATA PEGAWAI</div>
        
        <div class="data-row">
            <div class="data-label">Nama</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $nama_pegawai ?? 'Umi Setyawati, AMKg' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">NIP</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $nip_pegawai ?? '19870223 200902 2 004' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Jabatan</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $jabatan ?? 'Pengelola Kepegawaian' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Masa kerja</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $masa_kerja ?? '14 Tahun 06 Bulan' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Unit kerja</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $unit_kerja ?? 'Sub Bag Umum dan Kepegawaian Dinas Kesehatan Daerah Kabupaten Purworejo' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Golongan/Ruang</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $golongan ?? 'III/c' }}</div>
        </div>
    </div>

    <!-- BAGIAN II: JENIS CUTI -->
    <div class="content-section">
        <div class="section-title">II. JENIS CUTI YANG DIAMBIL</div>
        
        <div class="checkbox-section">
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? 'Cuti Tahunan') === 'Cuti Tahunan' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? 'Cuti Tahunan') === 'Cuti Tahunan' ? '✓' : '' }}
                </span>
                Cuti Tahunan
            </div>
            
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? '') === 'Cuti Besar' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? '') === 'Cuti Besar' ? '✓' : '' }}
                </span>
                Cuti Besar
            </div>
            
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? '') === 'Cuti Sakit' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? '') === 'Cuti Sakit' ? '✓' : '' }}
                </span>
                Cuti Sakit
            </div>
            
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? '') === 'Cuti Melahirkan' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? '') === 'Cuti Melahirkan' ? '✓' : '' }}
                </span>
                Cuti Melahirkan
            </div>
            
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? '') === 'Cuti Alasan Penting' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? '') === 'Cuti Alasan Penting' ? '✓' : '' }}
                </span>
                Cuti Karena Alasan Penting
            </div>
            
            <div class="checkbox-row">
                <span class="checkbox {{ ($jenis_cuti ?? '') === 'Cuti di Luar Tanggungan Negara' ? 'checked' : '' }}">
                    {{ ($jenis_cuti ?? '') === 'Cuti di Luar Tanggungan Negara' ? '✓' : '' }}
                </span>
                Cuti di Luar Tanggungan Negara
            </div>
        </div>
    </div>

    <!-- BAGIAN III: ALASAN CUTI -->
    <div class="content-section">
        <div class="section-title">III. ALASAN CUTI</div>
        <div class="text-justify">
            {{ $alasan_cuti ?? 'Kepentingan keluarga' }}
        </div>
    </div>

    <!-- BAGIAN IV: LAMANYA CUTI -->
    <div class="content-section">
        <div class="section-title">IV. LAMANYA CUTI</div>
        
        <div class="data-row">
            <div class="data-label">Selama</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $lama_cuti ?? '1' }} hari</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Mulai tanggal</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $tanggal_mulai ?? '6 Agustus 2025' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Sampai dengan tanggal</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $tanggal_selesai ?? '6 Agustus 2025' }}</div>
        </div>
    </div>

    <!-- BAGIAN V: CATATAN CUTI -->
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
                    <td>{{ $haki_2023 ?? '12' }}</td>
                    <td>{{ $diambil_2023 ?? '12' }}</td>
                    <td>{{ $keterangan_2023 ?? '0' }}</td>
                </tr>
                <tr>
                    <td>2024</td>
                    <td>{{ $haki_2024 ?? '12' }}</td>
                    <td>{{ $diambil_2024 ?? '9' }}</td>
                    <td>{{ $keterangan_2024 ?? '3' }}</td>
                </tr>
                <tr>
                    <td>2025</td>
                    <td>{{ $haki_2025 ?? '12' }}</td>
                    <td>{{ $diambil_2025 ?? '0' }}</td>
                    <td>{{ $keterangan_2025 ?? '12' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- BAGIAN VI: ALAMAT SELAMA CUTI -->
    <div class="content-section">
        <div class="section-title">VI. ALAMAT SELAMA MENJALANKAN CUTI</div>
        
        <div class="data-row">
            <div class="data-label">Alamat lengkap</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $alamat_cuti ?? 'Kledung Karangdalem, RT 3, RW 1, Kec Banyuurip, Kab Purworejo' }}</div>
        </div>
        
        <div class="data-row">
            <div class="data-label">Telepon</div>
            <div class="data-colon">:</div>
            <div class="data-value">{{ $telepon ?? '085292678023' }}</div>
        </div>
    </div>

    <!-- BAGIAN VII: PERTIMBANGAN ATASAN LANGSUNG -->
    <div class="content-section">
        <div class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG</div>
        
        <div class="status-options">
            <div class="status-option">
                <span class="checkbox {{ ($pertimbangan_atasan ?? '') === 'disetujui' ? 'checked' : '' }}">
                    {{ ($pertimbangan_atasan ?? '') === 'disetujui' ? '✓' : '' }}
                </span>
                DISETUJUI
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($pertimbangan_atasan ?? '') === 'perubahan' ? 'checked' : '' }}">
                    {{ ($pertimbangan_atasan ?? '') === 'perubahan' ? '✓' : '' }}
                </span>
                PERUBAHAN
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($pertimbangan_atasan ?? '') === 'ditangguhkan' ? 'checked' : '' }}">
                    {{ ($pertimbangan_atasan ?? '') === 'ditangguhkan' ? '✓' : '' }}
                </span>
                DITANGGUHKAN
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($pertimbangan_atasan ?? '') === 'tidak_disetujui' ? 'checked' : '' }}">
                    {{ ($pertimbangan_atasan ?? '') === 'tidak_disetujui' ? '✓' : '' }}
                </span>
                TIDAK DISETUJUI
            </div>
        </div>
        
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-cell">
                    <div>{{ $tempat ?? 'Purworejo' }}, {{ $tanggal_surat ?? '5 Agustus 2025' }}</div>
                    <div><strong>PEMOHON</strong></div>
                    @if(!empty($pemohon_signature_base64))
                        <div><img src="{{ $pemohon_signature_base64 }}" alt="Tanda Tangan Pemohon" class="signature-image"></div>
                    @endif
                    <div class="signature-box"></div>
                    <div class="signature-name">{{ $nama_pegawai ?? 'Umi Setyawati, AMKg' }}</div>
                    <div>NIP. {{ $nip_pegawai ?? '19870223 200902 2 004' }}</div>
                </div>
                
                <div class="signature-cell">
                    <!-- Kolom tengah kosong untuk spacing -->
                </div>
                
                <div class="signature-cell">
                    <div>{{ $tempat ?? 'Purworejo' }}, _______________</div>
                    <div><strong>ATASAN LANGSUNG</strong></div>
                    @if(!empty($atasan_cap_base64))
                        <div><img src="{{ $atasan_cap_base64 }}" alt="Cap" class="cap-image"></div>
                    @endif
                    @if(!empty($atasan_signature_base64))
                        <div><img src="{{ $atasan_signature_base64 }}" alt="Tanda Tangan Atasan" class="signature-image"></div>
                    @endif
                    <div class="signature-box"></div>
                    <div class="signature-name">{{ $atasan_langsung ?? 'Taufik Anggoro, S.IP' }}</div>
                    <div>NIP. {{ $nip_atasan ?? '19710404 199403 1 003' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN VIII: KEPUTUSAN PEJABAT BERWENANG -->
    <div class="content-section">
        <div class="section-title">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</div>
        
        <div class="status-options">
            <div class="status-option">
                <span class="checkbox {{ ($keputusan_pejabat ?? '') === 'disetujui' ? 'checked' : '' }}">
                    {{ ($keputusan_pejabat ?? '') === 'disetujui' ? '✓' : '' }}
                </span>
                DISETUJUI
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($keputusan_pejabat ?? '') === 'perubahan' ? 'checked' : '' }}">
                    {{ ($keputusan_pejabat ?? '') === 'perubahan' ? '✓' : '' }}
                </span>
                PERUBAHAN
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($keputusan_pejabat ?? '') === 'ditangguhkan' ? 'checked' : '' }}">
                    {{ ($keputusan_pejabat ?? '') === 'ditangguhkan' ? '✓' : '' }}
                </span>
                DITANGGUHKAN
            </div>
            <div class="status-option">
                <span class="checkbox {{ ($keputusan_pejabat ?? '') === 'tidak_disetujui' ? 'checked' : '' }}">
                    {{ ($keputusan_pejabat ?? '') === 'tidak_disetujui' ? '✓' : '' }}
                </span>
                TIDAK DISETUJUI
            </div>
        </div>
        
        <div class="signature-section">
            <div style="text-align: center; width: 50%; margin-left: auto;">
                <div>{{ $tempat ?? 'Purworejo' }}, _______________</div>
                <div><strong>PEJABAT YANG BERWENANG</strong></div>
                @if(!empty($pejabat_cap_base64))
                    <div><img src="{{ $pejabat_cap_base64 }}" alt="Cap" class="cap-image"></div>
                @endif
                @if(!empty($pejabat_signature_base64))
                    <div><img src="{{ $pejabat_signature_base64 }}" alt="Tanda Tangan Pejabat" class="signature-image"></div>
                @endif
                <div class="signature-box"></div>
                <div class="signature-name">{{ $pejabat_berwenang ?? 'dr. Sudarmi, MM' }}</div>
                <div>NIP. {{ $nip_pejabat ?? '19690220 200212 2 004' }}</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div style="margin-top: 30px; font-size: 10pt; color: #666; text-align: center;">
        <hr style="border: 0.5px solid #ccc; margin: 20px 0;">
        <p>Dokumen ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p>Sistem Informasi Kepegawaian - Dinas Kesehatan Daerah Kabupaten {{ $kabupaten ?? 'Purworejo' }}</p>
    </div>
</body>
</html>
