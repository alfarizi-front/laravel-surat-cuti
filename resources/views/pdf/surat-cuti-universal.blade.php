<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti - {{ $pegawai->nama }} ({{ $pegawai->jenis_pegawai }})</title>
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
        .cuti-info {
            margin: 20px 0;
            border: 1px solid #ccc;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .disposisi-section {
            margin: 30px 0;
            border: 1px solid #000;
            padding: 15px;
        }
        .disposisi-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .disposisi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .disposisi-table th, .disposisi-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .disposisi-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .tipe-ttd {
            color: #d32f2f;
            font-weight: bold;
        }
        .tipe-paraf {
            color: #388e3c;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .signature-row {
            display: table-row;
        }
        .signature-cell {
            display: table-cell;
            text-align: center;
            padding: 15px;
            vertical-align: top;
            border: 1px solid #ccc;
        }
        .signature-box {
            height: 80px;
            margin: 20px 0;
            border-bottom: 1px solid #000;
            position: relative;
        }
        .signature-image {
            max-width: 100px;
            max-height: 60px;
            margin: 10px 0;
        }
        .cap-image {
            max-width: 80px;
            max-height: 80px;
            margin: 10px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .pegawai-notice {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .asn-notice {
            background-color: #e3f2fd;
            border: 1px solid #1976d2;
        }
        .pppk-notice {
            background-color: #f3e5f5;
            border: 1px solid #7b1fa2;
        }
        .notice-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .asn-notice .notice-title {
            color: #1565c0;
        }
        .pppk-notice .notice-title {
            color: #6a1b9a;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH DAERAH PROVINSI</h1>
        <h1>DINAS KESEHATAN</h1>
        <h2>SURAT PERMOHONAN CUTI</h2>
        <h2>{{ strtoupper($pegawai->jenis_pegawai) }} - {{ $pegawai->jenis_pegawai === 'ASN' ? 'APARATUR SIPIL NEGARA' : 'PEGAWAI PEMERINTAH DENGAN PERJANJIAN KERJA' }}</h2>
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
                    <td><strong>{{ $pegawai->jenis_pegawai }}</strong></td>
                </tr>
            </table>
        </div>

        @if($pegawai->jenis_pegawai === 'ASN')
            <div class="pegawai-notice asn-notice">
                <div class="notice-title">Ketentuan untuk Aparatur Sipil Negara (ASN):</div>
                <ul>
                    <li>Cuti tahunan sesuai dengan Peraturan Pemerintah tentang Cuti ASN</li>
                    <li>Hak cuti tahunan 12 hari per tahun</li>
                    <li>Dapat mengambil cuti bersama dan cuti besar</li>
                    <li>Mendapat tunjangan selama cuti sesuai ketentuan</li>
                </ul>
            </div>
        @elseif($pegawai->jenis_pegawai === 'PPPK')
            <div class="pegawai-notice pppk-notice">
                <div class="notice-title">Ketentuan untuk Pegawai Pemerintah dengan Perjanjian Kerja (PPPK):</div>
                <ul>
                    <li>Cuti tahunan diberikan sesuai dengan masa kerja dalam 1 (satu) tahun</li>
                    <li>Pengajuan cuti harus mendapat persetujuan atasan langsung</li>
                    <li>Cuti dilaksanakan sesuai dengan perjanjian kerja yang berlaku</li>
                    <li>Masa cuti diperhitungkan berdasarkan hari kerja efektif</li>
                </ul>
            </div>
        @endif

        @if(isset($surat_cuti))
            <div class="cuti-info">
                <h3>Informasi Cuti:</h3>
                <table>
                    <tr>
                        <td class="label">Jenis Cuti</td>
                        <td class="colon">:</td>
                        <td>{{ $surat_cuti->jenisCuti->nama ?? 'Cuti Tahunan' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="colon">:</td>
                        <td>{{ $surat_cuti->tanggal_awal->format('d F Y') ?? '' }} s/d {{ $surat_cuti->tanggal_akhir->format('d F Y') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lama Cuti</td>
                        <td class="colon">:</td>
                        <td>{{ $surat_cuti->jumlah_hari ?? '' }} hari</td>
                    </tr>
                    <tr>
                        <td class="label">Alasan</td>
                        <td class="colon">:</td>
                        <td>{{ $surat_cuti->alasan ?? 'Keperluan pribadi' }}</td>
                    </tr>
                </table>
            </div>
        @endif

        <p>Dengan ini mengajukan permohonan cuti sesuai dengan ketentuan yang berlaku bagi {{ $pegawai->jenis_pegawai === 'ASN' ? 'Aparatur Sipil Negara (ASN)' : 'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)' }}.</p>

        <p>Permohonan ini dibuat dengan sebenar-benarnya dan akan melaksanakan cuti sesuai dengan {{ $pegawai->jenis_pegawai === 'ASN' ? 'peraturan perundang-undangan yang berlaku' : 'perjanjian kerja dan peraturan perundang-undangan yang berlaku' }}.</p>

        <p>Demikian permohonan ini dibuat untuk dapat diproses lebih lanjut sesuai dengan {{ $pegawai->jenis_pegawai === 'ASN' ? 'ketentuan yang berlaku' : 'mekanisme yang berlaku untuk PPPK' }}.</p>
    </div>

    @if(isset($disposisi_list) && $disposisi_list->count() > 0)
        <div class="disposisi-section">
            <div class="disposisi-title">ALUR DISPOSISI PERSETUJUAN</div>
            
            <table class="disposisi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jabatan</th>
                        <th>Nama Pejabat</th>
                        <th>Tipe Disposisi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disposisi_list as $index => $disposisi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $disposisi->jabatan }}</td>
                            <td>{{ $disposisi->user->nama ?? 'Belum Ditentukan' }}</td>
                            <td>
                                <span class="{{ $disposisi->tipe_disposisi === 'ttd' ? 'tipe-ttd' : 'tipe-paraf' }}">
                                    {{ $disposisi->tipe_disposisi === 'ttd' ? '🖋️ TANDA TANGAN' : '✍️ PARAF' }}
                                </span>
                            </td>
                            <td>{{ ucfirst($disposisi->status) }}</td>
                            <td>{{ $disposisi->tanggal ? $disposisi->tanggal->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 15px; font-size: 10px;">
                <p><strong>Keterangan:</strong></p>
                <p><span class="tipe-ttd">🖋️ TANDA TANGAN</span> - Untuk pejabat tinggi (KADIN, Kepala Puskesmas, Kepala Bidang)</p>
                <p><span class="tipe-paraf">✍️ PARAF</span> - Untuk pejabat struktural lainnya</p>
            </div>
        </div>
    @endif

    <div class="signature-section">
        <div class="signature-grid">
            <div class="signature-row">
                <div class="signature-cell" style="width: 50%;">
                    <p><strong>Pemohon</strong></p>
                    <p>{{ $tanggal_cetak ?? now()->format('d F Y') }}</p>
                    
                    @if($pegawai->tanda_tangan)
                        <img src="{{ storage_path('app/public/' . $pegawai->tanda_tangan) }}" 
                             class="signature-image" alt="Tanda Tangan">
                    @endif
                    
                    <div class="signature-box"></div>
                    <p><strong>{{ $pegawai->nama }}</strong></p>
                    <p>NIP: {{ $pegawai->nip }}</p>
                </div>
                
                <div class="signature-cell" style="width: 50%;">
                    <p><strong>Kepala Dinas Kesehatan</strong></p>
                    <p>{{ $tanggal_cetak ?? now()->format('d F Y') }}</p>
                    
                    @if(isset($kepala_dinas) && $kepala_dinas->cap_stempel && $kepala_dinas->gunakan_cap)
                        <img src="{{ storage_path('app/public/' . $kepala_dinas->cap_stempel) }}" 
                             class="cap-image" alt="Cap Dinas">
                    @endif
                    
                    @if(isset($kepala_dinas) && $kepala_dinas->tanda_tangan)
                        <img src="{{ storage_path('app/public/' . $kepala_dinas->tanda_tangan) }}" 
                             class="signature-image" alt="Tanda Tangan Kepala Dinas">
                    @endif
                    
                    <div class="signature-box"></div>
                    <p><strong>{{ $kepala_dinas->nama ?? 'Dr. Kepala Dinas' }}</strong></p>
                    <p>NIP: {{ $kepala_dinas->nip ?? '________________' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ $tanggal_cetak ?? now()->format('d F Y, H:i') }} WIB</p>
        <p>Sistem Informasi Surat Cuti - Dinas Kesehatan</p>
        <p><em>Template Universal untuk {{ $pegawai->jenis_pegawai }} dengan Alur Disposisi Terintegrasi</em></p>
    </div>
</body>
</html>
