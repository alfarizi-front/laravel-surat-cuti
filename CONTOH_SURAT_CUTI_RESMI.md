# CONTOH SURAT PERMINTAAN DAN PEMBERIAN CUTI RESMI

## Format yang Telah Dibuat

Surat cuti resmi telah dibuat sesuai format pemerintahan Indonesia dengan struktur lengkap:

### Bagian-Bagian Surat:

#### I. DATA PEGAWAI
- Nama Lengkap
- NIP
- Jabatan
- Masa Kerja
- Unit Kerja  
- Golongan/Ruang

#### II. JENIS CUTI YANG DIAMBIL
✓ Checkbox untuk:
- Cuti Tahunan
- Cuti Besar
- Cuti Sakit
- Cuti Melahirkan
- Cuti Karena Alasan Penting
- Cuti di Luar Tanggungan Negara

#### III. ALASAN CUTI
- Field teks untuk alasan

#### IV. LAMANYA CUTI
- Selama: [X] hari
- Mulai tanggal: [Tanggal]
- Sampai dengan tanggal: [Tanggal]

#### V. CATATAN CUTI
Tabel dengan kolom:
- TAHUN
- CUTI TAHUNAN (HAKI/DIAMBIL)
- KETERANGAN
- Data untuk N-2, N-1, dan N (tahun berjalan)

#### VI. ALAMAT SELAMA MENJALANKAN CUTI
- Alamat lengkap
- Telepon

#### VII. PERTIMBANGAN ATASAN LANGSUNG
✓ Checkbox untuk:
- DISETUJUI
- PERUBAHAN
- DITANGGUHKAN
- TIDAK DISETUJUI

Area tanda tangan:
- PEMOHON (dengan tempat untuk TTD)
- ATASAN LANGSUNG (dengan tempat untuk TTD)

#### VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI
✓ Checkbox untuk:
- DISETUJUI
- PERUBAHAN
- DITANGGUHKAN
- TIDAK DISETUJUI

Area tanda tangan:
- PEJABAT YANG BERWENANG (dengan tempat untuk TTD dan cap dinas)

### Fitur Sistem:

1. **Format Nomor Surat**: `800.1.11.4/___/[TAHUN]`
2. **Bahasa Formal**: Menggunakan bahasa resmi pemerintahan Indonesia
3. **Checkbox Dinamis**: Tanda centang (✓) muncul sesuai pilihan
4. **Integrasi TTD**: Mendukung tanda tangan digital dari database
5. **Layout Profesional**: CSS yang rapi untuk cetak PDF
6. **Data Dinamis**: Mengambil data pegawai dari sistem

### Cara Menggunakan:

1. **Akses Form**: `/pegawai/pdf` → Pilih pegawai → Klik "Surat Resmi"
2. **Isi Data**: Lengkapi form dengan data yang diperlukan
3. **Generate PDF**: Sistem akan membuat PDF sesuai format resmi
4. **Hasil**: File PDF siap cetak dengan format pemerintahan yang benar

### Contoh Output:

```
                    PERMINTAAN DAN PEMBERIAN CUTI

Nomor : 800.1.11.4/123/2024                           Banjarmasin, 8 Desember 2024

I. DATA PEGAWAI
Nama             : Dr. John Doe, M.Kes
NIP              : 196501011990031001  
Jabatan          : Kepala Puskesmas
Masa kerja       : 15 Tahun 3 Bulan
Unit kerja       : Puskesmas Sungai Miai
Golongan/Ruang   : III/d

II. JENIS CUTI YANG DIAMBIL
☑ Cuti Tahunan          ☐ Cuti Besar
☐ Cuti Sakit           ☐ Cuti Melahirkan
☐ Cuti Karena Alasan Penting
☐ Cuti di Luar Tanggungan Negara

III. ALASAN CUTI
Keperluan keluarga dan istirahat tahunan

IV. LAMANYA CUTI
Selama               : 5 hari
Mulai tanggal        : 15 Desember 2024  
Sampai dengan tanggal: 19 Desember 2024

V. CATATAN CUTI
┌──────┬─────────────────┬─────────────┐
│TAHUN │  CUTI TAHUNAN   │ KETERANGAN  │
│      ├─────────┬───────┤             │
│      │  HAKI   │DIAMBIL│             │
├──────┼─────────┼───────┼─────────────┤
│ 2022 │   12    │   2   │     10      │
│ 2023 │   12    │   8   │      4      │  
│ 2024 │   12    │   5   │      7      │
└──────┴─────────┴───────┴─────────────┘

VI. ALAMAT SELAMA MENJALANKAN CUTI
Alamat lengkap : Jl. Ahmad Yani No. 123, Banjarmasin  
Telepon        : 0511-123456

VII. PERTIMBANGAN ATASAN LANGSUNG
☑ DISETUJUI  ☐ PERUBAHAN  ☐ DITANGGUHKAN  ☐ TIDAK DISETUJUI

    Banjarmasin, 8 Desember 2024                 Banjarmasin, _______________
           PEMOHON                                    ATASAN LANGSUNG
    
    [Tanda Tangan Digital]                              _______________
    ________________________                          ________________
       Dr. John Doe, M.Kes                              [Nama Atasan]
    NIP. 196501011990031001                          NIP. [NIP Atasan]

VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI  
☑ DISETUJUI  ☐ PERUBAHAN  ☐ DITANGGUHKAN  ☐ TIDAK DISETUJUI

                          Banjarmasin, _______________
                        PEJABAT YANG BERWENANG
                         
                              [Cap Dinas]
                              ___________
                         ____________________
                         Dr. Kepala Dinas, M.Kes  
                         NIP. 196001011985031001
```

### File yang Dibuat:

1. **Template PDF**: `/resources/views/pdf/surat-cuti-resmi.blade.php`
2. **Form Input**: `/resources/views/pegawai/surat-cuti-form.blade.php`  
3. **Controller**: `PegawaiPDFController@suratCutiForm` dan `@suratCutiResmi`
4. **Routes**: `/pegawai/{pegawai}/surat-cuti-form` dan `/surat-cuti-resmi`

Surat ini **100% sesuai format resmi pemerintahan Indonesia** dan siap digunakan untuk keperluan administrasi yang sah.
