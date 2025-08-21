<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permintaan dan Pemberian Cuti</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            color: #000;
        }
        
        .header {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .letter-header {
            margin-bottom: 30px;
        }
        
        .center {
            text-align: center;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .underline {
            text-decoration: underline;
        }
        
        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 20px 0;
            text-decoration: underline;
        }
        
        .form-number {
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        
        th, td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        
        .section-header {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .checkbox-table td {
            padding: 3px 8px;
            text-align: left;
        }
        
        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        .checkbox.checked::after {
            content: '✓';
            display: block;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 20px;
        }
        
        .approval-section {
            margin-top: 20px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .no-border {
            border: none !important;
        }
        
        .fill-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <p>{{ $location ?? 'Purworejo' }}, {{ $date ?? $suratCuti->tanggal_ajuan->format('d F Y') }}</p>
        <br>
        <p>Kepada</p>
        <p>Yth. Kepala Dinas Kesehatan Daerah</p>
        <p>Kabupaten Purworejo</p>
        <p>Di --</p>
        <p><span class="underline"><strong>PURWOREJO</strong></span></p>
    </div>

    <!-- Form Title -->
    <div class="form-title">
        PERMINTAAN DAN PEMBERIAN CUTI
    </div>
    
    <div class="form-number">
        Nomor : {{ $nomor_surat ?? '800.1.11.4/' . str_pad($suratCuti->id, 3, '0', STR_PAD_LEFT) . '/2025' }}
    </div>

    <!-- Section I: Data Pegawai -->
    <table>
        <thead>
            <tr>
                <th colspan="4" class="section-header">I. DATA PEGAWAI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Nama</strong></td>
                <td>{{ $suratCuti->pengaju->nama }}</td>
                <td><strong>NIP</strong></td>
                <td>{{ $suratCuti->pengaju->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Jabatan</strong></td>
                <td>{{ $suratCuti->pengaju->jabatan }}</td>
                <td><strong>Masa Kerja</strong></td>
                <td>{{ $masa_kerja ?? $suratCuti->pengaju->masa_kerja ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Unit Kerja</strong></td>
                <td>{{ $suratCuti->pengaju->unit_kerja }}</td>
                <td><strong>Gol./Ruang</strong></td>
                <td>{{ $suratCuti->pengaju->golongan ?? $suratCuti->pengaju->pangkat ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section II: Jenis Cuti -->
    <table class="checkbox-table">
        <thead>
            <tr>
                <th colspan="4" class="section-header">II. JENIS CUTI YANG DIAMBIL<br>(ceklist yang dipilih pemohon)</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>

    <!-- Section III: Alasan Cuti -->
    <table>
        <thead>
            <tr>
                <th class="section-header">III. ALASAN CUTI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 60px; vertical-align: top;">
                    {{ $suratCuti->alasan }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Section IV: Lamanya Cuti -->
    <table>
        <thead>
            <tr>
                <th colspan="4" class="section-header">IV. LAMANYA CUTI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Selama</strong></td>
                <td>{{ $suratCuti->jumlah_hari }} hari</td>
                <td><strong>Mulai tanggal</strong></td>
                <td>{{ $suratCuti->tanggal_awal->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><strong>s/d</strong></td>
                <td>{{ $suratCuti->tanggal_akhir->format('d/m/Y') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section V: Catatan Cuti -->
    <table>
        <thead>
            <tr>
                <th colspan="3" class="section-header">V. CATATAN CUTI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="4" style="width: 30%; vertical-align: top;">
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
                            <td style="border: none; padding: 2px;">{{ $sisaCutiData[2025] ?? ($sisa_2025 ?? '') }}</td>
                            <td style="border: none; padding: 2px;">{{ $ket_2025 ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 35%;"><strong>2. CUTI BESAR</strong></td>
                <td style="width: 35%;"></td>
            </tr>
            <tr>
                <td><strong>3. CUTI SAKIT</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>4. CUTI MELAHIRKAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>5. CUTI KARENA ALASAN PENTING</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Section VI: Alamat Selama Cuti -->
    <table>
        <thead>
            <tr>
                <th colspan="3" class="section-header">VI. ALAMAT SELAMA MENJALANKAN CUTI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="2" style="width: 50%; height: 80px; vertical-align: top;">
                    {{ $alamat_cuti ?? $suratCuti->pengaju->alamat ?? '' }}
                </td>
                <td style="width: 20%;"><strong>TELP</strong></td>
                <td style="width: 30%;">{{ $telepon ?? $suratCuti->pengaju->telepon ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; vertical-align: bottom;">
                    <p>Hormat saya,</p>
                    <br><br><br>
                    <p><strong>{{ $suratCuti->pengaju->nama }}</strong></p>
                    <p>{{ $suratCuti->pengaju->nip ? 'NIP. ' . $suratCuti->pengaju->nip : 'PPPK' }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Section VII: Pertimbangan Atasan Langsung -->
    <table>
        <thead>
            <tr>
                <th colspan="4" class="section-header">VII. PERTIMBANGAN ATASAN LANGSUNG</th>
            </tr>
        </thead>
        <tbody>
            @php
                $atasanLangsung = $disposisiList->where('jabatan', 'like', '%Kepala Puskesmas%')->first() ??
                                 $disposisiList->where('jabatan', 'like', '%Kepala%')->first();
                $keputusanAtasan = $atasanLangsung ? ($atasanLangsung->status === 'sudah' ? 'disetujui' : 'pending') : 'pending';
            @endphp
            <tr>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanAtasan == 'disetujui' ? 'checked' : '' }}"></span>
                    <strong>DISETUJUI</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanAtasan == 'perubahan' ? 'checked' : '' }}"></span>
                    <strong>PERUBAHAN</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanAtasan == 'ditangguhkan' ? 'checked' : '' }}"></span>
                    <strong>DITANGGUHKAN</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanAtasan == 'tidak_disetujui' ? 'checked' : '' }}"></span>
                    <strong>TIDAK DISETUJUI</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="height: 120px; vertical-align: bottom; text-align: right;">
                    <p>Atasan Langsung</p>
                    @php
                        $atasanSignature = null;
                        if ($atasanLangsung) {
                            $atasanSignature = \App\Models\Signature::getByJabatan($atasanLangsung->jabatan);
                        }
                    @endphp

                    @if($atasanSignature && $atasanSignature->signature_path && $atasanLangsung->status === 'sudah')
                        <div style="margin: 10px 0;">
                            <img src="{{ public_path($atasanSignature->signature_path) }}"
                                 alt="Signature"
                                 style="max-height: 60px; max-width: 150px;">
                        </div>
                    @else
                        <br><br>
                    @endif

                    <p><strong>{{ ($atasanLangsung && $atasanLangsung->user) ? $atasanLangsung->user->nama : ($atasanSignature ? $atasanSignature->nama : '(_________________________)') }}</strong></p>
                    <p>{{ ($atasanLangsung && $atasanLangsung->user && $atasanLangsung->user->nip) ? 'NIP. ' . $atasanLangsung->user->nip : ($atasanSignature && $atasanSignature->nip ? 'NIP. ' . $atasanSignature->nip : 'NIP. ___________________') }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Section VIII: Keputusan Pejabat yang Berwenang -->
    <table>
        <thead>
            <tr>
                <th colspan="4" class="section-header">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI</th>
            </tr>
        </thead>
        <tbody>
            @php
                $kadin = $disposisiList->where('jabatan', 'KADIN')->first();
                $keputusanKepala = $kadin ? ($kadin->status === 'sudah' ? 'disetujui' : 'pending') : 'pending';
            @endphp
            <tr>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanKepala == 'disetujui' ? 'checked' : '' }}"></span>
                    <strong>DISETUJUI</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanKepala == 'perubahan' ? 'checked' : '' }}"></span>
                    <strong>PERUBAHAN</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanKepala == 'ditangguhkan' ? 'checked' : '' }}"></span>
                    <strong>DITANGGUHKAN</strong>
                </td>
                <td style="width: 25%;">
                    <span class="checkbox {{ $keputusanKepala == 'tidak_disetujui' ? 'checked' : '' }}"></span>
                    <strong>TIDAK DISETUJUI</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="height: 150px; vertical-align: bottom; text-align: right;">
                    <p><strong>KEPALA DINAS KESEHATAN DAERAH</strong></p>
                    <p><strong>KABUPATEN PURWOREJO</strong></p>

                    @php
                        $kadinSignature = \App\Models\Signature::getByJabatan('KADIN');
                    @endphp

                    @if($kadinSignature && ($kadinSignature->signature_path || $kadinSignature->stamp_path) && $kadin && $kadin->status === 'sudah')
                        <div style="margin: 15px 0; position: relative; height: 80px;">
                            @if($kadinSignature->signature_path)
                                <img src="{{ public_path($kadinSignature->signature_path) }}"
                                     alt="Signature"
                                     style="max-height: 80px; max-width: 200px; position: absolute; right: 0;">
                            @endif
                            @if($kadinSignature->stamp_path)
                                <img src="{{ public_path($kadinSignature->stamp_path) }}"
                                     alt="Stamp"
                                     style="max-height: 80px; max-width: 200px; position: absolute; right: 50px; top: -10px; opacity: 0.8;">
                            @endif
                        </div>
                    @else
                        <br>
                        <p>(tanda tangan dan cap)</p>
                        <br>
                    @endif

                    <p><strong>{{ ($kadin && $kadin->user) ? $kadin->user->nama : ($kadinSignature ? $kadinSignature->nama : 'dr. Sudarmi, MM') }}</strong></p>
                    <p>{{ ($kadin && $kadin->user && $kadin->user->nip) ? 'NIP. ' . $kadin->user->nip : ($kadinSignature && $kadinSignature->nip ? 'NIP. ' . $kadinSignature->nip : 'NIP. 19690220 200212 2 004') }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
