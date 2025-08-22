<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti Puskesmas</title>
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
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #2563eb;
            text-transform: uppercase;
        }
        
        .header .subtitle {
            font-size: 14px;
            margin: 5px 0;
            color: #1e40af;
        }
        
        .puskesmas-info {
            background-color: #eff6ff;
            border: 2px solid #2563eb;
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
            border: 1px solid #2563eb;
        }
        
        .main-table .label {
            width: 30%;
            font-weight: bold;
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            font-size: 14px;
            color: #1e40af;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
        }
        
        .medical-section {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
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
            border: 1px solid #2563eb;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-table th {
            background-color: #2563eb;
            color: white;
            padding: 10px;
            font-weight: bold;
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
 

            font-family: 'DejaVu Sans', sans-serif;

      
 
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48px;
            color: rgba(37, 99, 235, 0.1);
            z-index: -1;
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
        <div class="watermark">PUSKESMAS {{ $completionRate['overall'] }}%</div>
    @endif

    <!-- HEADER PUSKESMAS -->
    <div class="header">
        <h1>🏥 Surat Permintaan Cuti Pegawai Puskesmas</h1>
        <div class="subtitle">{{ $puskesmas_name ?? 'Puskesmas ' . $suratCuti->pengaju->unit_kerja }}</div>
        <div class="subtitle">Dinas Kesehatan Kabupaten {{ $kabupaten ?? 'Purworejo' }}</div>
    </div>

    <!-- INFO PUSKESMAS -->
    <div class="puskesmas-info">
        <h3 style="margin: 0 0 10px 0; color: #1e40af;">📋 Informasi Puskesmas</h3>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Nama Puskesmas:</strong> {{ $puskesmas_name ?? 'Puskesmas ' . $suratCuti->pengaju->unit_kerja }}<br>
                    <strong>Alamat:</strong> {{ $puskesmas_address ?? 'Jl. Kesehatan No. 1' }}<br>
                    <strong>Telepon:</strong> {{ $puskesmas_phone ?? '(0275) 123456' }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Kode Puskesmas:</strong> {{ $puskesmas_code ?? 'PKM001' }}<br>
                    <strong>Kepala Puskesmas:</strong> {{ $kepala_puskesmas ?? 'dr. Kepala Puskesmas' }}<br>
                    <strong>NIP:</strong> {{ $kepala_puskesmas_nip ?? '196501011990031001' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- NOMOR SURAT -->
    <div style="text-align: right; margin-bottom: 20px;">
        <strong>Nomor:</strong> {{ $nomor_surat ?? 'PKM/800/' . date('Y') . '/' . str_pad($suratCuti->id, 4, '0', STR_PAD_LEFT) }}<br>
        <strong>Tanggal:</strong> {{ $tanggal_surat ?? now()->format('d F Y') }}
    </div>

    <!-- DATA PEGAWAI -->
    <div class="section-title">I. Data Pegawai Puskesmas</div>
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
            <td class="label">Jenis Pegawai</td>
            <td>{{ $suratCuti->pengaju->jenis_pegawai ?? 'ASN' }}</td>
        </tr>
        <tr>
            <td class="label">Masa Kerja</td>
            <td>{{ $masa_kerja ?? '5 tahun' }}</td>
        </tr>
    </table>

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
            <td class="label">Tanggal Pengajuan</td>
            <td>{{ $suratCuti->tanggal_ajuan->format('d F Y') }}</td>
        </tr>
    </table>

    <!-- INFORMASI MEDIS (Jika cuti sakit) -->
    @if($suratCuti->jenisCuti->nama === 'Cuti Sakit')
    <div class="medical-section">
        <h4 style="margin: 0 0 10px 0; color: #0ea5e9;">🏥 Informasi Medis</h4>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Dokter Pemeriksa:</strong> {{ $dokter_pemeriksa ?? 'dr. Dokter Puskesmas' }}<br>
                    <strong>Diagnosis:</strong> {{ $diagnosis ?? 'Sesuai surat keterangan dokter' }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Tanggal Pemeriksaan:</strong> {{ $tanggal_periksa ?? $suratCuti->tanggal_ajuan->format('d F Y') }}<br>
                    <strong>Rekomendasi:</strong> {{ $rekomendasi ?? 'Istirahat total' }}
                </td>
            </tr>
        </table>
    </div>
    @endif

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
                <th>Jabatan</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parafList as $disposisi)
            <tr>
                <td><strong>{{ $disposisi->jabatan }}</strong></td>
                <td>{{ $disposisi->user->nama ?? 'N/A' }}</td>
                <td>
                    @if($disposisi->status === 'sudah')
                        <span style="color: #059669; font-weight: bold;"><span class="checkbox">✓</span> APPROVED</span>
                    @else
                        <span style="color: #d97706; font-weight: bold;">⏳ PENDING</span>
                    @endif
                </td>
                <td>{{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y') : '-' }}</td>
                <td style="font-size: 10px;">{{ $disposisi->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
                <td style="width: 50%; border: 1px solid #2563eb; padding: 15px; text-align: center;">
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
            @else
                <!-- Multiple TTD - Side by side -->
                @foreach($ttdList as $index => $ttd)
                <td style="width: {{ 100 / $ttdList->count() }}%; border: 1px solid #2563eb; padding: 15px; text-align: center; {{ $index > 0 ? 'border-left: none;' : '' }}">
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
            @endif
        </tr>
    </table>
    @endif

    <!-- FOOTER PUSKESMAS -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #6b7280;">
        <p><strong>{{ $puskesmas_name ?? 'Puskesmas ' . $suratCuti->pengaju->unit_kerja }}</strong></p>
        <p>{{ $puskesmas_address ?? 'Jl. Kesehatan No. 1' }} | Telp: {{ $puskesmas_phone ?? '(0275) 123456' }}</p>
        <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
        @if($isFlexibleApproval ?? false)
            <p><em>* Dokumen dengan persetujuan fleksibel ({{ $completionRate['overall'] }}% complete)</em></p>
        @endif
    </div>
</body>
</html>
