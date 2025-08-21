<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan dan Pemberian Cuti</title>
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
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header .subtitle {
            font-size: 14px;
            margin: 5px 0;
            font-weight: normal;
        }
        
        .nomor-section {
            margin-bottom: 20px;
            text-align: right;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .main-table td {
            padding: 4px 8px;
            vertical-align: top;
            border: 1px solid #000;
        }
        
        .main-table .label {
            width: 25%;
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .main-table .colon {
            width: 5%;
            text-align: center;
        }
        
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            font-size: 13px;
        }
        
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            text-align: center;
            line-height: 10px;
            margin-right: 5px;
        }
        
        .checkbox.checked::before {
            content: "✓";
            font-weight: bold;
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
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-box {
            height: 80px;
            position: relative;
        }
        
        .signature-name {
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: bold;
            font-size: 11px;
        }
        
        .approval-status {
            background-color: #e8f5e8;
            border: 1px solid #4caf50;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .approval-status.partial {
            background-color: #fff3cd;
            border-color: #ffc107;
        }
        
        .status-indicator {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
            font-weight: bold;
        }
        
        @page {
            margin: 2cm;
            size: A4;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    @if($isFlexibleApproval ?? false)
        <div class="watermark">{{ $completionRate['overall'] }}% APPROVED</div>
    @endif

    <!-- HEADER ASN -->
    <div class="header">
        <h1>📄 Surat Permintaan dan Pemberian Cuti ASN</h1>
        <div class="subtitle">Aparatur Sipil Negara (ASN)</div>
        <div class="subtitle">Dinas Kesehatan Daerah Kabupaten {{ $kabupaten ?? 'Purworejo' }}</div>
        @if($isFlexibleApproval ?? false)
            <div class="approval-status {{ $suratCuti->status === 'disetujui' ? '' : 'partial' }}">
                <strong>Status Persetujuan:</strong> 
                @if($suratCuti->status === 'disetujui')
                    FULLY APPROVED
                @else
                    PARTIAL APPROVAL ({{ $completionRate['overall'] }}% Complete)
                @endif
            </div>
        @endif
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-section">
        <strong>Nomor:</strong> {{ $nomor_surat ?? '800.1.11.4/___/' . date('Y') }}<br>
        <strong>Tanggal:</strong> {{ $tanggal_surat ?? now()->format('d F Y') }}
    </div>

    <!-- DATA PEGAWAI -->
    <div class="section-title">I. Data Pegawai</div>
    <table class="main-table">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->pengaju->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->pengaju->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->pengaju->jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->pengaju->unit_kerja }}</td>
        </tr>
        <tr>
            <td class="label">Masa Kerja</td>
            <td class="colon">:</td>
            <td>{{ $masa_kerja ?? '5 tahun' }}</td>
        </tr>
    </table>

    <!-- JENIS CUTI -->
    <div class="section-title">II. Jenis Cuti yang Diambil</div>
    <table class="main-table">
        <tr>
            <td>
                <span class="checkbox {{ $suratCuti->jenisCuti->nama === 'Cuti Tahunan' ? 'checked' : '' }}"></span>
                Cuti Tahunan
            </td>
            <td>
                <span class="checkbox {{ $suratCuti->jenisCuti->nama === 'Cuti Besar' ? 'checked' : '' }}"></span>
                Cuti Besar
            </td>
        </tr>
        <tr>
            <td>
                <span class="checkbox {{ $suratCuti->jenisCuti->nama === 'Cuti Sakit' ? 'checked' : '' }}"></span>
                Cuti Sakit
            </td>
            <td>
                <span class="checkbox {{ $suratCuti->jenisCuti->nama === 'Cuti Melahirkan' ? 'checked' : '' }}"></span>
                Cuti Melahirkan
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="checkbox {{ !in_array($suratCuti->jenisCuti->nama, ['Cuti Tahunan', 'Cuti Besar', 'Cuti Sakit', 'Cuti Melahirkan']) ? 'checked' : '' }}"></span>
                Lainnya: {{ !in_array($suratCuti->jenisCuti->nama, ['Cuti Tahunan', 'Cuti Besar', 'Cuti Sakit', 'Cuti Melahirkan']) ? $suratCuti->jenisCuti->nama : '' }}
            </td>
        </tr>
    </table>

    <!-- ALASAN CUTI -->
    <div class="section-title">III. Alasan Cuti</div>
    <table class="main-table">
        <tr>
            <td>{{ $suratCuti->alasan }}</td>
        </tr>
    </table>

    <!-- LAMANYA CUTI -->
    <div class="section-title">IV. Lamanya Cuti</div>
    <table class="main-table">
        <tr>
            <td class="label">Selama</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->jumlah_hari }} hari</td>
        </tr>
        <tr>
            <td class="label">Mulai tanggal</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->tanggal_awal->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Sampai dengan tanggal</td>
            <td class="colon">:</td>
            <td>{{ $suratCuti->tanggal_akhir->format('d F Y') }}</td>
        </tr>
    </table>

    <!-- PERSETUJUAN -->
    <div class="section-title">V. Persetujuan</div>

    <!-- Tabel Paraf -->
    @php
        $parafList = $disposisiList->where('tipe_disposisi', 'paraf');
        $ttdList = $disposisiList->where('tipe_disposisi', 'ttd');
    @endphp

    @if($parafList->count() > 0)
    <h4 style="margin: 15px 0 10px 0; color: #059669;">📝 Persetujuan Paraf</h4>
    <table class="signature-table">
        <tr>
            <td><strong>Jabatan</strong></td>
            <td><strong>Nama</strong></td>
            <td><strong>Status</strong></td>
            <td><strong>Tanggal</strong></td>
            <td><strong>Paraf</strong></td>
            <td><strong>Catatan</strong></td>
        </tr>
        @foreach($parafList as $disposisi)
        <tr>
            <td>{{ $disposisi->jabatan }}</td>
            <td>{{ $disposisi->user ? $disposisi->user->nama : 'N/A' }}</td>
            <td>
                @if($disposisi->status === 'sudah')
                    <span class="status-indicator status-approved">APPROVED</span>
                @else
                    <span class="status-indicator status-pending">PENDING</span>
                @endif
            </td>
            <td>{{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y') : '-' }}</td>
            <td style="text-align: center; width: 80px; min-height: 40px; padding: 8px; border: 1px solid #000; vertical-align: middle;">
                @if($disposisi->status === 'sudah')
                    <div style="font-weight: bold; font-size: 10px; line-height: 1.2;">PARAF</div>
                @else
                    <div style="color: #666; font-size: 9px; font-style: italic; line-height: 1.2;">(tempat paraf)</div>
                @endif
            </td>
            <td style="font-size: 10px;">{{ $disposisi->catatan ?? '-' }}</td>
        </tr>
        @endforeach
    </table>

    <!-- Info Paraf -->
    <div style="margin: 10px 0; padding: 8px; background-color: #f0f9ff; border-left: 3px solid #3b82f6; font-size: 11px;">
        <strong>Keterangan Paraf:</strong> Paraf menunjukkan persetujuan dari pejabat terkait untuk memproses permohonan cuti ke tahap selanjutnya.
        Kolom paraf di tabel di atas dapat digunakan untuk memberikan paraf fisik saat dokumen dicetak.
    </div>
    @endif

    <!-- Kolom Tanda Tangan -->
    @if($ttdList->count() > 0)
    <h4 style="margin: 20px 0 10px 0; color: #dc2626;">✍️ Tanda Tangan dan Pengesahan</h4>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            @if($ttdList->count() == 1)
                <!-- Single TTD - Right aligned -->
                <td style="width: 50%; border: none;"></td>
                @php $ttd = $ttdList->first(); @endphp
                <td style="width: 50%; border: 1px solid #000; padding: 15px; text-align: center;">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 20px 0;">
                        @if($ttd->status === 'sudah')
                            <div style="color: #059669; font-weight: bold; margin-bottom: 10px;">✅ DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>
                        @else
                            <div style="color: #d97706; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user ? $ttd->user->nama : 'N/A' }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user ? ($ttd->user->nip ?? '-') : '-' }}</span>
                    </div>
                </td>
            @else
                <!-- Multiple TTD - Side by side -->
                @foreach($ttdList as $index => $ttd)
                <td style="width: {{ 100 / $ttdList->count() }}%; border: 1px solid #000; padding: 15px; text-align: center; {{ $index > 0 ? 'border-left: none;' : '' }}">
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $ttd->jabatan }}</strong>
                    </div>
                    <div style="height: 80px; margin: 20px 0;">
                        @if($ttd->status === 'sudah')
                            <div style="color: #059669; font-weight: bold; margin-bottom: 10px;">✅ DISETUJUI</div>
                            <div style="font-size: 10px;">{{ $ttd->tanggal ? $ttd->tanggal->format('d F Y') : '' }}</div>
                            @if($ttd->catatan)
                                <div style="font-size: 9px; color: #6b7280; margin-top: 5px;">{{ $ttd->catatan }}</div>
                            @endif
                        @else
                            <div style="color: #d97706; font-style: italic;">( Menunggu Tanda Tangan )</div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <strong>{{ $ttd->user ? $ttd->user->nama : 'N/A' }}</strong><br>
                        <span style="font-size: 10px;">NIP: {{ $ttd->user ? ($ttd->user->nip ?? '-') : '-' }}</span>
                    </div>
                </td>
                @endforeach
            @endif
        </tr>
    </table>
    @endif

    @if(isset($approvalStatus))
    <div class="section-title">VI. Ringkasan Persetujuan</div>
    <table class="main-table">
        <tr>
            <td class="label">Tanda Tangan</td>
            <td class="colon">:</td>
            <td>{{ $approvalStatus['signatures']['approved'] }}/{{ $approvalStatus['signatures']['total'] }} 
                ({{ $completionRate['signatures'] }}%)</td>
        </tr>
        <tr>
            <td class="label">Paraf</td>
            <td class="colon">:</td>
            <td>{{ $approvalStatus['parafs']['approved'] }}/{{ $approvalStatus['parafs']['total'] }} 
                ({{ $completionRate['parafs'] }}%)</td>
        </tr>
        <tr>
            <td class="label">Total Completion</td>
            <td class="colon">:</td>
            <td><strong>{{ $completionRate['overall'] }}%</strong></td>
        </tr>
    </table>
    @endif

    <!-- TANDA TANGAN PEMOHON -->
    <div class="section-title">Tanda Tangan Pemohon</div>
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
    <div style="margin: 10px 0; padding: 8px; background-color: #f0f9ff; border-left: 3px solid #3b82f6; font-size: 11px;">
        <strong>Keterangan Tanda Tangan:</strong><br>
        • <strong>Pemohon:</strong> Tanda tangan pemohon menunjukkan bahwa permohonan cuti diajukan secara resmi dan data yang tercantum adalah benar.<br>
        • <strong>Pejabat:</strong> Tanda tangan pejabat menunjukkan persetujuan resmi terhadap permohonan cuti sesuai dengan kewenangan masing-masing.
    </div>

    <!-- FOOTER -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
        @if($isFlexibleApproval ?? false)
            <p><em>* Dokumen ini dapat diunduh dengan persetujuan fleksibel (minimal semua tanda tangan dan 80% paraf)</em></p>
        @endif
    </div>
</body>
</html>
