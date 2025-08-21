<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\JenisCuti;
use App\Models\SuratCuti;
use App\Models\DisposisiCuti;
use App\Models\AlurCuti;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_surat_cuti()
    {
        $user = User::factory()->create([
            'nama' => 'Test User',
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::create([
            'nama' => 'Cuti Tahunan',
            'berlaku_untuk' => 'ASN'
        ]);

        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        $this->assertTrue($user->suratCuti->contains($suratCuti));
        $this->assertEquals(1, $user->suratCuti->count());
    }

    public function test_surat_cuti_belongs_to_user()
    {
        $user = User::factory()->create([
            'nama' => 'Test User',
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::create([
            'nama' => 'Cuti Tahunan',
            'berlaku_untuk' => 'ASN'
        ]);

        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        $this->assertEquals($user->id, $suratCuti->pengaju->id);
        $this->assertEquals($user->nama, $suratCuti->pengaju->nama);
    }

    public function test_surat_cuti_belongs_to_jenis_cuti()
    {
        $user = User::factory()->create([
            'nama' => 'Test User',
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::create([
            'nama' => 'Cuti Tahunan',
            'berlaku_untuk' => 'ASN'
        ]);

        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        $this->assertEquals($jenisCuti->id, $suratCuti->jenisCuti->id);
        $this->assertEquals($jenisCuti->nama, $suratCuti->jenisCuti->nama);
    }

    public function test_surat_cuti_has_many_disposisi_cuti()
    {
        $user = User::factory()->create([
            'nama' => 'Test User',
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $disposisiUser = User::factory()->create([
            'nama' => 'Kepala',
            'jabatan' => 'Kepala Tata Usaha',
            'unit_kerja' => 'Puskesmas',
            'role' => 'kepala',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::create([
            'nama' => 'Cuti Tahunan',
            'berlaku_untuk' => 'ASN'
        ]);

        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'proses'
        ]);

        $disposisi = DisposisiCuti::create([
            'surat_cuti_id' => $suratCuti->id,
            'user_id' => $disposisiUser->id,
            'jabatan' => $disposisiUser->jabatan,
            'status' => 'pending'
        ]);

        $this->assertTrue($suratCuti->disposisiCuti->contains($disposisi));
        $this->assertEquals(1, $suratCuti->disposisiCuti->count());
    }

    public function test_surat_cuti_jumlah_hari_calculation()
    {
        $user = User::factory()->create([
            'nama' => 'Test User',
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::create([
            'nama' => 'Cuti Tahunan',
            'berlaku_untuk' => 'ASN'
        ]);

        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9), // 3 days (7, 8, 9)
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        $this->assertEquals(3, $suratCuti->jumlah_hari);
    }

    public function test_alur_cuti_get_alur_by_unit_kerja()
    {
        AlurCuti::create([
            'unit_kerja' => 'Puskesmas',
            'step_ke' => 1,
            'jabatan' => 'Kepala Tata Usaha',
            'tipe_disposisi' => 'paraf',
            'urutan' => 1
        ]);

        AlurCuti::create([
            'unit_kerja' => 'Puskesmas',
            'step_ke' => 2,
            'jabatan' => 'Kepala Puskesmas',
            'tipe_disposisi' => 'ttd',
            'urutan' => 2
        ]);

        AlurCuti::create([
            'unit_kerja' => 'Sekretariat',
            'step_ke' => 1,
            'jabatan' => 'Kasubag',
            'tipe_disposisi' => 'paraf',
            'urutan' => 1
        ]);

        $alurPuskesmas = AlurCuti::getAlurByUnitKerja('Puskesmas');
        $alurSekretariat = AlurCuti::getAlurByUnitKerja('Sekretariat');

        $this->assertEquals(2, $alurPuskesmas->count());
        $this->assertEquals(1, $alurSekretariat->count());
        
        // Test ordering
        $this->assertEquals('Kepala Tata Usaha', $alurPuskesmas->first()->jabatan);
        $this->assertEquals('Kepala Puskesmas', $alurPuskesmas->last()->jabatan);
    }
}
