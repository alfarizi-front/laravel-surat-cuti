<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
        }
        .header {
            text-align: right;
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px 8px;
        }
        .no-border {
            border: none;
        }
        .section {
            margin: 15px 0;
        }
        .signature {
            margin-top: 30px;
            float: right;
            text-align: center;
        }
        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid black;
            display: inline-block;
            margin-right: 5px;
        }
        .check {
            font-size: 12pt;
            line-height: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        Purworejo, {{ date('d F Y') }}<br>
        Kepada<br>
        Yth. Kepala Dinas Kesehatan Daerah<br>
        Kabupaten Purworejo<br>
        Di - PURWOREJO
    </div>

    <div class="title">
        PERMINTAAN DAN PEMBERIAN CUTI<br>
        Nomor : {{ $surat->nomor_surat }}
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="4">I. DATA PEGAWAI</td>
            </tr>
            <tr>
                <td width="20%">Nama</td>
                <td width="40%">{{ $surat->pengaju->nama }}</td>
                <td width="15%">NIP</td>
                <td width="25%">{{ $surat->pengaju->nip }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>{{ $surat->pengaju->jabatan }}</td>
                <td>Masa Kerja</td>
                <td>{{ $masa_kerja }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td colspan="3">{{ $surat->pengaju->unit_kerja }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="6">II. JENIS CUTI YANG DIAMBIL</td>
            </tr>
            <tr>
                <td width="5%">1.</td>
                <td width="28%">Cuti Tahunan</td>
                <td width="5%">2.</td>
                <td width="28%">Cuti Besar</td>
                <td colspan="2" width="34%">
                    @if($surat->jenis_cuti->nama == 'Cuti Tahunan')
                    <div class="checkbox">✓</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Cuti Sakit</td>
                <td>4.</td>
                <td>Cuti Melahirkan</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Cuti Alasan Penting</td>
                <td>6.</td>
                <td>Cuti di Luar Tanggungan Negara</td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="2">III. ALASAN CUTI</td>
            </tr>
            <tr>
                <td colspan="2">{{ $surat->alasan }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="4">IV. LAMANYA CUTI</td>
            </tr>
            <tr>
                <td width="15%">Selama</td>
                <td width="35%">{{ $surat->lama_hari }} hari</td>
                <td width="25%">Mulai tanggal</td>
                <td width="25%">{{ date('d F Y', strtotime($surat->tanggal_mulai)) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="6">V. CATATAN CUTI</td>
            </tr>
            <tr>
                <td colspan="2">1. CUTI TAHUNAN</td>
                <td>2.CUTI BESAR</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>Tahun</td>
                <td>Sisa</td>
                <td>3.CUTI SAKIT</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>{{ date('Y') }}</td>
                <td>{{ $sisa_cuti }}</td>
                <td>4.CUTI MELAHIRKAN</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>{{ date('Y')-1 }}</td>
                <td>{{ $sisa_cuti_tahun_lalu }}</td>
                <td>5.CUTI KARENA ALASAN PENTING</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>6.CUTI DI LUAR TANGGUNGAN NEGARA</td>
                <td colspan="3"></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="3">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
            </tr>
            <tr>
                <td width="40%">{{ $surat->alamat_cuti }}</td>
                <td width="30%">TELP<br>{{ $surat->telepon }}</td>
                <td width="30%">
                    Hormat saya,<br><br><br><br>
                    {{ $surat->pengaju->nama }}<br>
                    NIP. {{ $surat->pengaju->nip }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG</td>
            </tr>
            <tr>
                <td width="25%">DISETUJUI</td>
                <td width="25%">PERUBAHAN</td>
                <td width="25%">DITANGGUHKAN</td>
                <td width="25%">TIDAK DISETUJUI</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>
                    Atasan Langsung<br><br><br><br>
                    {{ $atasan->nama }}<br>
                    NIP. {{ $atasan->nip }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</td>
            </tr>
            <tr>
                <td width="25%">DISETUJUI</td>
                <td width="25%">PERUBAHAN</td>
                <td width="25%">DITANGGUHKAN</td>
                <td width="25%">TIDAK DISETUJUI</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>
                    KEPALA DINAS KESEHATAN DAERAH<br>
                    KABUPATEN PURWOREJO<br><br><br><br>
                    {{ $kepala_dinas->nama }}<br>
                    NIP. {{ $kepala_dinas->nip }}
                </td>
            </tr>
        </table>
    </div>
    <a href="{{ route('surat-cuti.pdf', $suratCuti->id) }}" target="_blank" class="btn btn-primary">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
</body>
</html>
