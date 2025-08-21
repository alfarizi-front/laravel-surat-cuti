<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Cuti - {{ date('d F Y') }}</title>
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
        
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .info-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 5px;
            border: 1px solid #000;
            font-size: 11px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-box {
            text-align: center;
            margin-top: 50px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
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
        Laporan Surat Cuti yang Disetujui
    </div>

    <!-- Info Laporan -->
    <table class="info-table">
        <tr>
            <td style="width: 150px; font-weight: bold;">Periode Laporan</td>
            <td>
                @if(request('bulan') && request('tahun'))
                    {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }} {{ request('tahun') }}
                @elseif(request('tanggal_mulai') && request('tanggal_selesai'))
                    {{ date('d F Y', strtotime(request('tanggal_mulai'))) }} s/d {{ date('d F Y', strtotime(request('tanggal_selesai'))) }}
                @elseif(request('tanggal_mulai'))
                    Mulai {{ date('d F Y', strtotime(request('tanggal_mulai'))) }}
                @elseif(request('tanggal_selesai'))
                    Sampai {{ date('d F Y', strtotime(request('tanggal_selesai'))) }}
                @else
                    Semua Data
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Total Surat Cuti</td>
            <td>{{ number_format($statistik['total_surat']) }} surat</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Total Hari Cuti</td>
            <td>{{ number_format($statistik['total_hari']) }} hari</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Rata-rata Hari Cuti</td>
            <td>{{ $statistik['rata_rata_hari'] }} hari per surat</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal Cetak</td>
            <td>{{ date('d F Y, H:i:s') }}</td>
        </tr>
    </table>

    <!-- Data Table -->
    @if($suratCuti->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama Pegawai</th>
                    <th style="width: 15%;">NIP</th>
                    <th style="width: 20%;">Unit Kerja</th>
                    <th style="width: 15%;">Jenis Cuti</th>
                    <th style="width: 15%;">Periode Cuti</th>
                    <th style="width: 10%;">Jumlah Hari</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suratCuti as $index => $surat)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $surat->pengaju->nama }}</strong><br>
                            <small>{{ $surat->pengaju->jabatan }}</small><br>
                            <small>{{ $surat->pengaju->jenis_pegawai }}</small>
                        </td>
                        <td>{{ $surat->pengaju->nip ?: '-' }}</td>
                        <td>{{ $surat->pengaju->unit_kerja }}</td>
                        <td>{{ $surat->jenisCuti->nama ?? 'Cuti' }}</td>
                        <td style="text-align: center;">
                            {{ $surat->tanggal_awal->format('d/m/Y') }}<br>
                            s/d<br>
                            {{ $surat->tanggal_akhir->format('d/m/Y') }}
                        </td>
                        <td style="text-align: center;">{{ $surat->jumlah_hari }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">TOTAL:</td>
                    <td style="text-align: center;">{{ $suratCuti->sum('jumlah_hari') }} hari</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; margin: 50px 0; font-style: italic;">
            Tidak ada data surat cuti yang sesuai dengan filter yang dipilih.
        </p>
    @endif

    <!-- Statistik Per Jenis Cuti -->
    @if($statistik['per_jenis_cuti']->count() > 0)
        <div style="page-break-before: auto; margin-top: 30px;">
            <h3 style="font-size: 12px; margin-bottom: 10px; text-decoration: underline;">
                Rekapitulasi Per Jenis Cuti:
            </h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 60%;">Jenis Cuti</th>
                        <th style="width: 20%;">Jumlah Surat</th>
                        <th style="width: 15%;">Total Hari</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistik['per_jenis_cuti'] as $index => $stat)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $stat->jenisCuti->nama ?? 'Cuti' }}</td>
                            <td style="text-align: center;">{{ $stat->total }}</td>
                            <td style="text-align: center;">{{ $stat->total_hari }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2" style="text-align: right;">TOTAL:</td>
                        <td style="text-align: center;">{{ $statistik['per_jenis_cuti']->sum('total') }}</td>
                        <td style="text-align: center;">{{ $statistik['per_jenis_cuti']->sum('total_hari') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    <!-- Statistik Per Unit Kerja -->
    @if($statistik['per_unit_kerja']->count() > 0)
        <div style="page-break-before: auto; margin-top: 30px;">
            <h3 style="font-size: 12px; margin-bottom: 10px; text-decoration: underline;">
                Rekapitulasi Per Unit Kerja:
            </h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 60%;">Unit Kerja</th>
                        <th style="width: 20%;">Jumlah Surat</th>
                        <th style="width: 15%;">Total Hari</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistik['per_unit_kerja'] as $index => $stat)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $stat->unit_kerja }}</td>
                            <td style="text-align: center;">{{ $stat->total }}</td>
                            <td style="text-align: center;">{{ $stat->total_hari }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2" style="text-align: right;">TOTAL:</td>
                        <td style="text-align: center;">{{ $statistik['per_unit_kerja']->sum('total') }}</td>
                        <td style="text-align: center;">{{ $statistik['per_unit_kerja']->sum('total_hari') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    <!-- Footer & Signature -->
    <div class="footer">
        <div class="signature-box">
            <p>{{ date('d F Y') }}</p>
            <p>Kepala Dinas Kesehatan</p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">
                _________________________
            </p>
            <p>NIP: ___________________</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
