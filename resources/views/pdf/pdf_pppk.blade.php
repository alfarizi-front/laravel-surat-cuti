<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti - {{ $pegawai->nama }} (PPPK)</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
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
        .content {
            margin: 30px 0;
        }
        .employee-info {
            margin: 20px 0;
        }
        .employee-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .employee-info td {
            padding: 5px;
            vertical-align: top;
        }
        .employee-info .label {
            width: 150px;
            font-weight: bold;
        }
        .employee-info .colon {
            width: 20px;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 80px;
            padding-top: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .pppk-notice {
            background-color: #f0f8ff;
            border: 1px solid #4a90e2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .pppk-notice h3 {
            margin-top: 0;
            color: #2c5aa0;
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
        @page {
            margin: 1cm;
        }
    .disposisi-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .disposisi-table th, .disposisi-table td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        .disposisi-table th { background: #f0f0f0; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH DAERAH PROVINSI</h1>
        <h1>DINAS KESEHATAN</h1>
        <h2>SURAT PERMOHONAN CUTI</h2>
        <h2>PEGAWAI PEMERINTAH DENGAN PERJANJIAN KERJA (PPPK)</h2>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>

        <div class="employee-info">
            <table>
                <tr>
                    <td class="label">Nama</td>
                    <td class="colon">:</td>
                    <td>{{ $pegawai->nama }}</td>
                </tr>
                <tr>
                    <td class="label">NIP</td>
                    <td class="colon">:</td>
                    <td>{{ $pegawai->nip }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="colon">:</td>
                    <td>{{ $pegawai->jabatan }}</td>
                </tr>
                <tr>
                    <td class="label">Unit Kerja</td>
                    <td class="colon">:</td>
                    <td>{{ $pegawai->unit_kerja }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Pegawai</td>
                    <td class="colon">:</td>
                    <td>{{ $pegawai->jenis_pegawai }}</td>
                </tr>
            </table>
        </div>

        <div class="pppk-notice">
            <h3>Ketentuan Khusus untuk PPPK:</h3>
            <ul>
                <li>Cuti tahunan diberikan sesuai dengan masa kerja dalam 1 (satu) tahun</li>
                <li>Pengajuan cuti harus mendapat persetujuan atasan langsung</li>
                <li>Cuti dilaksanakan sesuai dengan perjanjian kerja yang berlaku</li>
                <li>Masa cuti diperhitungkan berdasarkan hari kerja efektif</li>
            </ul>
        </div>

        <p>Dengan ini mengajukan permohonan cuti sesuai dengan ketentuan yang berlaku bagi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK).</p>

        <p>Permohonan ini dibuat dengan sebenar-benarnya dan akan melaksanakan cuti sesuai dengan perjanjian kerja dan peraturan perundang-undangan yang berlaku.</p>

        <p>Demikian permohonan ini dibuat untuk dapat diproses lebih lanjut sesuai dengan mekanisme yang berlaku untuk PPPK.</p>
    </div>

    
    @if(isset($disposisiList) && $disposisiList->count() > 0)
    <div class="content">
        <h3 style="font-size:12px; margin-bottom:8px;">Riwayat Disposisi:</h3>
        <table class="disposisi-table">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:25%;">Nama</th>
                    <th style="width:20%;">Jabatan</th>
                    <th style="width:15%;">Status</th>
                    <th style="width:15%;">Tanggal</th>
                    <th style="width:20%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($disposisiList as $i => $d)
                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td>{{ $d->user->nama ?? '-' }}</td>
                    <td>{{ $d->jabatan }}</td>
                    <td style="text-align:center;">
                        @if($d->status === 'sudah')
                            <span class="checkbox">✓</span> DISETUJUI
                        @elseif($d->status === 'ditolak')
                            DITOLAK
                        @else
                            PENDING
                        @endif
                    </td>
                    <td style="text-align:center;">{{ $d->tanggal ? $d->tanggal->format('d/m/Y') : '-' }}</td>
                    <td>{{ $d->catatan ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p><strong>Atasan Langsung</strong></p>
            <div class="signature-line">
                <p>(...................................)</p>
                <p>NIP: ........................</p>
            </div>
        </div>

        <div class="signature-box">
            <p>{{ $tanggal_cetak }}</p>
            <p><strong>Pemohon</strong></p>
            @if($pegawai->tanda_tangan)
                <img src="{{ storage_path('app/public/' . $pegawai->tanda_tangan) }}" 
                     style="max-width: 100px; max-height: 50px; margin: 20px 0;">
            @endif
            <div class="signature-line">
                <p><strong>{{ $pegawai->nama }}</strong></p>
                <p>NIP: {{ $pegawai->nip }}</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ $tanggal_cetak }}</p>
        <p>Sistem Informasi Surat Cuti - Dinas Kesehatan</p>
        <p><em>Khusus untuk Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)</em></p>
    </div>
</body>
</html>
