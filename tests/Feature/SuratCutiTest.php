<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JenisCuti;
use App\Models\SuratCuti;
use App\Models\AlurCuti;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuratCutiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create jenis cuti
        JenisCuti::create(['nama' => 'Cuti Tahunan', 'berlaku_untuk' => 'Keduanya']);
        JenisCuti::create(['nama' => 'Cuti Besar', 'berlaku_untuk' => 'ASN']);

        // Create alur cuti for Puskesmas
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

        // Create users
        User::create([
            'nama' => 'Karyawan ASN',
            'email' => 'asn@test.com',
            'password' => bcrypt('password'),
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        User::create([
            'nama' => 'Kepala Tata Usaha',
            'email' => 'katu@test.com',
            'password' => bcrypt('password'),
            'jabatan' => 'Kepala Tata Usaha',
            'unit_kerja' => 'Puskesmas',
            'role' => 'kepala',
            'jenis_pegawai' => 'ASN'
        ]);
    }

    public function test_karyawan_can_create_surat_cuti()
    {
        $user = User::where('role', 'karyawan')->first();
        $jenisCuti = JenisCuti::first();

        $response = $this->actingAs($user)->post('/surat-cuti', [
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7)->format('Y-m-d'),
            'tanggal_akhir' => now()->addDays(9)->format('Y-m-d'),
            'alasan' => 'Keperluan keluarga',
            'alamat_selama_cuti' => 'Jl. Test No. 123',
            'kontak_darurat' => '081234567890'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('surat_cuti', [
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'status' => 'draft'
        ]);
    }

    public function test_pppk_can_only_select_allowed_cuti_types()
    {
        $user = User::create([
            'nama' => 'Karyawan PPPK',
            'email' => 'pppk@test.com',
            'password' => bcrypt('password'),
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'PPPK'
        ]);

        $response = $this->actingAs($user)->get('/surat-cuti/create');
        
        $response->assertStatus(200);
        // PPPK should only see "Cuti Tahunan" (berlaku_untuk = 'Keduanya')
        $response->assertSee('Cuti Tahunan');
        $response->assertDontSee('Cuti Besar'); // ASN only
    }

    public function test_surat_cuti_submission_creates_disposisi()
    {
        $user = User::where('role', 'karyawan')->first();
        $jenisCuti = JenisCuti::first();

        // Create surat cuti
        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        // Submit surat cuti
        $response = $this->actingAs($user)->patch("/surat-cuti/{$suratCuti->id}/submit");

        $response->assertRedirect();
        $this->assertDatabaseHas('surat_cuti', [
            'id' => $suratCuti->id,
            'status' => 'proses'
        ]);

        // Check if disposisi is created
        $this->assertDatabaseHas('disposisi_cuti', [
            'surat_cuti_id' => $suratCuti->id,
            'status' => 'pending'
        ]);
    }

    public function test_user_cannot_access_other_users_surat_cuti()
    {
        $user1 = User::where('role', 'karyawan')->first();
        $user2 = User::create([
            'nama' => 'User 2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
            'jabatan' => 'Staff',
            'unit_kerja' => 'Puskesmas',
            'role' => 'karyawan',
            'jenis_pegawai' => 'ASN'
        ]);

        $jenisCuti = JenisCuti::first();

        // Create surat cuti by user1
        $suratCuti = SuratCuti::create([
            'pengaju_id' => $user1->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'draft'
        ]);

        // User2 tries to access user1's surat cuti
        $response = $this->actingAs($user2)->get("/surat-cuti/{$suratCuti->id}");

        $response->assertStatus(403);
    }

    public function test_disposisi_user_can_process_their_disposisi()
    {
        $karyawan = User::where('role', 'karyawan')->first();
        $kepala = User::where('jabatan', 'Kepala Tata Usaha')->first();
        $jenisCuti = JenisCuti::first();

        // Create and submit surat cuti
        $suratCuti = SuratCuti::create([
            'pengaju_id' => $karyawan->id,
            'jenis_cuti_id' => $jenisCuti->id,
            'tanggal_awal' => now()->addDays(7),
            'tanggal_akhir' => now()->addDays(9),
            'alasan' => 'Test',
            'status' => 'proses',
            'tanggal_ajuan' => now()
        ]);

        // Create disposisi
        $disposisi = $suratCuti->disposisiCuti()->create([
            'user_id' => $kepala->id,
            'jabatan' => $kepala->jabatan,
            'status' => 'pending'
        ]);

        // Process disposisi
        $response = $this->actingAs($kepala)->patch("/disposisi/{$disposisi->id}/process", [
            'action' => 'approve',
            'catatan' => 'Disetujui'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('disposisi_cuti', [
            'id' => $disposisi->id,
            'status' => 'sudah'
        ]);
    }
}
