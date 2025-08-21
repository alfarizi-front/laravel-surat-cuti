<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti - {{ $puskesmas->nama_puskesmas ?? 'Puskesmas' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.4; margin: 20px; }
        .header-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .header-left, .header-right { width: 45%; }
        .header-right { text-align: right; }
        .title-center { text-align: center; font-weight: bold; font-size: 14pt; margin: 20px 0; text-decoration: underline; }
        .nomor-section { text-align: center; margin-bottom: 20px; }
        .content-section { margin-bottom: 15px; }
        .section-title { font-weight: bold; margin-bottom: 10px; }
        .data-row { display: flex; margin-bottom: 5px; }
        .data-label { width: 150px; }
        .data-colon { width: 20px; }
        .data-value { flex: 1; }
        .checkbox-section { margin: 10px 0; }
        .checkbox-row { margin: 5px 0; display: flex; align-items: center; }
        .checkbox { display: inline-block; width: 15px; height: 15px; border: 1px solid #000; margin-right: 10px; text-align: center; line-height: 13px; font-size: 10pt; }
        .checkbox.checked { background-color: #000; color: white; }
        .cuti-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .cuti-table th, .cuti-table td { border: 1px solid #000; padding: 5px; text-align: center; }
        .cuti-table th { background-color: #f0f0f0; font-weight: bold; }
        .status-options { margin: 10px 0; }
        .status-option { display: inline-block; margin-right: 20px; margin-bottom: 5px; }
        .signature-section { margin-top: 30px; }
        .signature-grid { display: flex; justify-content: space-between; }
        .signature-cell { width: 30%; text-align: center; }
        .signature-box { height: 60px; margin: 10px 0; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        .puskesmas-header { background-color: #f8f9fa; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; border-radius: 5px; }
        .puskesmas-info { display: flex; justify-content: space-between; }
        .puskesmas-left, .puskesmas-right { width: 48%; }
        @media print {
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>
@php
    // Data Puskesmas
    $puskesmasNama = $puskesmas->nama_puskesmas ?? 'Puskesmas';
    $puskesmasAlamat = $puskesmas->alamat ?? '';
    $kepalaPuskesmas = $puskesmas->kepala_puskesmas_lengkap ?? $puskesmas->kepala_puskesmas ?? '[Kepala Puskesmas]';
    $nipKepala = $puskesmas->nip_kepala ?? '[NIP Kepala]';
    $teleponPuskesmas = $puskesmas->telepon ?? '';

    // Data umum
    $tempat = $tempat ?? 'Purworejo';
    $tanggalSurat = $tanggal_surat ?? now()->format('d F Y');
    $nomorSurat = $nomor_surat ?? ('445/' . ($puskesmas->kode_puskesmas ?? 'XXX') . '/___/' . now()->format('Y'));

    // Data pegawai
    $namaPegawai = $nama_pegawai ?? ($pegawai->name ?? '[Nama Lengkap]');
    $nipPegawai = $nip_pegawai ?? ($pegawai->nip ?? '[NIP Pegawai]');
    $jabatan = $jabatan ?? ($pegawai->jabatan ?? '[Jabatan]');
    $masaKerja = $masa_kerja ?? ($pegawai->masa_kerja ?? '[Tahun] Tahun [Bulan] Bulan');
    $unitKerja = $unit_kerja ?? ($pegawai->unit_kerja ?? $puskesmasNama);
    $golongan = $golongan ?? ($pegawai->golongan ?? '[Golongan/Ruang]');

    // Data cuti
    $jenisCuti = $jenis_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->jenisCuti)) ? $surat_cuti->jenisCuti->nama : null);
    $alasanCuti = $alasan_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->alasan)) ? $surat_cuti->alasan : '[Alasan]');
    $lamaCuti = $lama_cuti ?? ((isset($surat_cuti) && isset($surat_cuti->jumlah_hari)) ? $surat_cuti->jumlah_hari : '[Jumlah Hari]');

    $tanggalMulai = $tanggal_mulai ?? ((isset($surat_cuti) && isset($surat_cuti->tanggal_awal)) ? (\Carbon\Carbon::parse($surat_cuti->tanggal_awal)->translatedFormat('d F Y')) : '[Tanggal Mulai]');
    $tanggalSelesai = $tanggal_selesai ?? ((isset($surat_cuti) && isset($surat_cuti->tanggal_akhir)) ? (\Carbon\Carbon::parse($surat_cuti->tanggal_akhir)->translatedFormat('d F Y')) : '[Tanggal Selesai]');
@endphp
</body>
</html>