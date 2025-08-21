<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SURAT KETERANGAN PEGAWAI PUSKESMAS</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; color: #000; }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto 5px auto; }
        .title { text-align: center; font-weight: bold; text-transform: uppercase; margin: 10px 0 20px 0; }
        .meta { text-align: center; font-size: 11pt; margin-bottom: 10px; }
        .content { margin-top: 10px; }
        table.info { width: 100%; border-collapse: collapse; margin: 10px 0 18px 0; }
        table.info td { padding: 6px 4px; vertical-align: top; }
        table.info td:first-child { width: 28%; }
        table.info td:nth-child(2) { width: 2%; text-align: center; }
        p { text-align: justify; margin: 0 0 10px 0; line-height: 1.6; }
        .signature { margin-top: 40px; width: 100%; }
        .signature .right { width: 45%; float: right; text-align: center; }
        .signature .sig-box { min-height: 110px; position: relative; }
        .signature img.sig { max-height: 70px; max-width: 160px; object-fit: contain; display: block; margin: 0 auto; }
        .signature img.cap { max-height: 60px; max-width: 120px; object-fit: contain; display: block; margin: 0 auto 6px auto; }
        .clearfix { clear: both; }
        .footer { position: fixed; bottom: 22px; left: 0; right: 0; text-align: center; font-size: 10pt; color: #444; }
        .small { font-size: 10pt; }
        hr.sep { border: 0; border-top: 1px solid #000; margin: 6px 0 0 0; }
    </style>
</head>
<body>
    <!-- Header with Logo and Identity -->
    <div class="header">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" alt="Logo Puskesmas" class="logo">
        @else
            <div class="small" style="margin-bottom: 6px;">[Logo Puskesmas]</div>
        @endif
        <div class="meta">
            <div><strong>PUSKESMAS</strong> {{ $namaPuskesmas }}</div>
            <div class="small">Pemerintah Daerah - Dinas Kesehatan</div>
            <hr class="sep">
        </div>
    </div>

    <!-- Title -->
    <div class="title">SURAT KETERANGAN PEGAWAI PUSKESMAS</div>

    <!-- Employee Data Table -->
    <div class="content">
        <table class="info">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $pegawai->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $pegawai->nip ?: '-' }}</td>
            </tr>
            <tr>
                <td>Status Pegawai</td>
                <td>:</td>
                <td>{{ $statusPegawai }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pegawai->jabatan }}</td>
            </tr>
            <tr>
                <td>Nama Puskesmas</td>
                <td>:</td>
                <td>{{ $namaPuskesmas }}</td>
            </tr>
        </table>

        <!-- Short Statement Paragraph -->
        <p>
            Yang bertanda tangan di bawah ini menerangkan bahwa pegawai sebagaimana identitas tersebut di atas benar
            bertugas pada {{ $namaPuskesmas }} dengan status kepegawaian <strong>{{ $statusPegawai }}</strong> dan
            jabatan <strong>{{ $pegawai->jabatan }}</strong>. Surat keterangan ini dibuat untuk keperluan administrasi
            dan dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- Signature Block (Right aligned) -->
    <div class="signature">
        <div class="right">
            <div>Purworejo, {{ $tanggalCetak->format('d F Y') }}</div>
            <div>Kepala Puskesmas</div>
            <div class="sig-box">
                @if(!empty($kapusCapBase64))
                    <img src="{{ $kapusCapBase64 }}" alt="Cap Stempel" class="cap">
                @endif
                @if(!empty($kapusSignatureBase64))
                    <img src="{{ $kapusSignatureBase64 }}" alt="Tanda Tangan" class="sig">
                @else
                    <div class="small" style="position:absolute; bottom: 6px; left:0; right:0; text-align:center; color:#777;">(Tanda tangan)</div>
                @endif
            </div>
            <div style="margin-top: 4px;">
                <strong>{{ $kepalaPuskesmas?->nama ?: '-' }}</strong><br>
                <span class="small">NIP. {{ $kepalaPuskesmas?->nip ?: '-' }}</span>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <!-- Footer / Notes -->
    <div class="footer">
        Dicetak pada: {{ $tanggalCetak->format('d F Y, H:i') }}
    </div>
</body>
</html>
