<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan dan Pemberian Cuti</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            margin: 40px;
            line-height: 1.5;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mt-20 { margin-top: 20px; }
        .mt-40 { margin-top: 40px; }
        .mb-20 { margin-bottom: 20px; }
        .signature-block {
            width: 50%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 5px;
        }
        .form-section {
            margin-top: 15px;
        }
        .form-section td:first-child {
            width: 3%;
        }
        .form-section td:nth-child(2) {
            width: 40%;
        }
        .checkbox {
            display: inline-block;
            border: 1px solid black;
            width: 10px;
            height: 10px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div class="text-right">Purworejo, 5 Agustus 2025</div>
    
    <p>Kepada</p>
    <p class="bold">Yth. Kepala Dinas Kesehatan Daerah<br>
    Kabupaten Purworejo</p>
    <p>Di – <br><span class="bold">PURWOREJO</span></p>

    <div class="text-center mt-20 mb-20">
        <span class="bold underline">PERMINTAAN DAN PEMBERIAN CUTI</span><br>
        Nomor : 800.1.11.4/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2025
    </div>

    <div class="form-section">
        <p class="bold">I. DATA PEGAWAI</p>
        <table>
            <tr><td>Nama</td><td>: Umi Setyawati, AMKg</td></tr>
            <tr><td>NIP</td><td>: 19870223 200902 2 004</td></tr>
            <tr><td>Jabatan</td><td>: Pengelola Kepegawaian</td></tr>
            <tr><td>Masa Kerja</td><td>: 14 Tahun 06 Bulan</td></tr>
            <tr><td>Unit Kerja</td><td>: Sub Bag Umum dan Kepegawaian, Dinas Kesehatan Daerah Kabupaten Purworejo</td></tr>
            <tr><td>Gol./Ruang</td><td>: III/c</td></tr>
        </table>
    </div>

    <div class="form-section">
        <p class="bold">II. JENIS CUTI YANG DIAMBIL</p>
        <table>
            <tr>
                <td class="checkbox" style="background: black;"></td><td>1. Cuti Tahunan</td>
                <td class="checkbox"></td><td>2. Cuti Besar</td>
            </tr>
            <tr>
                <td class="checkbox"></td><td>3. Cuti Sakit</td>
                <td class="checkbox"></td><td>4. Cuti Melahirkan</td>
            </tr>
            <tr>
                <td class="checkbox"></td><td>5. Cuti Alasan Penting</td>
                <td class="checkbox"></td><td>6. Cuti di Luar Tanggungan Negara</td>
            </tr>
        </table>
    </div>

    <div class="form-section">
        <p class="bold">III. ALASAN CUTI</p>
        <p>Kepentingan keluarga</p>
    </div>

    <div class="form-section">
        <p class="bold">IV. LAMANYA CUTI</p>
        <p>Selama <b>1 hari</b> mulai tanggal <b>6 Agustus 2025</b> s/d <b>6 Agustus 2025</b></p>
    </div>

    <div class="form-section">
        <p class="bold">V. CATATAN CUTI</p>
        <table>
            <tr><td>2023</td><td>: CUTI TAHUNAN</td></tr>
            <tr><td>2024</td><td>: 3 hari</td></tr>
            <tr><td>2025</td><td>: 12 hari</td></tr>
        </table>
    </div>

    <div class="form-section">
        <p class="bold">VI. ALAMAT SELAMA MENJALANKAN CUTI</p>
        <p>Kledung Karangdalem, RT 3 RW 1, Kec. Banyuurip, Kab. Purworejo<br>
        TELP: 085292678023</p>
    </div>

    <div class="signature-block">
        Hormat saya,<br><br><br><br>
        <b>Umi Setyawati, AMKg</b><br>
        NIP. 19870223 200902 2 004
    </div>

    <div class="form-section mt-40">
        <p class="bold">VII. PERTIMBANGAN ATASAN LANGSUNG</p>
        <p>
            <span class="checkbox" style="background: black;"></span> DISETUJUI &nbsp;&nbsp;
            <span class="checkbox"></span> PERUBAHAN &nbsp;&nbsp;
            <span class="checkbox"></span> DITANGGUHKAN &nbsp;&nbsp;
            <span class="checkbox"></span> TIDAK DISETUJUI
        </p>

        <div class="signature-block">
            Atasan Langsung<br><br><br><br>
            <b>Taufik Anggoro, S.IP</b><br>
            NIP. 19710404 199403 1 003
        </div>
    </div>

    <div class="form-section mt-40">
        <p class="bold">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</p>
        <p>
            <span class="checkbox" style="background: black;"></span> DISETUJUI &nbsp;&nbsp;
            <span class="checkbox"></span> PERUBAHAN &nbsp;&nbsp;
            <span class="checkbox"></span> DITANGGUHKAN &nbsp;&nbsp;
            <span class="checkbox"></span> TIDAK DISETUJUI
        </p>

        <div class="signature-block">
            Kepala Dinas Kesehatan Daerah<br>
            Kabupaten Purworejo<br>

            <!-- Tanda Tangan dan Cap Kepala Dinas (KADIN) -->
            <div style="min-height: 120px; margin: 20px 0; position: relative;">
                @php
                    // Cari user dengan role kadin (Kepala Dinas)
                    $kadinUser = \App\Models\User::where('role', 'kadin')
                                                 ->orWhere('jabatan', 'like', '%Kepala Dinas%')
                                                 ->first();

                    $hasSignature = $kadinUser && $kadinUser->tanda_tangan && file_exists(public_path('storage/' . $kadinUser->tanda_tangan));
                    $hasCap = $kadinUser && $kadinUser->cap_stempel && $kadinUser->gunakan_cap && file_exists(public_path('storage/' . $kadinUser->cap_stempel));
                @endphp

                @if($hasSignature || $hasCap)
                    <div style="text-align: center;">
                        <!-- Cap/Stempel KADIN (di atas) -->
                        @if($hasCap)
                            @php
                                $capPath = public_path('storage/' . $kadinUser->cap_stempel);
                                $capImageData = base64_encode(file_get_contents($capPath));
                                $capImageMime = mime_content_type($capPath);
                                $capBase64Image = "data:{$capImageMime};base64,{$capImageData}";
                            @endphp
                            <div style="margin-bottom: 10px;">
                                <img src="{{ $capBase64Image }}"
                                     alt="Cap/Stempel Kepala Dinas"
                                     style="max-width: 120px; max-height: 60px; object-fit: contain;">
                            </div>
                        @endif

                        <!-- Tanda Tangan KADIN (di bawah cap) -->
                        @if($hasSignature)
                            @php
                                $signaturePath = public_path('storage/' . $kadinUser->tanda_tangan);
                                $signatureImageData = base64_encode(file_get_contents($signaturePath));
                                $signatureImageMime = mime_content_type($signaturePath);
                                $signatureBase64Image = "data:{$signatureImageMime};base64,{$signatureImageData}";
                            @endphp
                            <div>
                                <img src="{{ $signatureBase64Image }}"
                                     alt="Tanda Tangan Kepala Dinas"
                                     style="max-width: 150px; max-height: 70px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                @else
                    <div style="font-size: 10pt; color: #666; font-style: italic; text-align: center; padding: 30px 0;">
                        (Tanda tangan dan cap digital)
                    </div>
                @endif
            </div>

            <!-- Nama dan NIP Kepala Dinas -->
            @if($kadinUser)
                <b>{{ $kadinUser->nama }}</b><br>
                @if($kadinUser->nip)
                    NIP. {{ $kadinUser->nip }}
                @else
                    {{ $kadinUser->jabatan }}
                @endif
            @else
                <b>dr. Sudarmi, MM</b><br>
                NIP. 19690220 200212 2 004
            @endif
        </div>
    </div>

</body>
</html>
