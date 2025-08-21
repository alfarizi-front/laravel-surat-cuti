<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiPDFController extends Controller
{
    public function generate(User $pegawai)
    {
        // Use universal template for all employee types
        $template = 'pdf.surat-cuti-universal';

        // Get sample disposisi data for this pegawai's unit
        $unitKerja = $pegawai->unit_kerja;
        $alurCuti = \App\Models\AlurCuti::getAlurByUnitKerja($unitKerja);
        
        // Create sample disposisi list with proper tipe_disposisi
        $disposisi_list = collect();
        foreach ($alurCuti as $alur) {
            $user = \App\Models\User::where('jabatan', $alur->jabatan)
                                   ->where('unit_kerja', $unitKerja)
                                   ->first();
            
            if (!$user && in_array($alur->jabatan, ['Kasubag Umpeg', 'Sekretaris Dinas', 'KADIN'])) {
                $user = \App\Models\User::where('jabatan', $alur->jabatan)->first();
            }
            
            if ($user) {
                $disposisi_list->push((object)[
                    'jabatan' => $alur->jabatan,
                    'user' => $user,
                    'tipe_disposisi' => $alur->tipe_disposisi,
                    'status' => 'pending',
                    'tanggal' => null
                ]);
            }
        }
        
        // Get kepala dinas for signature
        $kepala_dinas = \App\Models\User::where('jabatan', 'KADIN')->first();

        // Check for actual surat cuti data
        $surat_cuti = \App\Models\SuratCuti::where('pengaju_id', $pegawai->id)
                                           ->with(['disposisiCuti.user', 'jenisCuti'])
                                           ->latest()
                                           ->first();

        // Prepare data for BLANKO CUTI template
        $atasanParaf = $disposisi_list->firstWhere('tipe_disposisi', 'paraf');
        $pejabatUser = $kepala_dinas ?: null;

        $blankoData = [
            // Data Pegawai
            'nama_pegawai' => $pegawai->nama,
            'nip_pegawai' => $pegawai->nip,
            'jabatan' => $pegawai->jabatan,
            'masa_kerja' => $this->calculateMasaKerja($pegawai),
            'unit_kerja' => $pegawai->unit_kerja,
            'golongan' => $pegawai->golongan ?? 'III/a',

            // Data Surat
            'tempat' => $pegawai->kota ?? 'Purworejo',
            'tanggal_surat' => now()->format('d F Y'),
            'kabupaten' => $pegawai->kabupaten ?? 'Purworejo',
            'nomor_surat' => '800.1.11.4/___/' . date('Y'),

            // Data Cuti
            'jenis_cuti' => $surat_cuti->jenisCuti->nama ?? 'Cuti Tahunan',
            'alasan_cuti' => $surat_cuti->alasan ?? 'Keperluan keluarga',
            'lama_cuti' => $surat_cuti->jumlah_hari ?? 1,
            'tanggal_mulai' => isset($surat_cuti) && $surat_cuti->tanggal_awal ? $surat_cuti->tanggal_awal->translatedFormat('d F Y') : now()->format('d F Y'),
            'tanggal_selesai' => isset($surat_cuti) && $surat_cuti->tanggal_akhir ? $surat_cuti->tanggal_akhir->translatedFormat('d F Y') : now()->format('d F Y'),

            // Catatan Cuti (default)
            'haki_2023' => '12', 'diambil_2023' => '0', 'keterangan_2023' => '12',
            'haki_2024' => '12', 'diambil_2024' => '0', 'keterangan_2024' => '12',
            'haki_2025' => '12', 'diambil_2025' => '0', 'keterangan_2025' => '12',

            // Alamat
            'alamat_cuti' => $surat_cuti->alamat_selama_cuti ?? ($surat_cuti->alamat_cuti ?? ''),
            'telepon' => $surat_cuti->kontak_darurat ?? ($surat_cuti->telepon ?? ''),

            // Pejabat
            'atasan_langsung' => $atasanParaf && $atasanParaf->user ? $atasanParaf->user->nama : 'Atasan Langsung',
            'nip_atasan' => $atasanParaf && $atasanParaf->user ? ($atasanParaf->user->nip ?? '') : '',
            'pejabat_berwenang' => $pejabatUser ? $pejabatUser->nama : 'Kepala Dinas',
            'nip_pejabat' => $pejabatUser ? ($pejabatUser->nip ?? '') : '',

            // Status (kosong untuk form umum)
            'pertimbangan_atasan' => '',
            'keputusan_pejabat' => '',
        ];

        // Generate PDF with BLANKO CUTI template
        $pdf = PDF::loadView('pdf.blanko-cuti-resmi', $blankoData)->setPaper('A4', 'portrait');

        // Create filename: Surat_Cuti_[nama]_[jenis_pegawai].pdf
        $filename = 'Surat_Cuti_' . str_replace(' ', '_', $pegawai->nama) . '_' . $pegawai->jenis_pegawai . '.pdf';

        return $pdf->download($filename);
    }

    public function stream(User $pegawai)
    {
        // Use universal template for all employee types
        $template = 'pdf.surat-cuti-universal';

        // Get sample disposisi data for this pegawai's unit
        $unitKerja = $pegawai->unit_kerja;
        $alurCuti = \App\Models\AlurCuti::getAlurByUnitKerja($unitKerja);
        
        // Create sample disposisi list with proper tipe_disposisi
        $disposisi_list = collect();
        foreach ($alurCuti as $alur) {
            $user = User::where('jabatan', $alur->jabatan)
                        ->where('unit_kerja', $unitKerja)
                        ->first();
            
            if (!$user && in_array($alur->jabatan, ['Kasubag Umpeg', 'Sekretaris Dinas', 'KADIN'])) {
                $user = User::where('jabatan', $alur->jabatan)->first();
            }
            
            if ($user) {
                $disposisi_list->push((object)[
                    'jabatan' => $alur->jabatan,
                    'user' => $user,
                    'tipe_disposisi' => $alur->tipe_disposisi,
                    'status' => 'pending',
                    'tanggal' => null
                ]);
            }
        }
        
        // Get kepala dinas for signature
        $kepala_dinas = User::where('jabatan', 'KADIN')->first();

        // Check for actual surat cuti data
        $surat_cuti = SuratCuti::where('pengaju_id', $pegawai->id)
                               ->with(['disposisiCuti.user', 'jenisCuti'])
                               ->latest()
                               ->first();

        // Prepare data for BLANKO CUTI template
        $atasanParaf = $disposisi_list->firstWhere('tipe_disposisi', 'paraf');
        $pejabatUser = $kepala_dinas ?: null;

        $blankoData = [
            // Data Pegawai
            'nama_pegawai' => $pegawai->nama,
            'nip_pegawai' => $pegawai->nip,
            'jabatan' => $pegawai->jabatan,
            'masa_kerja' => $this->calculateMasaKerja($pegawai),
            'unit_kerja' => $pegawai->unit_kerja,
            'golongan' => $pegawai->golongan ?? 'III/a',

            // Data Surat
            'tempat' => $pegawai->kota ?? 'Purworejo',
            'tanggal_surat' => now()->format('d F Y'),
            'kabupaten' => $pegawai->kabupaten ?? 'Purworejo',
            'nomor_surat' => '800.1.11.4/___/' . date('Y'),

            // Data Cuti
            'jenis_cuti' => $surat_cuti->jenisCuti->nama ?? 'Cuti Tahunan',
            'alasan_cuti' => $surat_cuti->alasan ?? 'Keperluan keluarga',
            'lama_cuti' => $surat_cuti->jumlah_hari ?? 1,
            'tanggal_mulai' => isset($surat_cuti) && $surat_cuti->tanggal_awal ? $surat_cuti->tanggal_awal->translatedFormat('d F Y') : now()->format('d F Y'),
            'tanggal_selesai' => isset($surat_cuti) && $surat_cuti->tanggal_akhir ? $surat_cuti->tanggal_akhir->translatedFormat('d F Y') : now()->format('d F Y'),

            // Catatan Cuti (default)
            'haki_2023' => '12', 'diambil_2023' => '0', 'keterangan_2023' => '12',
            'haki_2024' => '12', 'diambil_2024' => '0', 'keterangan_2024' => '12',
            'haki_2025' => '12', 'diambil_2025' => '0', 'keterangan_2025' => '12',

            // Alamat
            'alamat_cuti' => $surat_cuti->alamat_selama_cuti ?? ($surat_cuti->alamat_cuti ?? ''),
            'telepon' => $surat_cuti->kontak_darurat ?? ($surat_cuti->telepon ?? ''),

            // Pejabat
            'atasan_langsung' => $atasanParaf && $atasanParaf->user ? $atasanParaf->user->nama : 'Atasan Langsung',
            'nip_atasan' => $atasanParaf && $atasanParaf->user ? ($atasanParaf->user->nip ?? '') : '',
            'pejabat_berwenang' => $pejabatUser ? $pejabatUser->nama : 'Kepala Dinas',
            'nip_pejabat' => $pejabatUser ? ($pejabatUser->nip ?? '') : '',

            // Status (kosong untuk form umum)
            'pertimbangan_atasan' => '',
            'keputusan_pejabat' => '',
        ];

        // Generate PDF with BLANKO CUTI template
        $pdf = PDF::loadView('pdf.blanko-cuti-resmi', $blankoData)->setPaper('A4', 'portrait');

        // Create filename: Surat_Cuti_[nama]_[jenis_pegawai].pdf
        $filename = 'Surat_Cuti_' . str_replace(' ', '_', $pegawai->nama) . '_' . $pegawai->jenis_pegawai . '.pdf';

        return $pdf->stream($filename);
    }

    public function index()
    {
        // Get all employees with their data
        $pegawai = User::whereIn('jenis_pegawai', ['ASN', 'PPPK'])
                      ->orderBy('nama')
                      ->get();

        return view('pegawai.pdf-index', compact('pegawai'));
    }

    // Show form for Surat Cuti Resmi
    public function suratCutiForm(User $pegawai)
    {
        return view('pegawai.surat-cuti-form', compact('pegawai'));
    }

    // Generate Surat Cuti Resmi PDF
    public function suratCutiResmi(User $pegawai, Request $request)
    {
        // Get additional data from request or use defaults
        $data = [
            'pegawai' => $pegawai,
            'nomor_surat' => $request->input('nomor_surat', '800.1.11.4/___/' . date('Y')),
            'tempat' => $request->input('tempat', 'Banjarmasin'),
            'tanggal_surat' => $request->input('tanggal_surat', now()->format('d F Y')),
            'masa_kerja' => $request->input('masa_kerja', $this->calculateMasaKerja($pegawai)),
            'golongan' => $request->input('golongan', 'III/a'),
            'jenis_cuti' => $request->input('jenis_cuti', 'Cuti Tahunan'),
            'alasan_cuti' => $request->input('alasan_cuti', 'Keperluan keluarga'),
            'lama_cuti' => $request->input('lama_cuti', '2'),
            'tanggal_mulai' => $request->input('tanggal_mulai', now()->addDays(7)->format('d F Y')),
            'tanggal_selesai' => $request->input('tanggal_selesai', now()->addDays(8)->format('d F Y')),
            'alamat_cuti' => $request->input('alamat_cuti', 'Jl. Contoh No. 123, Banjarmasin'),
            'telepon' => $request->input('telepon', '0511-123456'),
            'atasan_langsung' => $request->input('atasan_langsung', 'Dr. Nama Atasan, M.Kes'),
            'nip_atasan' => $request->input('nip_atasan', '196501011990031001'),
            'pejabat_berwenang' => $request->input('pejabat_berwenang', 'Dr. Kepala Dinas, M.Kes'),
            'nip_pejabat' => $request->input('nip_pejabat', '196001011985031001'),
            'pertimbangan_atasan' => $request->input('pertimbangan_atasan', 'disetujui'),
            'keputusan_pejabat' => $request->input('keputusan_pejabat', 'disetujui'),
            'sisa_cuti_n_2' => $request->input('sisa_cuti_n_2', 12),
            'cuti_diambil_n_2' => $request->input('cuti_diambil_n_2', 0),
            'sisa_cuti_n_1' => $request->input('sisa_cuti_n_1', 12),
            'cuti_diambil_n_1' => $request->input('cuti_diambil_n_1', 2),
            'sisa_cuti_n' => $request->input('sisa_cuti_n', 12),
            'cuti_diambil_n' => $request->input('cuti_diambil_n', 0),
        ];

        // Add disposisi data to surat-cuti-resmi as well
        $unitKerja = $pegawai->unit_kerja;
        $alurCuti = \App\Models\AlurCuti::getAlurByUnitKerja($unitKerja);
        
        $disposisi_list = collect();
        foreach ($alurCuti as $alur) {
            $user = User::where('jabatan', $alur->jabatan)
                        ->where('unit_kerja', $unitKerja)
                        ->first();
            
            if (!$user && in_array($alur->jabatan, ['Kasubag Umpeg', 'Sekretaris Dinas', 'KADIN'])) {
                $user = User::where('jabatan', $alur->jabatan)->first();
            }
            
            if ($user) {
                $disposisi_list->push((object)[
                    'jabatan' => $alur->jabatan,
                    'user' => $user,
                    'tipe_disposisi' => $alur->tipe_disposisi,
                    'status' => 'pending',
                    'tanggal' => null
                ]);
            }
        }
        
        $data['disposisi_list'] = $disposisi_list;
        $data['kepala_dinas'] = User::where('jabatan', 'KADIN')->first();

        $pdf = PDF::loadView('pdf.surat-cuti-resmi', $data)->setPaper('A4', 'portrait');

        $filename = 'Surat_Cuti_Resmi_' . str_replace(' ', '_', $pegawai->nama) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    // Generate Puskesmas Employee Certificate PDF
    public function puskesmasCertificate(User $pegawai)
    {
        // 1) statusPegawai
        $statusPegawai = in_array($pegawai->jenis_pegawai, ['ASN', 'PPPK']) ? $pegawai->jenis_pegawai : 'ASN';

        // 2) namaPuskesmas diambil dari unit_kerja pegawai
        $namaPuskesmas = $pegawai->unit_kerja ?: 'Puskesmas';

        // 3) kepalaPuskesmas: cari user Kapus pada puskesmas yang sama
        $kepalaPuskesmas = User::where(function($q) {
                                $q->where('role', 'kapus')
                                  ->orWhere('jabatan', 'like', '%Kepala Puskesmas%');
                            })
                            ->where('unit_kerja', $namaPuskesmas)
                            ->orderBy('id')
                            ->first();

        // 4) logo Puskesmas: opsional, ambil dari public/logo-puskesmas.png jika ada
        $logoBase64 = null;
        $logoPath = public_path('images/logo-puskesmas.png');
        if (file_exists($logoPath)) {
            try {
                $logoBase64 = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Kapus signature/cap menjadi base64 jika file ada
        $kapusSignatureBase64 = null;
        $kapusCapBase64 = null;
        if ($kepalaPuskesmas) {
            if ($kepalaPuskesmas->tanda_tangan) {
                $sigPath = public_path('storage/' . $kepalaPuskesmas->tanda_tangan);
                if (file_exists($sigPath)) {
                    try {
                        $kapusSignatureBase64 = 'data:' . mime_content_type($sigPath) . ';base64,' . base64_encode(file_get_contents($sigPath));
                    } catch (\Exception $e) {}
                }
            }
            if ($kepalaPuskesmas->cap_stempel && $kepalaPuskesmas->gunakan_cap) {
                $capPath = public_path('storage/' . $kepalaPuskesmas->cap_stempel);
                if (file_exists($capPath)) {
                    try {
                        $kapusCapBase64 = 'data:' . mime_content_type($capPath) . ';base64,' . base64_encode(file_get_contents($capPath));
                    } catch (\Exception $e) {}
                }
            }
        }

        $viewData = [
            'pegawai' => $pegawai,
            'statusPegawai' => $statusPegawai,
            'namaPuskesmas' => $namaPuskesmas,
            'kepalaPuskesmas' => $kepalaPuskesmas,
            'logoBase64' => $logoBase64,
            'kapusSignatureBase64' => $kapusSignatureBase64,
            'kapusCapBase64' => $kapusCapBase64,
            'tanggalCetak' => now(),
        ];

        $pdf = PDF::loadView('pegawai.puskesmas-certificate', $viewData)->setPaper('A4', 'portrait');

        $filename = 'Surat_Keterangan_Puskesmas_' . str_replace(' ', '_', $pegawai->nama) . '.pdf';

        return $pdf->download($filename);
    }

    // Generate Blanko Cuti Resmi PDF with specific data
    public function blankoCutiResmi()
    {
        $data = [
            // Data Pegawai
            'nama_pegawai' => 'Umi Setyawati, AMKg',
            'nip_pegawai' => '19870223 200902 2 004',
            'jabatan' => 'Pengelola Kepegawaian',
            'masa_kerja' => '14 Tahun 06 Bulan',
            'unit_kerja' => 'Sub Bag Umum dan Kepegawaian Dinas Kesehatan Daerah Kabupaten Purworejo',
            'golongan' => 'III/c',
            
            // Data Surat
            'tempat' => 'Purworejo',
            'tanggal_surat' => '5 Agustus 2025',
            'kabupaten' => 'Purworejo',
            'nomor_surat' => '800.1.11.4/___/2025',
            
            // Data Cuti
            'jenis_cuti' => 'Cuti Tahunan',
            'alasan_cuti' => 'Kepentingan keluarga',
            'lama_cuti' => '1',
            'tanggal_mulai' => '6 Agustus 2025',
            'tanggal_selesai' => '6 Agustus 2025',
            
            // Catatan Cuti
            'haki_2023' => '12',
            'diambil_2023' => '12',
            'keterangan_2023' => '0',
            'haki_2024' => '12',
            'diambil_2024' => '9',
            'keterangan_2024' => '3',
            'haki_2025' => '12',
            'diambil_2025' => '0',
            'keterangan_2025' => '12',
            
            // Alamat
            'alamat_cuti' => 'Kledung Karangdalem, RT 3, RW 1, Kec Banyuurip, Kab Purworejo',
            'telepon' => '085292678023',
            
            // Pejabat
            'atasan_langsung' => 'Taufik Anggoro, S.IP',
            'nip_atasan' => '19710404 199403 1 003',
            'pejabat_berwenang' => 'dr. Sudarmi, MM',
            'nip_pejabat' => '19690220 200212 2 004',
            
            // Status (optional)
            'pertimbangan_atasan' => '', // kosong untuk form kosong
            'keputusan_pejabat' => '', // kosong untuk form kosong
        ];

        $pdf = PDF::loadView('pdf.blanko-cuti-resmi', $data)
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Times-Roman'
                  ]);

        $filename = 'Blanko_Cuti_Resmi_' . str_replace([' ', ','], ['_', ''], $data['nama_pegawai']) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Calculate masa kerja from tanggal_masuk if available
     */
    private function calculateMasaKerja(User $pegawai): string
    {
        if (isset($pegawai->tanggal_masuk)) {
            $tanggalMasuk = \Carbon\Carbon::parse($pegawai->tanggal_masuk);
            $tahun = $tanggalMasuk->diffInYears(\Carbon\Carbon::now());
            $bulan = $tanggalMasuk->diffInMonths(\Carbon\Carbon::now()) % 12;
            return "{$tahun} Tahun {$bulan} Bulan";
        }
        
        return "5 Tahun 0 Bulan"; // Default jika tanggal_masuk tidak ada
    }
}
