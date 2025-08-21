<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Cuti - {{ $suratCuti->pengaju->nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
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
        }
        
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .content {
            margin: 20px 0;
        }
        
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .data-table {
            width: 100%;
            margin: 15px 0;
        }
        
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .data-table .label {
            width: 150px;
            font-weight: normal;
        }
        
        .data-table .colon {
            width: 20px;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-left, .signature-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-box {
            text-align: center;
            min-height: 80px;
            position: relative;
            border: 1px solid #ddd;
            margin: 10px 0;
        }
        
        .signature-image {
            max-height: 70px;
            max-width: 150px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        
        .disposisi-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .disposisi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        .disposisi-table th,
        .disposisi-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }

        .status-approved {
            color: green;
            font-weight: bold;
            background-color: #e8f5e8;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
            background-color: #f8d7da;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .disposisi-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Pemerintah Kota</h1>
        <h1>Dinas Kesehatan</h1>
        <p>Jl. Kesehatan No. 123, Kota, Provinsi 12345</p>
        <p>Telp: (021) 1234567 | Email: dinkes@kota.go.id</p>
    </div>

    <!-- Title -->
    <div class="title">
        Surat Permohonan Cuti
    </div>

    <!-- Content -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        
        <table class="data-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->pengaju->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->pengaju->nip ?: '-' }}</td>
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
                <td class="label">Jenis Pegawai</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->pengaju->jenis_pegawai }}</td>
            </tr>
        </table>

        <p>Dengan ini mengajukan permohonan cuti dengan data sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td class="label">Jenis Cuti</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->jenisCuti->nama ?? 'Cuti' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->tanggal_awal->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->tanggal_akhir->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Hari</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->jumlah_hari }} hari</td>
            </tr>
            <tr>
                <td class="label">Alasan Cuti</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->alasan }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Selama Cuti</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->alamat_selama_cuti }}</td>
            </tr>
            <tr>
                <td class="label">Kontak Darurat</td>
                <td class="colon">:</td>
                <td>{{ $suratCuti->kontak_darurat }}</td>
            </tr>
        </table>

        <p>Demikian permohonan ini saya sampaikan, atas perhatian dan persetujuannya saya ucapkan terima kasih.</p>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-left">
            <p style="text-align: center; margin-bottom: 5px;">Pemohon,</p>
            <div class="signature-box">
                @if($suratCuti->pengaju->tanda_tangan)
                    @php
                        $signaturePath = public_path('storage/' . $suratCuti->pengaju->tanda_tangan);
                        $signatureExists = file_exists($signaturePath);
                    @endphp
                    @if($signatureExists)
                        @if(isset($isPreview) && $isPreview)
                            <img src="{{ asset('storage/' . $suratCuti->pengaju->tanda_tangan) }}" 
                                 alt="Tanda Tangan" class="signature-image">
                        @else
                            @php
                                $imageData = base64_encode(file_get_contents($signaturePath));
                                $imageMime = mime_content_type($signaturePath);
                                $base64Image = 'data:' . $imageMime . ';base64,' . $imageData;
                            @endphp
                            <img src="{{ $base64Image }}" 
                                 alt="Tanda Tangan" class="signature-image">
                        @endif
                    @else
                        <div style="position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); font-size: 8px; color: red;">
                            (File tanda tangan tidak ditemukan)
                        </div>
                    @endif
                @else
                    <div style="position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); font-size: 10px;">
                        (Belum upload tanda tangan)
                    </div>
                @endif
            </div>
            <p style="text-align: center; margin-top: 5px; font-weight: bold;">{{ $suratCuti->pengaju->nama }}</p>
            <p style="text-align: center; margin: 0; font-size: 10px;">{{ $suratCuti->pengaju->jabatan }}</p>
        </div>

        <div class="signature-right">
            @if($kadinDisposisi)
                <p style="text-align: center; margin-bottom: 5px;">Mengetahui,</p>
                <p style="text-align: center; margin-bottom: 5px;">Kepala Dinas Kesehatan</p>
                <div class="signature-box">
                    @if($kadinDisposisi->user->tanda_tangan)
                        @php
                            $kadinSignaturePath = public_path('storage/' . $kadinDisposisi->user->tanda_tangan);
                            $kadinSignatureExists = file_exists($kadinSignaturePath);
                        @endphp
                        @if($kadinSignatureExists)
                            @if(isset($isPreview) && $isPreview)
                                <img src="{{ asset('storage/' . $kadinDisposisi->user->tanda_tangan) }}" 
                                     alt="Tanda Tangan KADIN" class="signature-image">
                            @else
                                @php
                                    $kadinImageData = base64_encode(file_get_contents($kadinSignaturePath));
                                    $kadinImageMime = mime_content_type($kadinSignaturePath);
                                    $kadinBase64Image = 'data:' . $kadinImageMime . ';base64,' . $kadinImageData;
                                @endphp
                                <img src="{{ $kadinBase64Image }}" 
                                     alt="Tanda Tangan KADIN" class="signature-image">
                            @endif
                        @else
                            <div style="position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); font-size: 8px; color: red;">
                                (File tanda tangan KADIN tidak ditemukan)
                            </div>
                        @endif
                    @else
                        <div style="position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); font-size: 10px;">
                            ({{ $kadinDisposisi->tanggal->format('d/m/Y') }})
                        </div>
                    @endif
                </div>
                <p style="text-align: center; margin-top: 5px; font-weight: bold;">{{ $kadinDisposisi->user->nama }}</p>
                <p style="text-align: center; margin: 0; font-size: 10px;">NIP: {{ $kadinDisposisi->user->nip ?: '-' }}</p>
            @endif
        </div>
    </div>

    <!-- Disposisi History -->
    @if($disposisiList && $disposisiList->count() > 0)
        <div class="disposisi-section">
            <h3 style="font-size: 12px; margin-bottom: 10px;">Riwayat Disposisi:</h3>
            <table class="disposisi-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama</th>
                        <th style="width: 20%;">Jabatan</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 20%;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disposisiList as $index => $disposisi)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $disposisi->user->nama }}</td>
                            <td>{{ $disposisi->jabatan }}</td>
                            <td style="text-align: center;">
                                @if($disposisi->status === 'sudah')
                                    <span class="status-approved">DISETUJUI</span>
                                @elseif($disposisi->status === 'ditolak')
                                    <span class="status-rejected">DITOLAK</span>
                                @else
                                    <span class="status-pending">PENDING</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y') : '-' }}
                            </td>
                            <td>{{ $disposisi->catatan ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i:s') }}</p>
        <p>Surat Cuti ID: {{ $suratCuti->id }} | Status: {{ ucfirst($suratCuti->status) }}</p>
    </div>
</body>
</html>
