# 📋 Dokumentasi Workflow Persetujuan Sekretariat

## 🎯 Overview

Sistem persetujuan cuti untuk divisi **Sekretariat** menggunakan **Conditional Approval Logic** yang memungkinkan efisiensi dalam proses persetujuan dengan tetap menjaga kontrol yang ketat.

## 🔄 Alur Persetujuan Sekretariat

### Ketentuan Workflow:
1. **Kasubag** (paraf) - Persetujuan awal dari kepala sub bagian
2. **Umpeg/Perencanaan Keu** (paraf) - **Conditional Logic**: Jika salah satu sudah disetujui, yang satunya otomatis ikut tersetujui
3. **Sekdin** (paraf) - Persetujuan dari Sekretaris Dinas
4. **KADIN** (tanda tangan) - Persetujuan final dari Kepala Dinas

### 🤖 Conditional Logic

**Aturan Khusus untuk Umpeg/Perencanaan Keu:**
- Jika **Kasubag Umpeg** menyetujui → **Kasubag Perencanaan Keu** otomatis disetujui
- Jika **Kasubag Perencanaan Keu** menyetujui → **Kasubag Umpeg** otomatis disetujui
- Sistem akan mencatat bahwa persetujuan dilakukan secara otomatis
- Audit trail tetap lengkap dengan keterangan conditional approval

## 💻 Implementasi Teknis

### 1. Database Structure

**Tabel: `alur_cuti`**
```sql
-- Workflow Sekretariat
INSERT INTO alur_cuti VALUES 
(1, 'Sekretariat', 1, 'Kasubag', 'paraf', 1),
(2, 'Sekretariat', 2, 'Kasubag Umpeg', 'paraf', 2),
(3, 'Sekretariat', 3, 'Kasubag Perencanaan Keu', 'paraf', 3),
(4, 'Sekretariat', 4, 'Sekretaris Dinas', 'paraf', 4),
(5, 'Sekretariat', 5, 'KADIN', 'ttd', 5);
```

### 2. Controller Logic

**File: `app/Http/Controllers/DisposisiController.php`**

```php
/**
 * Apply conditional logic for Sekretariat workflow
 * If Umpeg OR Perencanaan Keu approves, auto-approve the other
 */
private function applyConditionalLogic(DisposisiCuti $disposisi, SuratCuti $suratCuti): void
{
    if ($suratCuti->pengaju->unit_kerja !== 'Sekretariat') {
        return;
    }

    $conditionalRoles = ['Kasubag Umpeg', 'Kasubag Perencanaan Keu'];
    
    if (!in_array($disposisi->jabatan, $conditionalRoles)) {
        return;
    }

    $otherRole = $disposisi->jabatan === 'Kasubag Umpeg' 
               ? 'Kasubag Perencanaan Keu' 
               : 'Kasubag Umpeg';

    $updated = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                           ->where('jabatan', $otherRole)
                           ->where('status', 'pending')
                           ->update([
                               'status' => 'sudah',
                               'tanggal' => now(),
                               'catatan' => "Otomatis disetujui karena {$disposisi->jabatan} telah menyetujui"
                           ]);
}
```

### 3. User Interface

**Enhanced View Features:**
- ✅ Indikator conditional logic di header disposisi
- ✅ Icon robot untuk approval otomatis
- ✅ Badge "Auto" untuk disposisi otomatis
- ✅ Styling khusus untuk catatan conditional approval
- ✅ Info tooltip untuk pending conditional disposisi

## 🧪 Testing & Verification

### Test Scenario 1: Umpeg Approves First
```bash
php test_sekretariat_workflow.php
```
**Expected Result:**
- Umpeg approve → Perencanaan Keu auto-approve
- Catatan: "Otomatis disetujui karena Kasubag Umpeg telah menyetujui"

### Test Scenario 2: Perencanaan Keu Approves First
```bash
php test_sekretariat_reverse.php
```
**Expected Result:**
- Perencanaan Keu approve → Umpeg auto-approve
- Catatan: "Otomatis disetujui karena Kasubag Perencanaan Keu telah menyetujui"

### Demo Lengkap
```bash
php demo_sekretariat_workflow.php
```

## 📊 Keunggulan Sistem

### ✅ Efisiensi
- Mengurangi waktu tunggu approval
- Tidak perlu koordinasi manual antara Umpeg dan Perencanaan Keu
- Proses lebih cepat tanpa mengurangi kontrol

### ✅ Audit Trail Lengkap
- Semua approval tercatat dengan timestamp
- Catatan jelas untuk approval otomatis
- History lengkap untuk compliance

### ✅ Fleksibilitas
- Approval bisa dilakukan dalam urutan apa saja
- Sistem otomatis mendeteksi completion
- Tidak ada dependency sequence yang kaku

### ✅ User Experience
- Interface yang jelas dengan indikator visual
- Real-time status update
- Informasi conditional logic yang transparan

## 🔧 Configuration

### Menambah Unit Kerja Baru dengan Conditional Logic

1. **Update AlurCutiSeeder:**
```php
// Contoh untuk unit baru
['unit_kerja' => 'Unit Baru', 'step_ke' => 2, 'jabatan' => 'Role A', 'tipe_disposisi' => 'paraf', 'urutan' => 2],
['unit_kerja' => 'Unit Baru', 'step_ke' => 3, 'jabatan' => 'Role B', 'tipe_disposisi' => 'paraf', 'urutan' => 3],
```

2. **Update DisposisiController:**
```php
// Tambahkan kondisi untuk unit baru
if ($suratCuti->pengaju->unit_kerja === 'Unit Baru') {
    $conditionalRoles = ['Role A', 'Role B'];
    // ... logic conditional
}
```

## 🚀 Deployment Checklist

- [ ] ✅ Database seeder dijalankan
- [ ] ✅ User untuk semua jabatan sudah dibuat
- [ ] ✅ Testing conditional logic berhasil
- [ ] ✅ UI menampilkan indikator dengan benar
- [ ] ✅ Audit trail berfungsi
- [ ] ✅ Performance testing completed

## 📞 Support & Maintenance

### Monitoring
- Log conditional approval di `storage/logs/laravel.log`
- Monitor approval time metrics
- Track user satisfaction dengan workflow

### Troubleshooting
- Jika conditional logic tidak berfungsi: cek unit_kerja user
- Jika disposisi tidak dibuat: cek AlurCuti seeder
- Jika UI tidak menampilkan indikator: clear view cache

---

**Status: ✅ IMPLEMENTED & TESTED**  
**Version: 1.0**  
**Last Updated: 14 Agustus 2025**
