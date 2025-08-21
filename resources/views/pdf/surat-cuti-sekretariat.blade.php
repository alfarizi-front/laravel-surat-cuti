<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti Sekretariat</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #7c3aed;
            text-transform: uppercase;
        }
        
        .header .subtitle {
            font-size: 14px;
            margin: 5px 0;
            color: #6d28d9;
        }
        
        .sekretariat-info {
            background-color: #faf5ff;
            border: 2px solid #7c3aed;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .main-table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #7c3aed;
        }
        
        .main-table .label {
            width: 30%;
            font-weight: bold;
            background-color: #ede9fe;
            color: #6d28d9;
        }
        
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            font-size: 14px;
            color: #6d28d9;
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 5px;
        }
        
        .admin-section {
            background-color: #f3f4f6;
            border: 1px solid #9ca3af;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
        }
        
        .workflow-section {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
        }
        
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .signature-table td {
            padding: 10px;
            border: 1px solid #7c3aed;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-table th {
            background-color: #7c3aed;
            color: white;
            padding: 10px;
            font-weight: bold;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48px;
            color: rgba(124, 58, 237, 0.1);
            z-index: -1;
            font-weight: bold;
        }
        
        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
            background-color: white;
        }

        .checkbox.checked {
            background-color: white;
            color: black;
            font-weight: bold;
        }

        @page {
            margin: 2cm;
            size: A4;
        }
    </style>
</head>
<body>
    @if($isFlexibleApproval ?? false)
        <div class="watermark">SEKRETARIAT {{ $completionRate['overall'] }}%</div>
    @endif

    <!-- HEADER SEKRETARIAT -->
    <div class="header">
        <h1>📋 Surat Permintaan Cuti Pegawai Sekretariat</h1>
        <div class="subtitle">Sekretariat Dinas Kesehatan</div>
        <div class="subtitle">Kabupaten {{ $kabupaten ?? 'Purworejo' }}</div>
    </div>

    <!-- INFO SEKRETARIAT -->
    <div class="sekretariat-info">
        <h3 style="margin: 0 0 10px 0; color: #6d28d9;">🏛️ Informasi Sekretariat</h3>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Bagian:</strong> {{ $bagian ?? 'Sekretariat Dinas' }}<br>
                    <strong>Sekretaris Dinas:</strong> {{ $sekretaris_dinas ?? 'Sekretaris Dinas Kesehatan' }}<br>
                    <strong>NIP:</strong> {{ $sekretaris_dinas_nip ?? '196502021991032002' }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Alamat:</strong> {{ $alamat_sekretariat ?? 'Jl. Pemda No. 1' }}<br>
                    <strong>Telepon:</strong> {{ $telepon_sekretariat ?? '(0275) 321654' }}<br>
                    <strong>Email:</strong> {{ $email_sekretariat ?? 'sekretariat@dinkes.go.id' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- NOMOR SURAT -->
    <div style="text-align: right; margin-bottom: 20px;">
        <strong>Nomor:</strong> {{ $nomor_surat ?? '800.1.11.4/' . str_pad($suratCuti->id, 4, '0', STR_PAD_LEFT) . '/' . date('Y') }}<br>
        <strong>Tanggal:</strong> {{ $tanggal_surat ?? now()->format('d F Y') }}
    </div>

    <!-- DATA PEGAWAI -->
    <div class="section-title">I. Data Pegawai Sekretariat</div>
    <table class="main-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $suratCuti->pengaju->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>{{ $suratCuti->pengaju->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan/Posisi</td>
            <td>{{ $suratCuti->pengaju->jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td>{{ $suratCuti->pengaju->unit_kerja }}</td>
        </tr>
        <tr>
            <td class="label">Golongan/Pangkat</td>
            <td>{{ $golongan ?? 'III/c' }}</td>
        </tr>
        <tr>
            <td class="label">Masa Kerja</td>
            <td>{{ $masa_kerja ?? '10 tahun' }}</td>
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

    <!-- ADMINISTRASI CUTI -->
    <div class="admin-section">
        <h4 style="margin: 0 0 10px 0; color: #374151;">📊 Administrasi Cuti</h4>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    @php
                        $sisaCutiSekarang = \App\Models\SisaCuti::getSisaCuti($suratCuti->pengaju_id, date('Y'));
                        $sisaCutiDisplay = $sisaCutiSekarang ? $sisaCutiSekarang->sisa_akhir . ' hari' : ($sisa_cuti ?? '12 hari');
                        $cutiDiambilDisplay = $sisaCutiSekarang ? $sisaCutiSekarang->cuti_diambil . ' hari' : ($cuti_diambil ?? '0 hari');
                    @endphp
                    <strong>Sisa Cuti Tahunan:</strong> {{ $sisaCutiDisplay }}<br>
                    <strong>Cuti Diambil Tahun Ini:</strong> {{ $cutiDiambilDisplay }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Nomor Absen:</strong> {{ $nomor_absen ?? 'A' . str_pad($suratCuti->pengaju->id, 3, '0', STR_PAD_LEFT) }}<br>
                    <strong>Status Kepegawaian:</strong> {{ $status_kepegawaian ?? 'PNS Aktif' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- DETAIL CUTI -->
    <div class="section-title">II. Detail Permohonan Cuti</div>
    <table class="main-table">
        <tr>
            <td class="label">Jenis Cuti</td>
            <td><strong>{{ $suratCuti->jenisCuti->nama }}</strong></td>
        </tr>
        <tr>
            <td class="label">Alasan Cuti</td>
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
            <td class="label">Alamat Selama Cuti</td>
            <td>{{ $alamat_cuti ?? 'Sesuai alamat KTP' }}</td>
        </tr>
    </table>

    <!-- WORKFLOW PERSETUJUAN -->
    <div class="workflow-section">
        <h4 style="margin: 0 0 10px 0; color: #92400e;">🔄 Alur Persetujuan Sekretariat</h4>
        <p style="margin: 0; font-size: 11px;">
            Permohonan cuti pegawai sekretariat mengikuti alur: 
            <strong>Kasubag → Kasubag Umpeg → Kasubag Perencanaan → Sekretaris Dinas → KADIN</strong>
        </p>
    </div>

    <!-- PERSETUJUAN -->
    <div class="section-title">III. Persetujuan dan Pengesahan</div>

    <!-- Tabel Paraf -->
    @php
        $parafList = $disposisiList->where('tipe_disposisi', 'paraf');
        $ttdList = $disposisiList->where('tipe_disposisi', 'ttd');
    @endphp

    @if($parafList->count() > 0)
    <h4 style="margin: 15px 0 10px 0; color: #059669;">📝 Persetujuan Paraf</h4>
    <table class="signature-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Jabatan</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parafList as $index => $disposisi)
            <tr>
                <td><strong>{{ $index + 1 }}</strong></td>
                <td><strong>{{ $disposisi->jabatan }}</strong></td>
                <td>{{ $disposisi->user->nama ?? 'N/A' }}</td>
                <td>
                    @if($disposisi->status === 'sudah')
                        <span style="color: #059669; font-weight: bold;"><span class="checkbox">✓</span> APPROVED</span>
                    @else
                        <span style="color: #d97706; font-weight: bold;">⏳ PENDING</span>
                    @endif
                </td>
                <td>{{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y H:i') : '-' }}</td>
                <td style="font-size: 10px;">{{ $disposisi->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
                <td style="width: 50%; border: 1px solid #7c3aed; padding: 15px; text-align: center;">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 20px 0;">
                        @if($ttd->status === 'sudah')
                            <div style="color: #059669; font-weight: bold; margin-bottom: 10px;"><span class="checkbox">✓</span> DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>
                        @else
                            <div style="color: #d97706; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user->nama ?? 'N/A' }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user->nip ?? '-' }}</span>
                    </div>
                </td>
            </tr>
        </table>
    @else
        <!-- Multiple TTD - 2 Columns -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                @foreach($ttdList as $index => $ttd)
                <td style="width: 50%; border: 1px solid #7c3aed; padding: 15px; text-align: center; {{ $index > 0 ? 'border-left: none;' : '' }}">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 20px 0;">
                        @if($ttd->status === 'sudah')
                            <div style="color: #059669; font-weight: bold; margin-bottom: 10px;"><span class="checkbox">✓</span> DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>
                            @if($ttd->catatan)
                                <div style="font-size: 9px; color: #6b7280; margin-top: 5px;">{{ $ttd->catatan }}</div>
                            @endif
                        @else
                            <div style="color: #d97706; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user->nama ?? 'N/A' }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user->nip ?? '-' }}</span>
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

    <!-- CATATAN KHUSUS -->
    <div style="margin-top: 20px; padding: 10px; background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 4px;">
        <h4 style="margin: 0 0 5px 0; color: #92400e;">📝 Catatan Khusus Sekretariat:</h4>
        <ul style="margin: 0; padding-left: 20px; font-size: 11px;">
            <li>Pastikan tugas-tugas administrasi telah didelegasikan selama cuti</li>
            <li>Koordinasi dengan atasan langsung untuk kontinuitas pelayanan</li>
            <li>Serahkan dokumen penting kepada penanggung jawab sementara</li>
            <li>Laporkan kembali kepada atasan setelah selesai cuti</li>
        </ul>
    </div>

    <!-- FOOTER SEKRETARIAT -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #6b7280;">
        <p><strong>Sekretariat Dinas Kesehatan Kabupaten {{ $kabupaten ?? 'Purworejo' }}</strong></p>
        <p>{{ $alamat_sekretariat ?? 'Jl. Pemda No. 1' }} | Telp: {{ $telepon_sekretariat ?? '(0275) 321654' }}</p>
        <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
        @if($isFlexibleApproval ?? false)
            <p><em>* Dokumen dengan persetujuan fleksibel ({{ $completionRate['overall'] }}% complete)</em></p>
        @endif
    </div>
</body>
</html>
