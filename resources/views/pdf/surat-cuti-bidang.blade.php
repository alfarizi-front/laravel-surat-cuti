<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti Bidang</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 12px;
            margin: 5px 0;
            color: #000;
        }

        .document-info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .main-table td {
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #000;
        }

        .main-table .label {
            width: 25%;
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .section-title {
            font-weight: bold;
            margin: 15px 0 8px 0;
            font-size: 12px;
            color: #000;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        .simple-info {
            margin: 10px 0;
            padding: 8px;
            background-color: #f9f9f9;
            border-left: 3px solid #000;
        }

        .signature-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signature-table td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
            vertical-align: middle;
        }

        .signature-table th {
            background-color: #000;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }

        .paraf-cell {
            text-align: center !important;
            width: 80px;
            min-height: 40px;
            padding: 8px;
            vertical-align: middle;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 13px;
            font-weight: bold;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }


        @page {
            margin: 2cm;
            size: A4;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <!-- HEADER SIMPLE -->
    <div class="header">
        <h1>Surat Permintaan Cuti</h1>
        <div class="subtitle">{{ $nama_bidang ?? $suratCuti->pengaju->unit_kerja }}</div>
        <div class="subtitle">Dinas Kesehatan Kabupaten {{ $kabupaten ?? 'Purworejo' }}</div>
    </div>

    <!-- NOMOR SURAT -->
    <div class="document-info">
        <strong>Nomor:</strong> {{ $nomor_surat ?? 'BDG/800/' . date('Y') . '/' . str_pad($suratCuti->id, 4, '0', STR_PAD_LEFT) }}<br>
        <strong>Tanggal:</strong> {{ $tanggal_surat ?? now()->format('d F Y') }}
    </div>

    <!-- DATA PEGAWAI -->
    <div class="section-title">I. Data Pegawai</div>
    <table class="main-table">
        <tr>
            <td class="label">Nama</td>
            <td>{{ $suratCuti->pengaju->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>{{ $suratCuti->pengaju->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>{{ $suratCuti->pengaju->jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td>{{ $suratCuti->pengaju->unit_kerja }}</td>
        </tr>
    </table>

    <!-- JENIS CUTI YANG DIAMBIL -->
    <div class="section-title">II. JENIS CUTI YANG DIAMBIL</div>
    <div style="text-align: center; margin-bottom: 10px; font-style: italic;">
        (ceklis yang dipilih pemohon)
    </div>
    <table class="main-table">
        <tr>
            <td style="width: 5%;">1.</td>
            <td style="width: 40%;">
                @if(stripos($suratCuti->jenisCuti->nama, 'Tahunan') !== false)
                    <strong>[X]</strong> Cuti Tahunan
                @else
                    [ ] Cuti Tahunan
                @endif
            </td>
            <td style="width: 5%;">2.</td>
            <td style="width: 50%;">
                @if(stripos($suratCuti->jenisCuti->nama, 'Besar') !== false)
                    <strong>[X]</strong> Cuti Besar
                @else
                    [ ] Cuti Besar
                @endif
            </td>
        </tr>
        <tr>
            <td>3.</td>
            <td>
                @if(stripos($suratCuti->jenisCuti->nama, 'Sakit') !== false)
                    <strong>[X]</strong> Cuti Sakit
                @else
                    [ ] Cuti Sakit
                @endif
            </td>
            <td>4.</td>
            <td>
                @if(stripos($suratCuti->jenisCuti->nama, 'Melahirkan') !== false)
                    <strong>[X]</strong> Cuti Melahirkan
                @else
                    [ ] Cuti Melahirkan
                @endif
            </td>
        </tr>
        <tr>
            <td>5.</td>
            <td>
                @if(stripos($suratCuti->jenisCuti->nama, 'Alasan Penting') !== false)
                    <strong>[X]</strong> Cuti Alasan Penting
                @else
                    [ ] Cuti Alasan Penting
                @endif
            </td>
            <td>6.</td>
            <td>
                @if(stripos($suratCuti->jenisCuti->nama, 'Luar Tanggungan') !== false)
                    <strong>[X]</strong> Cuti di Luar Tanggungan Negara
                @else
                    [ ] Cuti di Luar Tanggungan Negara
                @endif
            </td>
        </tr>
    </table>

    <!-- DETAIL CUTI -->
    <div class="section-title">III. Detail Permohonan Cuti</div>
    <table class="main-table">
        <tr>
            <td class="label">Jenis Cuti</td>
            <td><strong>{{ $suratCuti->jenisCuti->nama }}</strong></td>
        </tr>
        <tr>
            <td class="label">Alasan</td>
            <td>{{ $suratCuti->alasan }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Mulai</td>
            <td>{{ $suratCuti->tanggal_awal->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Selesai</td>
            <td>{{ $suratCuti->tanggal_akhir->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Hari</td>
            <td><strong>{{ $suratCuti->jumlah_hari }} hari kerja</strong></td>
        </tr>
        <tr>
            <td class="label">Tanggal Pengajuan</td>
            <td>{{ $suratCuti->tanggal_ajuan->format('d F Y') }}</td>
        </tr>
    </table>

    <!-- INFO TAMBAHAN -->
    <div class="simple-info">
        <strong>Catatan:</strong> Selama cuti, tugas dan tanggung jawab akan didelegasikan kepada rekan kerja di bidang yang sama dengan koordinasi Kepala Bidang.
    </div>

    <!-- PERSETUJUAN -->
    <div class="section-title">III. Persetujuan</div>

    @php
        $parafList = $disposisiList->where('tipe_disposisi', 'paraf');
        $ttdList = $disposisiList->where('tipe_disposisi', 'ttd');
    @endphp

    @if($parafList->count() > 0)
    <div style="margin: 10px 0; font-weight: bold;">Persetujuan Paraf:</div>
    <table class="signature-table">
        <thead>
            <tr>
                <th>Jabatan</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Paraf</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parafList as $disposisi)
            <tr>
                <td>{{ $disposisi->jabatan }}</td>
                <td>{{ $disposisi->user ? $disposisi->user->nama : 'N/A' }}</td>
                <td>
                    @if($disposisi->status === 'sudah')
                        <span style="color: #000; font-weight: bold;"><span class="checkbox">✓</span>DISETUJUI</span>
                    @else
                        <span style="color: #666;">PENDING</span>
                    @endif
                </td>
                <td>{{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y') : '-' }}</td>
                <td class="paraf-cell">
                    @if($disposisi->status === 'sudah')
                        <div style="font-weight: bold; font-size: 10px; line-height: 1.2;">PARAF</div>
                    @else
                        <div style="color: #666; font-size: 9px; font-style: italic; line-height: 1.2;">(tempat paraf)</div>
                    @endif
                </td>
                <td style="font-size: 10px;">{{ $disposisi->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Info Paraf -->
    <div style="margin: 10px 0; padding: 8px; background-color: #f9f9f9; border-left: 3px solid #000; font-size: 11px;">
        <strong>Keterangan Paraf:</strong> Paraf menunjukkan persetujuan dari pejabat terkait untuk memproses permohonan cuti ke tahap selanjutnya.
        Kolom paraf di tabel di atas dapat digunakan untuk memberikan paraf fisik saat dokumen dicetak.
    </div>
    @endif

    <!-- Kolom Tanda Tangan -->
    @if($ttdList->count() > 0)
    <h4 style="margin: 20px 0 10px 0; color: #dc2626;">✍️ Tanda Tangan dan Pengesahan</h4>

    @if($ttdList->count() == 1)
        <!-- Single TTD -->
        @php $ttd = $ttdList->first(); @endphp
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="width: 50%; border: none;"></td>
                <td style="width: 50%; border: 1px solid #000; padding: 15px; text-align: center;">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 15px 0;">
                        @php
                            $ttdSignature = \App\Models\Signature::getByJabatan($ttd->jabatan);
                        @endphp

                        @if($ttd->status === 'sudah')
                            <div style="color: #000; font-weight: bold; margin-bottom: 5px;"><span class="checkbox">✓</span>DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>

                            @if($ttdSignature && ($ttdSignature->signature_path || $ttdSignature->stamp_path))
                                <div style="position: relative; margin: 5px 0;">
                                    @if($ttdSignature->signature_path)
                                        <img src="{{ public_path($ttdSignature->signature_path) }}"
                                             alt="Signature"
                                             style="max-height: 40px; max-width: 100px;">
                                    @endif
                                    @if($ttdSignature->stamp_path)
                                        <img src="{{ public_path($ttdSignature->stamp_path) }}"
                                             alt="Stamp"
                                             style="max-height: 40px; max-width: 100px; position: absolute; top: -5px; left: 20px; opacity: 0.8;">
                                    @endif
                                </div>
                            @endif
                        @else
                            <div style="color: #666; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user ? $ttd->user->nama : ($ttdSignature ? $ttdSignature->nama : 'N/A') }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user ? ($ttd->user->nip ?? '-') : ($ttdSignature && $ttdSignature->nip ? $ttdSignature->nip : '-') }}</span>
                    </div>
                </td>
            </tr>
        </table>
    @else
        <!-- Multiple TTD - 2 Columns -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                @foreach($ttdList as $index => $ttd)
                <td style="width: 50%; border: 1px solid #000; padding: 15px; text-align: center; {{ $index > 0 ? 'border-left: none;' : '' }}">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 15px 0;">
                        @php
                            $ttdSignature = \App\Models\Signature::getByJabatan($ttd->jabatan);
                        @endphp

                        @if($ttd->status === 'sudah')
                            <div style="color: #000; font-weight: bold; margin-bottom: 5px;"><span class="checkbox">✓</span>DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>

                            @if($ttdSignature && ($ttdSignature->signature_path || $ttdSignature->stamp_path))
                                <div style="position: relative; margin: 5px 0;">
                                    @if($ttdSignature->signature_path)
                                        <img src="{{ public_path($ttdSignature->signature_path) }}"
                                             alt="Signature"
                                             style="max-height: 40px; max-width: 100px;">
                                    @endif
                                    @if($ttdSignature->stamp_path)
                                        <img src="{{ public_path($ttdSignature->stamp_path) }}"
                                             alt="Stamp"
                                             style="max-height: 40px; max-width: 100px; position: absolute; top: -5px; left: 20px; opacity: 0.8;">
                                    @endif
                                </div>
                            @endif
                        @else
                            <div style="color: #666; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user ? $ttd->user->nama : ($ttdSignature ? $ttdSignature->nama : 'N/A') }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user ? ($ttd->user->nip ?? '-') : ($ttdSignature && $ttdSignature->nip ? $ttdSignature->nip : '-') }}</span>
                    </div>
                </td>
                @endforeach
            </tr>
        </table>
    @endif
    @endif

    <!-- V. CATATAN CUTI -->
    <div style="margin-top: 20px;">
        <h3 style="margin: 0 0 10px 0; color: #374151; font-size: 14px;">V. CATATAN CUTI</h3>
        <table style="width: 100%; border: 1px solid #d1d5db; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td rowspan="4" style="width: 30%; vertical-align: top; border: 1px solid #d1d5db; padding: 8px;">
                        <strong>1. CUTI TAHUNAN</strong><br><br>
                        <table style="width: 100%; border: none;">
                            <tr style="border: none;">
                                <td style="border: none; padding: 2px;"><strong>TAHUN</strong></td>
                                <td style="border: none; padding: 2px;"><strong>Sisa</strong></td>
                                <td style="border: none; padding: 2px;"><strong>Keterangan</strong></td>
                            </tr>
                            @php
                                $sisaCutiData = \App\Models\SisaCuti::getSisaCutiMultiYear($suratCuti->pengaju_id, [2023, 2024, 2025]);
                            @endphp
                            <tr style="border: none;">
                                <td style="border: none; padding: 2px;">2023</td>
                                <td style="border: none; padding: 2px;">{{ $sisaCutiData[2023] ?? ($sisa_2023 ?? '') }}</td>
                                <td style="border: none; padding: 2px;">{{ $ket_2023 ?? '' }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none; padding: 2px;">2024</td>
                                <td style="border: none; padding: 2px;">{{ $sisaCutiData[2024] ?? ($sisa_2024 ?? '') }}</td>
                                <td style="border: none; padding: 2px;">{{ $ket_2024 ?? '' }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none; padding: 2px;">2025</td>
                                <td style="border: none; padding: 2px;">{{ $sisaCutiData[2025] ?? ($sisa_2025 ?? '12') }}</td>
                                <td style="border: none; padding: 2px;">{{ $ket_2025 ?? '' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 35%; vertical-align: top; border: 1px solid #d1d5db; padding: 8px;">
                        <strong>2. CUTI BESAR</strong><br><br>
                        <div style="height: 40px;"></div>
                    </td>
                    <td style="width: 35%; vertical-align: top; border: 1px solid #d1d5db; padding: 8px;">
                        <strong>3. CUTI SAKIT</strong><br><br>
                        <div style="height: 40px;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px;">
                        <strong>4. CUTI MELAHIRKAN</strong><br><br>
                        <div style="height: 40px;"></div>
                    </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px;">
                        <strong>5. CUTI KARENA ALASAN PENTING</strong><br><br>
                        <div style="height: 40px;"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- TANDA TANGAN PEMOHON -->
    <div style="margin: 20px 0; font-weight: bold;">Tanda Tangan Pemohon:</div>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; border: none;"></td>
            <td style="width: 50%; border: 1px solid #000; padding: 15px; text-align: center;">
                <div style="margin-bottom: 10px;">
                    <strong>PEMOHON</strong>
                </div>
                <div style="height: 60px; margin: 15px 0;">
                    <div style="color: #000; font-weight: bold; margin-bottom: 5px;">MENGAJUKAN</div>
                    <div style="font-size: 10px;">{{ $suratCuti->tanggal_ajuan->format('d F Y') }}</div>
                </div>
                <div style="border-top: 1px solid #000; padding-top: 5px;">
                    <strong>{{ $suratCuti->pengaju->nama }}</strong><br>
                    <span style="font-size: 10px;">NIP: {{ $suratCuti->pengaju->nip ?? '-' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- INFO TANDA TANGAN -->
    <div style="margin: 10px 0; padding: 8px; background-color: #f9f9f9; border-left: 3px solid #000; font-size: 11px;">
        <strong>Keterangan Tanda Tangan:</strong><br>
        • <strong>Pemohon:</strong> Tanda tangan pemohon menunjukkan bahwa permohonan cuti diajukan secara resmi dan data yang tercantum adalah benar.<br>
        • <strong>Pejabat:</strong> Tanda tangan pejabat menunjukkan persetujuan resmi terhadap permohonan cuti sesuai dengan kewenangan masing-masing.
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>{{ $nama_bidang ?? $suratCuti->pengaju->unit_kerja }}</strong></p>
        <p>Dinas Kesehatan Kabupaten {{ $kabupaten ?? 'Purworejo' }}</p>
        <p>Dokumen dicetak pada {{ now()->format('d F Y') }}</p>
    </div>
</body>
</html>
