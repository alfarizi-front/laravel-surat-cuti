<?php

namespace App\Http\Controllers;

use App\Models\AlurCuti;
use App\Models\DisposisiCuti;
use App\Models\JenisCuti;
use App\Models\Puskesmas;
use App\Models\Signature;
use App\Models\SisaCuti;
use App\Models\SuratCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// DomPDF akan di-import secara langsung di method

class SuratCutiController extends Controller
{
    // Constructor middleware sudah dihandle di routes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validasi parameter sorting
        $validColumns = ['id', 'pengaju', 'jenis_cuti', 'tanggal_awal', 'status', 'tanggal_ajuan', 'created_at'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'created_at';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query = SuratCuti::with(['pengaju', 'jenisCuti']);

        // Tambahkan kondisi berdasarkan role pengguna
        if ($user->role === 'admin') {
            // Admin bisa melihat semua surat cuti
        } elseif ($user->role === 'karyawan') {
            // Karyawan hanya bisa melihat surat cuti mereka sendiri
            $query->where('pengaju_id', $user->id);
        } else {
            // Untuk role disposisi, tampilkan surat yang perlu ditindaklanjuti
            $query->whereHas('disposisiCuti', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Terapkan filtering
        if ($request->has('search') && $request->get('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('pengaju', function ($q) use ($searchTerm) {
                    $q->where('nama', 'like', '%' . $searchTerm . '%');
                })->orWhereHas('jenisCuti', function ($q) use ($searchTerm) {
                    $q->where('nama', 'like', '%' . $searchTerm . '%');
                })->orWhere('status', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('start_date') && $request->get('start_date')) {
            $query->whereDate('tanggal_awal', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date') && $request->get('end_date')) {
            $query->whereDate('tanggal_awal', '<=', $request->get('end_date'));
        }

        // Terapkan sorting
        switch ($sortColumn) {
            case 'pengaju':
                $query->join('users as pengaju_sort', 'surat_cuti.pengaju_id', '=', 'pengaju_sort.id')
                      ->orderBy('pengaju_sort.nama', $sortDirection);
                break;
            case 'jenis_cuti':
                $query->join('jenis_cuti as jenis_sort', 'surat_cuti.jenis_cuti_id', '=', 'jenis_sort.id')
                      ->orderBy('jenis_sort.nama', $sortDirection);
                break;
            case 'tanggal_awal':
                $query->orderBy('surat_cuti.tanggal_awal', $sortDirection);
                break;
            case 'status':
                $query->orderBy('surat_cuti.status', $sortDirection);
                break;
            case 'tanggal_ajuan':
                $query->orderBy('surat_cuti.tanggal_ajuan', $sortDirection);
                break;
            case 'id':
                $query->orderBy('surat_cuti.id', $sortDirection);
                break;
            default:
                $query->orderBy('surat_cuti.created_at', $sortDirection);
                break;
        }

        $suratCuti = $query->paginate(10)->appends(request()->except('page'));

        return view('surat-cuti.index', compact('suratCuti', 'sortColumn', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Get jenis cuti based on user type
        if ($user->jenis_pegawai === 'PPPK') {
            $jenisCuti = JenisCuti::whereIn('berlaku_untuk', ['PPPK', 'Keduanya'])->get();
        } else {
            $jenisCuti = JenisCuti::whereIn('berlaku_untuk', ['ASN', 'Keduanya'])->get();
        }

        // Get puskesmas list with kepala information
        $puskesmasList = \App\Models\Puskesmas::orderBy('nama_puskesmas')
            ->select(['id', 'nama_puskesmas', 'kepala', 'nip_kepala'])
            ->get();

        // Get informasi cuti tahunan user (sistem lama untuk kompatibilitas)
        $cutiTahunan = $user->getCutiTahunan();

        // Get informasi sisa cuti dari sistem baru
        $tahunSekarang = date('Y');
        $sisaCutiData = SisaCuti::getSisaCutiMultiYear($user->id, [2023, 2024, 2025]);
        $sisaCutiSekarang = SisaCuti::getSisaCuti($user->id, $tahunSekarang);

        // Override sisa_cuti dengan data dari sistem baru jika tersedia
        if ($sisaCutiSekarang) {
            $cutiTahunan->sisa_cuti = $sisaCutiSekarang->sisa_akhir;
            $cutiTahunan->cuti_digunakan = $sisaCutiSekarang->cuti_diambil;
        }
        $puskesmasList = Puskesmas::orderBy('nama_puskesmas')->get();

        return view('surat-cuti.create', compact('jenisCuti', 'cutiTahunan', 'sisaCutiData', 'puskesmasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation rules based on user type
        $rules = [
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'alasan' => 'required|string|max:1000',
            'golongan' => 'nullable|string|max:50',
            'masa_kerja' => 'nullable|string|max:50',
            'puskesmas_id' => 'required|exists:puskesmas,id',
        ];

        if ($user->jenis_pegawai === 'ASN') {
            $rules['alamat_selama_cuti'] = 'nullable|string|max:500';
            $rules['kontak_darurat'] = 'nullable|string|max:20';
            $rules['lampiran'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['golongan_ruang'] = 'nullable|string|max:50';
            $rules['masa_jabatan'] = 'nullable|string|max:100';
        }

        $validatedData = $request->validate($rules);

        if ($user->jenis_pegawai === 'ASN') {
            $validatedData['golongan_ruang'] = $request->input('golongan_ruang');
            $validatedData['masa_jabatan'] = $request->input('masa_jabatan');
        }

        // Update informasi golongan dan masa kerja pengguna
        $golongan = $request->input('golongan', $user->golongan);
        $masaKerja = $request->input('masa_kerja', $user->masa_kerja);

        if ($request->filled('golongan') || $request->filled('masa_kerja')) {
            $user->update([
                'golongan' => $golongan,
                'masa_kerja' => $masaKerja,
            ]);
        }

        $validatedData['golongan_ruang'] = $golongan;
        $validatedData['masa_jabatan'] = $masaKerja;

        unset($validatedData['golongan'], $validatedData['masa_kerja']);

        // Hitung jumlah hari cuti
        $tanggalAwal = \Carbon\Carbon::parse($request->tanggal_awal);
        $tanggalAkhir = \Carbon\Carbon::parse($request->tanggal_akhir);
        $jumlahHari = $tanggalAwal->diffInDays($tanggalAkhir) + 1;

        // Get informasi cuti tahunan user (sistem lama untuk kompatibilitas)
        $cutiTahunan = $user->getCutiTahunan();

        // Get informasi sisa cuti dari sistem baru
        $tahunSekarang = date('Y');
        $sisaCutiSekarang = SisaCuti::getSisaCuti($user->id, $tahunSekarang);

        // Override sisa_cuti dengan data dari sistem baru jika tersedia
        if ($sisaCutiSekarang) {
            $cutiTahunan->sisa_cuti = $sisaCutiSekarang->sisa_akhir;
            $cutiTahunan->cuti_digunakan = $sisaCutiSekarang->cuti_diambil;
        }

        // Handle file upload
        if ($request->hasFile('lampiran')) {
            $validatedData['lampiran'] = $request->file('lampiran')->store('lampiran-cuti', 'public');
        }

        // Create surat cuti
        $validatedData['pengaju_id'] = $user->id;
        $validatedData['status'] = 'draft';
        $validatedData['puskesmas_id'] = $request->input('puskesmas_id');

        $suratCuti = SuratCuti::create($validatedData);

        // Simpan informasi limit untuk ditampilkan di show
        $isExceeding = $cutiTahunan->isExceedingLimit($jumlahHari);
        $totalCutiIfApproved = $cutiTahunan->getTotalCutiIfApproved($jumlahHari);

        session()->flash('cuti_info', [
            'jumlah_hari' => $jumlahHari,
            'sisa_cuti' => $cutiTahunan->sisa_cuti,
            'is_exceeding' => $isExceeding,
            'total_if_approved' => $totalCutiIfApproved,
            'jatah_cuti' => $cutiTahunan->jatah_cuti,
        ]);

        return redirect()->route('surat-cuti.show', $suratCuti)
            ->with('success', 'Surat cuti berhasil dibuat. Silakan review dan submit untuk memulai proses disposisi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratCuti $suratCuti)
    {
        // Check access permission
        $user = Auth::user();

        if ($user->role !== 'admin' && $suratCuti->pengaju_id !== $user->id) {
            // Check if user is in disposisi chain
            $hasAccess = $suratCuti->disposisiCuti()
                ->where('user_id', $user->id)
                ->exists();
            if (! $hasAccess) {
                abort(403, 'Anda tidak memiliki akses ke surat cuti ini');
            }
        }

        $suratCuti->load(['pengaju', 'jenisCuti', 'disposisiCuti.user']);

        return view('surat-cuti.show', compact('suratCuti'));
    }

    /**
     * Submit surat cuti for approval process
     */
    public function submit(SuratCuti $suratCuti)
    {
        // Only pengaju can submit their own surat
        if ($suratCuti->pengaju_id !== Auth::id()) {
            abort(403);
        }

        if ($suratCuti->status !== 'draft') {
            return back()->with('error', 'Surat cuti sudah disubmit sebelumnya.');
        }

        $user = $suratCuti->pengaju;
        $jumlahHari = $suratCuti->jumlah_hari;

        // Get informasi cuti tahunan user
        $cutiTahunan = $user->getCutiTahunan();

        // Cek apakah melebihi batas maksimal
        $isExceeding = $cutiTahunan->isExceedingLimit($jumlahHari);
        $totalCutiIfApproved = $cutiTahunan->getTotalCutiIfApproved($jumlahHari);

        // Update status and tanggal ajuan
        $suratCuti->update([
            'status' => 'proses',
            'tanggal_ajuan' => now(),
        ]);

        // Update cuti pending
        $cutiTahunan->addPendingCuti($jumlahHari);

        // Create disposisi based on alur
        $this->createDisposisiAlur($suratCuti);

        // Pesan berdasarkan status limit
        if ($isExceeding) {
            $message = 'Surat cuti berhasil disubmit untuk proses disposisi. '.
                      'PERHATIAN: Pengajuan ini melebihi batas maksimal cuti tahunan (12 hari). '.
                      "Total cuti jika disetujui: {$totalCutiIfApproved} hari. ".
                      'Pengajuan akan memerlukan persetujuan khusus dari admin.';

            return back()->with('warning', $message);
        } else {
            $sisaCuti = $cutiTahunan->sisa_cuti;
            $message = 'Surat cuti berhasil disubmit untuk proses disposisi. '.
                      "Sisa cuti Anda setelah pengajuan ini: {$sisaCuti} hari.";

            return back()->with('success', $message);
        }
    }

    /**
     * Create disposisi alur for surat cuti
     */
    private function createDisposisiAlur(SuratCuti $suratCuti)
    {
        $unitKerja = $suratCuti->pengaju->unit_kerja;
        $puskesmas = $suratCuti->puskesmas;
        
        // Cek apakah puskesmas menggunakan workflow khusus
        if ($puskesmas && $puskesmas->gunakan_workflow_khusus) {
            // Buat disposisi khusus untuk puskesmas
            $this->createDisposisiKhususPuskesmas($suratCuti, $puskesmas);
            return;
        }
        
        // Gunakan workflow default
        $alurCuti = AlurCuti::getAlurByUnitKerja($unitKerja);

        // If no workflow found for specific unit, try fallback patterns
        if ($alurCuti->isEmpty()) {
            $alurCuti = $this->getFallbackAlurCuti($unitKerja);
        }

        // Log if still no workflow found
        if ($alurCuti->isEmpty()) {
            Log::warning("No workflow found for unit kerja: {$unitKerja}. Surat cuti ID: {$suratCuti->id}");

            return;
        }

        foreach ($alurCuti as $alur) {
            // Determine the target unit kerja for user search
            // If using fallback workflow, use the workflow's unit_kerja instead of original
            $targetUnitKerja = $alur->unit_kerja; // Use workflow's unit_kerja

            // Find user with matching jabatan in target unit kerja
            $user = User::where('jabatan', $alur->jabatan)
                ->where('unit_kerja', $targetUnitKerja)
                ->first();

            // If not found in target unit, look for cross-unit roles
            if (! $user && in_array($alur->jabatan, ['Kasubag Umpeg', 'Sekretaris Dinas', 'KADIN'])) {
                $user = User::where('jabatan', $alur->jabatan)->first();
            }

            if ($user) {
                // Check if disposisi already exists to prevent duplicates
                $existingDisposisi = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                    ->where('user_id', $user->id)
                    ->where('jabatan', $alur->jabatan)
                    ->first();

                if (! $existingDisposisi) {
                    DisposisiCuti::create([
                        'surat_cuti_id' => $suratCuti->id,
                        'user_id' => $user->id,
                        'jabatan' => $alur->jabatan,
                        'tipe_disposisi' => $alur->tipe_disposisi, // Include tipe_disposisi from workflow
                        'status' => 'pending',
                    ]);
                }
            }
        }
    }
    
    /**
     * Create disposisi khusus untuk puskesmas (mengikuti alur 5 tahap yang benar)
     */
    private function createDisposisiKhususPuskesmas(SuratCuti $suratCuti, \App\Models\Puskesmas $puskesmas)
    {
        // Dapatkan alur cuti untuk unit kerja puskesmas
        $alurCuti = AlurCuti::where('unit_kerja', 'like', '%Puskesmas%')
                           ->orderBy('urutan')
                           ->get();
        
        // Jika tidak ada alur khusus, gunakan fallback
        if ($alurCuti->isEmpty()) {
            $alurCuti = AlurCuti::where('unit_kerja', 'Puskesmas')
                               ->orderBy('urutan')
                               ->get();
        }
        
        foreach ($alurCuti as $alur) {
            $user = null;
            
            // Untuk Kepala Puskesmas, gunakan kepala puskesmas yang dipilih
            if ($alur->jabatan === 'Kepala Puskesmas') {
                $user = $puskesmas->kepalaPuskesmas;
            } 
            // Untuk Kepala Tata Usaha, gunakan kepala puskesmas juga (karena puskesmas)
            elseif ($alur->jabatan === 'Kepala Tata Usaha') {
                $user = $puskesmas->kepalaPuskesmas;
            }
            // Untuk jabatan lain, cari user dengan jabatan yang sesuai
            else {
                // Cari user dengan jabatan yang sesuai di unit kerja yang relevan
                if (in_array($alur->jabatan, ['Kasubag Umpeg', 'Sekretaris Dinas', 'KADIN'])) {
                    $user = User::where('jabatan', $alur->jabatan)->first();
                } else {
                    $user = User::where('jabatan', $alur->jabatan)
                               ->where('unit_kerja', $alur->unit_kerja)
                               ->first();
                }
            }
            
            // Buat disposisi jika user ditemukan
            if ($user) {
                // Cek apakah disposisi sudah ada untuk mencegah duplikasi
                $existingDisposisi = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                    ->where('user_id', $user->id)
                    ->where('jabatan', $alur->jabatan)
                    ->first();
                
                // Hanya buat disposisi jika belum ada
                if (! $existingDisposisi) {
                    DisposisiCuti::create([
                        'surat_cuti_id' => $suratCuti->id,
                        'user_id' => $user->id,
                        'jabatan' => $alur->jabatan,
                        'tipe_disposisi' => $alur->tipe_disposisi,
                        'status' => 'pending',
                    ]);
                }
            }
        }
    }

    /**
     * Get fallback workflow for unit kerja variations
     */
    private function getFallbackAlurCuti(string $unitKerja)
    {
        // Define fallback patterns for unit kerja variations
        $fallbackPatterns = [
            // Puskesmas variations
            'Puskesmas Utara' => 'Puskesmas',
            'Puskesmas Selatan' => 'Puskesmas',
            'Puskesmas Timur' => 'Puskesmas',
            'Puskesmas Barat' => 'Puskesmas',
            'Puskesmas Pusat' => 'Puskesmas',
            // Bidang variations - semua ke "Bidang" saja
            'Bidang Gudang' => 'Bidang',
            'Bidang Kesehatan Masyarakat' => 'Bidang',
            'Bidang Pelayanan Kesehatan' => 'Bidang',
            'Bidang P2P' => 'Bidang',
            'Gudang' => 'Bidang',
            // Add more patterns as needed
        ];

        // Check if there's a fallback pattern
        if (isset($fallbackPatterns[$unitKerja])) {
            $fallbackUnit = $fallbackPatterns[$unitKerja];
            Log::info("Using fallback workflow for '{$unitKerja}' -> '{$fallbackUnit}'");

            return AlurCuti::getAlurByUnitKerja($fallbackUnit);
        }

        // Try generic patterns
        if (str_contains($unitKerja, 'Puskesmas')) {
            Log::info("Using generic Puskesmas workflow for '{$unitKerja}'");

            return AlurCuti::getAlurByUnitKerja('Puskesmas');
        }

        // All Bidang variations go to "Bidang"
        if (str_contains($unitKerja, 'Bidang') || str_contains($unitKerja, 'Gudang')) {
            Log::info("Using generic Bidang workflow for '{$unitKerja}'");

            return AlurCuti::getAlurByUnitKerja('Bidang');
        }

        return collect(); // Return empty collection if no fallback found
    }

    /**
     * Check if PDF can be generated for this surat cuti
     */
    private function canGeneratePDF(SuratCuti $suratCuti): bool
    {
        // Allow if already approved
        if ($suratCuti->status === 'disetujui') {
            return true;
        }

        // Allow if has sufficient approvals (flexible logic)
        $disposisiList = $suratCuti->disposisiCuti;

        // Check if all required signatures (TTD) are completed
        $requiredSignatures = $disposisiList->where('tipe_disposisi', 'ttd');
        $approvedSignatures = $requiredSignatures->where('status', 'sudah');

        // Must have all signatures completed
        if ($requiredSignatures->count() !== $approvedSignatures->count()) {
            return false;
        }

        // Check paraf completion rate
        $requiredParaf = $disposisiList->where('tipe_disposisi', 'paraf');
        if ($requiredParaf->count() === 0) {
            return true; // No paraf required
        }

        $approvedParaf = $requiredParaf->where('status', 'sudah');
        $parafCompletionRate = $approvedParaf->count() / $requiredParaf->count();

        // Allow PDF if at least 80% of paraf are completed and all TTD are done
        return $parafCompletionRate >= 0.8;
    }

    /**
     * Get approval status summary
     */
    private function getApprovalStatus(SuratCuti $suratCuti): array
    {
        $disposisiList = $suratCuti->disposisiCuti;

        $signatures = $disposisiList->where('tipe_disposisi', 'ttd');
        $parafs = $disposisiList->where('tipe_disposisi', 'paraf');

        return [
            'signatures' => [
                'total' => $signatures->count(),
                'approved' => $signatures->where('status', 'sudah')->count(),
                'pending' => $signatures->where('status', 'pending')->count(),
            ],
            'parafs' => [
                'total' => $parafs->count(),
                'approved' => $parafs->where('status', 'sudah')->count(),
                'pending' => $parafs->where('status', 'pending')->count(),
            ],
        ];
    }

    /**
     * Get completion rate percentage
     */
    private function getCompletionRate(SuratCuti $suratCuti): array
    {
        $disposisiList = $suratCuti->disposisiCuti;
        $total = $disposisiList->count();
        $approved = $disposisiList->where('status', 'sudah')->count();

        $signatures = $disposisiList->where('tipe_disposisi', 'ttd');
        $parafs = $disposisiList->where('tipe_disposisi', 'paraf');

        return [
            'overall' => $total > 0 ? round(($approved / $total) * 100, 1) : 0,
            'signatures' => $signatures->count() > 0 ? round(($signatures->where('status', 'sudah')->count() / $signatures->count()) * 100, 1) : 0,
            'parafs' => $parafs->count() > 0 ? round(($parafs->where('status', 'sudah')->count() / $parafs->count()) * 100, 1) : 0,
        ];
    }

    /**
     * Select PDF template based on unit kerja
     */
    private function selectPDFTemplate(string $unitKerja): string
    {
        $unitKerjaLower = strtolower($unitKerja);

        if (strpos($unitKerjaLower, 'puskesmas') !== false) {
            // Use formal form template for Puskesmas ASN/PPPK
            return 'pdf.surat-cuti-puskesmas-asn';
        } elseif (strpos($unitKerjaLower, 'sekretariat') !== false) {
            return 'pdf.surat-cuti-sekretariat';
        } elseif (strpos($unitKerjaLower, 'bidang') !== false) {
            return 'pdf.surat-cuti-bidang';
        } else {
            // Default ASN template
            return 'pdf.surat-cuti-resmi-flexible';
        }
    }

    /**
     * Enhance data for specific template
     */
    private function enhanceDataForTemplate(array $data, string $unitKerja): array
    {
        $unitKerjaLower = strtolower($unitKerja);

        if (strpos($unitKerjaLower, 'puskesmas') !== false) {
            return $this->enhanceDataForPuskesmas($data, $unitKerja);
        } elseif (strpos($unitKerjaLower, 'sekretariat') !== false) {
            return $this->enhanceDataForSekretariat($data);
        } elseif (strpos($unitKerjaLower, 'bidang') !== false) {
            return $this->enhanceDataForBidang($data, $unitKerja);
        }

        return $this->enhanceDataForASN($data);
    }

    /**
     * Enhance data for Puskesmas template
     */
    private function enhanceDataForPuskesmas(array $data, string $unitKerja): array
    {
        // Get disposisi data if available
        $disposisiList = $data['disposisiList'] ?? collect();

        // Find KADIN disposisi
        $kadin = $disposisiList->where('jabatan', 'KADIN')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas Kesehatan')->first()
              ?? $disposisiList->filter(function ($disposisi) {
                  return $disposisi->user && $disposisi->user->role === 'kadin';
              })->first();

        // Find Kepala Puskesmas disposisi
        $atasanLangsung = $disposisiList->where('jabatan', 'Kepala Puskesmas')->first()
                       ?? $disposisiList->where('tipe_disposisi', 'paraf')->first();

        // Get signatures
        $kadinSignature = Signature::getByJabatan('KADIN');
        $atasanSignature = Signature::getByJabatan('Kepala Puskesmas');

        return array_merge($data, [
            'puskesmas_name' => 'Puskesmas '.str_replace('Puskesmas ', '', $unitKerja),
            'puskesmas_address' => 'Jl. Kesehatan Masyarakat No. 1',
            'puskesmas_phone' => '(0275) 123456',
            'puskesmas_code' => 'PKM'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'kepala_puskesmas' => 'dr. Kepala Puskesmas',
            'kepala_puskesmas_nip' => '196501011990031001',
            'dokter_pemeriksa' => 'dr. Dokter Puskesmas',
            'diagnosis' => 'Sesuai surat keterangan dokter',
            'tanggal_periksa' => now()->format('d F Y'),
            'rekomendasi' => 'Istirahat total sesuai anjuran medis',

            // Add signature data
            'kadin' => $kadin,
            'kadinSignature' => $kadinSignature,
            'atasanLangsung' => $atasanLangsung,
            'atasanSignature' => $atasanSignature,
        ]);
    }

    /**
     * Enhance data for Sekretariat template
     */
    private function enhanceDataForSekretariat(array $data): array
    {
        // Get disposisi data if available
        $disposisiList = $data['disposisiList'] ?? collect();

        // Find KADIN disposisi
        $kadin = $disposisiList->where('jabatan', 'KADIN')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas Kesehatan')->first()
              ?? $disposisiList->filter(function ($disposisi) {
                  return $disposisi->user && $disposisi->user->role === 'kadin';
              })->first();

        // Find SEKDIN disposisi
        $sekdin = $disposisiList->where('jabatan', 'SEKDIN')->first()
               ?? $disposisiList->where('jabatan', 'Sekretaris Dinas')->first()
               ?? $disposisiList->where('tipe_disposisi', 'paraf')->first();

        // Get signatures
        $kadinSignature = Signature::getByJabatan('KADIN');
        $sekdinSignature = Signature::getByJabatan('SEKDIN');

        return array_merge($data, [
            'bagian' => 'Sekretariat Dinas Kesehatan',
            'sekretaris_dinas' => 'Sekretaris Dinas Kesehatan',
            'sekretaris_dinas_nip' => '196502021991032002',
            'alamat_sekretariat' => 'Jl. Pemda No. 1, Purworejo',
            'telepon_sekretariat' => '(0275) 321654',
            'email_sekretariat' => 'sekretariat@dinkes.purworejokab.go.id',
            'golongan' => 'III/c',
            'sisa_cuti' => '12 hari',
            'cuti_diambil' => '0 hari',
            'nomor_absen' => 'A'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'status_kepegawaian' => 'PNS Aktif',
            'alamat_cuti' => 'Sesuai alamat KTP',

            // Add signature data
            'kadin' => $kadin,
            'kadinSignature' => $kadinSignature,
            'sekdin' => $sekdin,
            'sekdinSignature' => $sekdinSignature,
            'atasanLangsung' => $sekdin, // Alias for compatibility
            'atasanSignature' => $sekdinSignature, // Alias for compatibility
        ]);
    }

    /**
     * Enhance data for Bidang template
     */
    private function enhanceDataForBidang(array $data, string $unitKerja): array
    {
        // Get disposisi data if available
        $disposisiList = $data['disposisiList'] ?? collect();

        // Find KADIN disposisi
        $kadin = $disposisiList->where('jabatan', 'KADIN')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas Kesehatan')->first()
              ?? $disposisiList->filter(function ($disposisi) {
                  return $disposisi->user && $disposisi->user->role === 'kadin';
              })->first();

        // Find Kepala Bidang disposisi
        $kepalaBidang = $disposisiList->where('jabatan', 'Kepala Bidang')->first()
                     ?? $disposisiList->where('tipe_disposisi', 'paraf')->first();

        // Get signatures
        $kadinSignature = Signature::getByJabatan('KADIN');
        $kepalaBidangSignature = Signature::getByJabatan('Kepala Bidang');

        return array_merge($data, [
            'nama_bidang' => $unitKerja,
            'kepala_bidang' => 'Kepala '.$unitKerja,
            'kepala_bidang_nip' => '196503031992033003',
            'kode_bidang' => 'BDG'.str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'program_utama' => 'Pelayanan Kesehatan Masyarakat',
            'target_tahun' => '95% Cakupan Pelayanan',
            'spesialisasi' => 'Kesehatan Masyarakat',
            'program_saat_ini' => 'Program Imunisasi Nasional',
            'tanggung_jawab' => 'Koordinator Lapangan',
            'backup_officer' => 'Staff Bidang Lainnya',
            'status_program' => 'Berjalan Normal',
            'target_bulanan' => '80% Cakupan',
            'pencapaian' => '75%',
            'rencana_setelah_cuti' => 'Lanjutkan program normal',
            'koordinasi' => 'Dengan Kepala Bidang',
            'dampak_program' => 'Minimal, sudah ada backup',

            // Add signature data
            'kadin' => $kadin,
            'kadinSignature' => $kadinSignature,
            'kepalaBidang' => $kepalaBidang,
            'kepalaBidangSignature' => $kepalaBidangSignature,
            'atasanLangsung' => $kepalaBidang, // Alias for compatibility
            'atasanSignature' => $kepalaBidangSignature, // Alias for compatibility
        ]);
    }

    /**
     * Enhance data for ASN template
     */
    private function enhanceDataForASN(array $data): array
    {
        // Get disposisi data if available
        $disposisiList = $data['disposisiList'] ?? collect();

        // Find KADIN disposisi
        $kadin = $disposisiList->where('jabatan', 'KADIN')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas')->first()
              ?? $disposisiList->where('jabatan', 'Kepala Dinas Kesehatan')->first()
              ?? $disposisiList->filter(function ($disposisi) {
                  return $disposisi->user && $disposisi->user->role === 'kadin';
              })->first();

        // Find atasan langsung (could be various positions)
        $atasanLangsung = $disposisiList->where('tipe_disposisi', 'paraf')->first();

        // Get signatures
        $kadinSignature = Signature::getByJabatan('KADIN');
        $atasanSignature = null;
        if ($atasanLangsung) {
            $atasanSignature = Signature::getByJabatan($atasanLangsung->jabatan);
        }

        return array_merge($data, [
            'jenis_pegawai' => 'Aparatur Sipil Negara (ASN)',
            'instansi' => 'Dinas Kesehatan Kabupaten Purworejo',
            'alamat_instansi' => 'Jl. Pemda No. 1, Purworejo',
            'telepon_instansi' => '(0275) 321654',
            'website_instansi' => 'dinkes.purworejokab.go.id',

            // Add signature data
            'kadin' => $kadin,
            'kadinSignature' => $kadinSignature,
            'atasanLangsung' => $atasanLangsung,
            'atasanSignature' => $atasanSignature,
        ]);
    }

    /**
     * Generate PDF for approved surat cuti with flexible approval
     */
    public function pdf(SuratCuti $suratCuti)
    {
        try {
            // Allow PDF generation for approved surat or surat with sufficient approvals
            if (! $this->canGeneratePDF($suratCuti)) {
                return back()->with('error', 'PDF hanya dapat diunduh untuk surat cuti yang sudah memenuhi persyaratan persetujuan.');
            }

            // Check access permission
            $user = Auth::user();
            if ($user->role !== 'admin' && $suratCuti->pengaju_id !== $user->id) {
                // Check if user is in disposisi chain
                $hasAccess = $suratCuti->disposisiCuti()
                    ->where('user_id', $user->id)
                    ->exists();
                if (! $hasAccess) {
                    return back()->with('error', 'Anda tidak memiliki akses untuk mengunduh PDF ini.');
                }
            }

            // Load relationships yang diperlukan
            $suratCuti->load(['pengaju', 'jenisCuti', 'disposisiCuti.user', 'puskesmas']);

            // Get disposisi data dengan urutan yang benar (berdasarkan workflow)
            $disposisiList = $suratCuti->disposisiCuti()
                ->with(['user'])
                ->orderBy('created_at', 'asc')
                ->get();

            // Find KADIN disposisi (cek berbagai kemungkinan nama jabatan)
            $kadinDisposisi = $disposisiList->where('jabatan', 'KADIN')->first()
                           ?? $disposisiList->where('jabatan', 'Kepala Dinas')->first()
                           ?? $disposisiList->where('jabatan', 'Kepala Dinas Kesehatan')->first()
                           ?? $disposisiList->filter(function ($disposisi) {
                               return $disposisi->user && $disposisi->user->role === 'kadin';
                           })->first()
                           ?? $disposisiList->filter(function ($disposisi) {
                               return $disposisi->user && stripos($disposisi->user->jabatan, 'kepala dinas') !== false;
                           })->first();

            // Prepare data untuk PDF dengan validasi konsistensi
            $pdfData = [
                'suratCuti' => $suratCuti,
                'disposisiList' => $disposisiList,
                'kadinDisposisi' => $kadinDisposisi,
                'pengaju' => $suratCuti->pengaju,
                'jenisCuti' => $suratCuti->jenisCuti,
                'tanggal_cetak' => now(),
                'jumlah_hari' => $suratCuti->jumlah_hari,
                'signatureHelper' => $this->getSignatureHelper(),
                'formatTanggal' => function ($date) {
                    return $this->formatTanggalIndonesia($date);
                },
                'isApproved' => $suratCuti->status === 'disetujui',
                'approvalDate' => $kadinDisposisi ? $kadinDisposisi->tanggal : null,
                // Data tambahan untuk konsistensi
                'nomorSurat' => $this->generateNomorSurat($suratCuti),
                'statusSurat' => $suratCuti->status,
                'tanggalPengajuan' => $suratCuti->created_at,
            ];

            // Cek apakah DomPDF tersedia
            if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                try {
                    // Map data ke template BLANKO CUTI resmi
                    $atasanParaf = $disposisiList->firstWhere('tipe_disposisi', 'paraf');
                    $pejabatUser = ($kadinDisposisi && $kadinDisposisi->user) ? $kadinDisposisi->user : null;

                    $signatureHelper = $this->getSignatureHelper();
                    $pemohon_signature_base64 = $signatureHelper($suratCuti->pengaju, true);
                    $atasan_signature_base64 = ($atasanParaf && $atasanParaf->user) ? $signatureHelper($atasanParaf->user, true) : null;
                    $pejabat_signature_base64 = $pejabatUser ? $signatureHelper($pejabatUser, true) : null;

                    $atasan_cap_base64 = null;
                    if ($atasanParaf && $atasanParaf->user && $atasanParaf->user->cap_stempel && ($atasanParaf->user->gunakan_cap ?? false)) {
                        $capPath = public_path('storage/'.$atasanParaf->user->cap_stempel);
                        if (file_exists($capPath)) {
                            try {
                                $atasan_cap_base64 = 'data:'.mime_content_type($capPath).';base64,'.base64_encode(file_get_contents($capPath));
                            } catch (\Exception $e) {
                                $atasan_cap_base64 = null;
                            }
                        }
                    }

                    $pejabat_cap_base64 = null;
                    if ($pejabatUser && $pejabatUser->cap_stempel && ($pejabatUser->gunakan_cap ?? false)) {
                        $capPath = public_path('storage/'.$pejabatUser->cap_stempel);
                        if (file_exists($capPath)) {
                            try {
                                $pejabat_cap_base64 = 'data:'.mime_content_type($capPath).';base64,'.base64_encode(file_get_contents($capPath));
                            } catch (\Exception $e) {
                                $pejabat_cap_base64 = null;
                            }

                        }
                    }

                    $atasanStatus = '';
                    if ($atasanParaf) {
                        if ($atasanParaf->status === 'sudah') {
                            $atasanStatus = 'disetujui';
                        } elseif ($atasanParaf->status === 'ditolak') {
                            $atasanStatus = 'tidak_disetujui';
                        }
                    } else {
                        if ($suratCuti->status === 'disetujui') {
                            $atasanStatus = 'disetujui';
                        } elseif ($suratCuti->status === 'ditolak') {
                            $atasanStatus = 'tidak_disetujui';
                        }
                    }

                    $pejabatStatus = '';
                    if ($kadinDisposisi) {
                        if ($kadinDisposisi->status === 'sudah') {
                            $pejabatStatus = 'disetujui';
                        } elseif ($kadinDisposisi->status === 'ditolak') {
                            $pejabatStatus = 'tidak_disetujui';
                        }
                    } else {
                        if ($suratCuti->status === 'disetujui') {
                            $pejabatStatus = 'disetujui';
                        } elseif ($suratCuti->status === 'ditolak') {
                            $pejabatStatus = 'tidak_disetujui';

                        }
                    }

                    $blankoData = [
                        // Data Pegawai
                        'nama_pegawai' => $suratCuti->pengaju->nama,
                        'nip_pegawai' => $suratCuti->pengaju->nip,
                        'jabatan' => $suratCuti->pengaju->jabatan,
                        'masa_kerja' => $suratCuti->masa_jabatan ?? $suratCuti->pengaju->masa_kerja ?? '5 Tahun 0 Bulan',
                        'unit_kerja' => $suratCuti->pengaju->unit_kerja,
                        'golongan' => $suratCuti->golongan_ruang ?? $suratCuti->pengaju->golongan ?? 'III/a',

                        // Data Surat
                        'tempat' => $suratCuti->pengaju->kota ?? 'Purworejo',
                        'tanggal_surat' => $this->formatTanggalIndonesia($suratCuti->created_at),
                        'kabupaten' => $suratCuti->pengaju->kabupaten ?? 'Purworejo',
                        'nomor_surat' => $pdfData['nomorSurat'],

                        // Data Cuti
                        'jenis_cuti' => $suratCuti->jenisCuti->nama,
                        'alasan_cuti' => $suratCuti->alasan,
                        'lama_cuti' => $suratCuti->jumlah_hari,
                        'tanggal_mulai' => $this->formatTanggalIndonesia(\Carbon\Carbon::parse($suratCuti->tanggal_awal)),
                        'tanggal_selesai' => $this->formatTanggalIndonesia(\Carbon\Carbon::parse($suratCuti->tanggal_akhir)),

                        // Alamat
                        'alamat_cuti' => $suratCuti->alamat_selama_cuti ?? ($suratCuti->alamat_cuti ?? ''),
                        'telepon' => $suratCuti->kontak_darurat ?? ($suratCuti->telepon ?? ''),

                        // Pejabat
                        'atasan_langsung' => $atasanParaf && $atasanParaf->user ? $atasanParaf->user->nama : 'Atasan Langsung',
                        'nip_atasan' => $atasanParaf && $atasanParaf->user ? ($atasanParaf->user->nip ?? '') : '',
                        'pejabat_berwenang' => $pejabatUser ? $pejabatUser->nama : 'Kepala Dinas',
                        'nip_pejabat' => $pejabatUser ? ($pejabatUser->nip ?? '') : '',

                        // Gambar tanda tangan & cap (base64 data URI)
                        'pemohon_signature_base64' => $pemohon_signature_base64,
                        'atasan_signature_base64' => $atasan_signature_base64,
                        'pejabat_signature_base64' => $pejabat_signature_base64,
                        'atasan_cap_base64' => $atasan_cap_base64,
                        'pejabat_cap_base64' => $pejabat_cap_base64,

                        // Status (opsional, mempengaruhi centang di bagian VII/VIII)
                        'pertimbangan_atasan' => $atasanStatus,
                        'keputusan_pejabat' => $pejabatStatus,
                    ];

                    // Enhanced PDF with flexible approval status
                    $blankoData['isFlexibleApproval'] = $suratCuti->status !== 'disetujui';
                    $blankoData['approvalStatus'] = $this->getApprovalStatus($suratCuti);
                    $blankoData['completionRate'] = $this->getCompletionRate($suratCuti);

                    // Select template based on unit kerja
                    $template = $this->selectPDFTemplate($suratCuti->pengaju->unit_kerja);
                    $blankoData = $this->enhanceDataForTemplate($blankoData, $suratCuti->pengaju->unit_kerja);

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($template, $blankoData);

                    // Set paper size dan orientation
                    $pdf->setPaper('A4', 'portrait');

                    // Generate filename yang user-friendly dengan status
                    $statusSuffix = $suratCuti->status === 'disetujui' ? 'APPROVED' : 'PARTIAL';
                    $filename = "surat-cuti-{$suratCuti->pengaju->nama}-{$statusSuffix}-{$suratCuti->id}.pdf";
                    $filename = str_replace(' ', '-', $filename);

                    return $pdf->download($filename);
                } catch (\Exception $e) {
                    Log::error('PDF Generation Error: '.$e->getMessage());

                    // Fallback ke preview
                    return view('surat-cuti.pdf-preview', $pdfData)
                        ->with('error', 'Gagal generate PDF. Menampilkan preview.');
                }
            } else {
                // DomPDF belum terinstall, tampilkan preview
                return view('surat-cuti.pdf-preview', $pdfData)
                    ->with('info', 'DomPDF belum terinstall. Menampilkan preview yang bisa di-print.');
            }
        } catch (\Exception $e) {
            Log::error('PDF Controller Error: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat mengakses PDF.');
        }
    }

    /**
     * Helper function untuk signature processing
     */
    private function getSignatureHelper()
    {
        return function ($user, $isForPdf = true) {
            if (! $user || ! $user->tanda_tangan) {
                return null;
            }

            $signaturePath = public_path('storage/'.$user->tanda_tangan);

            if (! file_exists($signaturePath)) {
                return null;
            }

            if ($isForPdf) {
                // Untuk PDF, gunakan base64
                try {
                    $imageData = base64_encode(file_get_contents($signaturePath));
                    $imageMime = mime_content_type($signaturePath);

                    return "data:{$imageMime};base64,{$imageData}";
                } catch (\Exception $e) {
                    Log::error('Signature processing error: '.$e->getMessage());

                    return null;
                }
            } else {
                // Untuk preview, gunakan asset URL
                return asset('storage/'.$user->tanda_tangan);
            }
        };
    }

    /**
     * Helper function untuk format tanggal Indonesia
     */
    private function formatTanggalIndonesia($date)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $date->day.' '.$bulan[$date->month].' '.$date->year;
    }

    /**
     * Generate nomor surat yang konsisten
     */
    private function generateNomorSurat($suratCuti)
    {
        $tahun = $suratCuti->created_at->year;
        $id = str_pad($suratCuti->id, 4, '0', STR_PAD_LEFT);

        return "800.1.11.4/{$id}/{$tahun}";
    }

    /**
     * Download PDF for approved surat cuti (for employees)
     */
    public function downloadPDF(SuratCuti $suratCuti)
    {
        // Check if user can access this surat cuti
        if (auth()->user()->role === 'karyawan' && $suratCuti->pengaju_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke surat cuti ini.');
        }

        // Check if surat cuti is approved
        if ($suratCuti->status !== 'disetujui') {
            return back()->with('error', 'PDF hanya dapat didownload untuk surat cuti yang sudah disetujui.');
        }

        try {
            // Get disposisi list ordered by creation (workflow order)
            $disposisiList = $suratCuti->disposisiCuti()->orderBy('created_at')->get();

            // Prepare data for PDF
            $blankoData = [
                'suratCuti' => $suratCuti,
                'disposisiList' => $disposisiList,
                'isFlexibleApproval' => false, // Always false for approved surat
                'approvalStatus' => $this->getApprovalStatus($suratCuti),
                'completionRate' => ['overall' => 100, 'signatures' => 100, 'parafs' => 100],
            ];

            // Select template based on unit kerja
            $template = $this->selectPDFTemplate($suratCuti->pengaju->unit_kerja);
            $blankoData = $this->enhanceDataForTemplate($blankoData, $suratCuti->pengaju->unit_kerja);

            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($template, $blankoData);
            $pdf->setPaper('A4', 'portrait');

            // Generate filename
            $pengajuName = str_replace(' ', '-', $suratCuti->pengaju->nama);
            $filename = "surat-cuti-{$pengajuName}-APPROVED-{$suratCuti->id}.pdf";

            // Log download activity
            \Log::info('PDF downloaded', [
                'surat_cuti_id' => $suratCuti->id,
                'downloaded_by' => auth()->id(),
                'filename' => $filename,
                'template' => $template,
            ]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF download error', [
                'surat_cuti_id' => $suratCuti->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menggenerate PDF: '.$e->getMessage());
        }
    }

    /**
     * Admin Dashboard untuk Bulk Operations
     */
    public function adminDashboard(Request $request)
    {
        // Hanya admin dan kadin yang bisa akses
        if (! in_array(auth()->user()->role, ['admin', 'kadin'])) {
            abort(403, 'Unauthorized');
        }

        // Get sorting parameters
        $sortPendingBy = $request->get('sort_pending_by', 'created_at');
        $sortPendingDirection = $request->get('sort_pending_direction', 'desc');
        $sortApprovedBy = $request->get('sort_approved_by', 'updated_at');
        $sortApprovedDirection = $request->get('sort_approved_direction', 'desc');

        // Validate sorting parameters
        $validSortColumns = ['id', 'created_at', 'updated_at', 'nama', 'unit_kerja', 'jenis_cuti', 'tanggal_awal', 'jumlah_hari'];
        $validDirections = ['asc', 'desc'];

        if (!in_array($sortPendingBy, $validSortColumns)) {
            $sortPendingBy = 'created_at';
        }

        if (!in_array($sortPendingDirection, $validDirections)) {
            $sortPendingDirection = 'desc';
        }

        if (!in_array($sortApprovedBy, $validSortColumns)) {
            $sortApprovedBy = 'updated_at';
        }

        if (!in_array($sortApprovedDirection, $validDirections)) {
            $sortApprovedDirection = 'desc';
        }

        // Build query for pending surat cuti with sorting
        $pendingQuery = SuratCuti::whereIn('status', ['draft', 'proses'])
            ->with(['pengaju', 'jenisCuti']);

        // Apply sorting for pending surat
        if ($sortPendingBy === 'nama') {
            $pendingQuery->join('users', 'surat_cuti.pengaju_id', '=', 'users.id')
                ->orderBy('users.nama', $sortPendingDirection)
                ->select('surat_cuti.*');
        } elseif ($sortPendingBy === 'unit_kerja') {
            $pendingQuery->join('users', 'surat_cuti.pengaju_id', '=', 'users.id')
                ->orderBy('users.unit_kerja', $sortPendingDirection)
                ->select('surat_cuti.*');
        } elseif ($sortPendingBy === 'jenis_cuti') {
            $pendingQuery->join('jenis_cuti', 'surat_cuti.jenis_cuti_id', '=', 'jenis_cuti.id')
                ->orderBy('jenis_cuti.nama', $sortPendingDirection)
                ->select('surat_cuti.*');
        } elseif ($sortPendingBy === 'tanggal_awal') {
            $pendingQuery->orderBy('surat_cuti.tanggal_awal', $sortPendingDirection);
        } elseif ($sortPendingBy === 'jumlah_hari') {
            // For jumlah_hari, we need to order by the difference between dates
            if ($sortPendingDirection === 'asc') {
                $pendingQuery->orderByRaw('DATEDIFF(tanggal_akhir, tanggal_awal) ASC');
            } else {
                $pendingQuery->orderByRaw('DATEDIFF(tanggal_akhir, tanggal_awal) DESC');
            }
        } else {
            $pendingQuery->orderBy("surat_cuti.{$sortPendingBy}", $sortPendingDirection);
        }

        $pendingSuratCuti = $pendingQuery->get();

        // Build query for approved surat cuti with sorting
        $approvedQuery = SuratCuti::where('status', 'disetujui')
            ->with(['pengaju', 'jenisCuti']);

        // Apply sorting for approved surat
        if ($sortApprovedBy === 'nama') {
            $approvedQuery->join('users', 'surat_cuti.pengaju_id', '=', 'users.id')
                ->orderBy('users.nama', $sortApprovedDirection)
                ->select('surat_cuti.*');
        } elseif ($sortApprovedBy === 'unit_kerja') {
            $approvedQuery->join('users', 'surat_cuti.pengaju_id', '=', 'users.id')
                ->orderBy('users.unit_kerja', $sortApprovedDirection)
                ->select('surat_cuti.*');
        } elseif ($sortApprovedBy === 'jenis_cuti') {
            $approvedQuery->join('jenis_cuti', 'surat_cuti.jenis_cuti_id', '=', 'jenis_cuti.id')
                ->orderBy('jenis_cuti.nama', $sortApprovedDirection)
                ->select('surat_cuti.*');
        } elseif ($sortApprovedBy === 'tanggal_awal') {
            $approvedQuery->orderBy('surat_cuti.tanggal_awal', $sortApprovedDirection);
        } elseif ($sortApprovedBy === 'jumlah_hari') {
            // For jumlah_hari, we need to order by the difference between dates
            if ($sortApprovedDirection === 'asc') {
                $approvedQuery->orderByRaw('DATEDIFF(tanggal_akhir, tanggal_awal) ASC');
            } else {
                $approvedQuery->orderByRaw('DATEDIFF(tanggal_akhir, tanggal_awal) DESC');
            }
        } else {
            $approvedQuery->orderBy("surat_cuti.{$sortApprovedBy}", $sortApprovedDirection);
        }

        $approvedSuratCuti = $approvedQuery->limit(10)->get();

        // Pass sorting parameters to view
        $sortingParams = [
            'sort_pending_by' => $sortPendingBy,
            'sort_pending_direction' => $sortPendingDirection,
            'sort_approved_by' => $sortApprovedBy,
            'sort_approved_direction' => $sortApprovedDirection,
        ];

        return view('admin.surat-cuti.dashboard', compact('pendingSuratCuti', 'approvedSuratCuti', 'sortingParams'));
    }

    /**
     * Bulk Approve All Pending Surat Cuti
     */
    public function bulkApproveAll()
    {
        // Hanya admin dan kadin yang bisa akses
        if (! in_array(auth()->user()->role, ['admin', 'kadin'])) {
            abort(403, 'Unauthorized');
        }

        $pendingSuratCuti = SuratCuti::whereIn('status', ['draft', 'proses'])->get();
        $approvedCount = 0;

        foreach ($pendingSuratCuti as $surat) {
            // Update status ke disetujui
            $surat->status = 'disetujui';
            $surat->save();

            // Buat disposisi otomatis untuk semua level
            $this->createAutoDisposisi($surat);

            $approvedCount++;
        }

        // Handle both AJAX and form submission
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$approvedCount} surat cuti",
                'approved_count' => $approvedCount,
            ]);
        } else {
            return redirect()->route('admin.surat-cuti.admin-dashboard')
                ->with('success', "Berhasil menyetujui {$approvedCount} surat cuti");
        }
    }

    /**
     * Bulk Reject All Pending Surat Cuti
     */
    public function bulkRejectAll()
    {
        // Hanya admin dan kadin yang bisa akses
        if (! in_array(auth()->user()->role, ['admin', 'kadin'])) {
            abort(403, 'Unauthorized');
        }

        $pendingSuratCuti = SuratCuti::whereIn('status', ['draft', 'proses'])->get();
        $rejectedCount = 0;

        foreach ($pendingSuratCuti as $surat) {
            $surat->status = 'ditolak';
            $surat->save();
            $rejectedCount++;
        }

        // Handle both AJAX and form submission
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil menolak {$rejectedCount} surat cuti",
                'rejected_count' => $rejectedCount,
            ]);
        } else {
            return redirect()->route('admin.surat-cuti.admin-dashboard')
                ->with('success', "Berhasil menolak {$rejectedCount} surat cuti");
        }
    }

    /**
     * Create auto disposisi for bulk approval
     */
    private function createAutoDisposisi(SuratCuti $suratCuti)
    {
        $unitKerja = $suratCuti->pengaju->unit_kerja;
        $puskesmas = $suratCuti->puskesmas;
        $disposisiList = [];

        // Cek apakah puskesmas menggunakan workflow khusus
        if ($puskesmas && $puskesmas->gunakan_workflow_khusus && $puskesmas->kepalaPuskesmas) {
            // Buat disposisi khusus untuk puskesmas
            $this->createAutoDisposisiKhususPuskesmas($suratCuti, $puskesmas);
            return;
        }

        // Tentukan disposisi berdasarkan unit kerja
        if (stripos($unitKerja, 'puskesmas') !== false) {
            $disposisiList = [
                ['jabatan' => 'Kepala Puskesmas', 'tipe_disposisi' => 'paraf'],
                ['jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd'],
            ];
        } elseif (stripos($unitKerja, 'sekretariat') !== false) {
            $disposisiList = [
                ['jabatan' => 'Sekretaris Dinas', 'tipe_disposisi' => 'paraf'],
                ['jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd'],
            ];
        } else {
            // Bidang atau unit lain
            $disposisiList = [
                ['jabatan' => 'Kepala Bidang', 'tipe_disposisi' => 'paraf'],
                ['jabatan' => 'KADIN', 'tipe_disposisi' => 'ttd'],
            ];
        }

        // Buat disposisi otomatis dengan pengecekan duplikasi
        foreach ($disposisiList as $disposisi) {
            // Cek apakah disposisi sudah ada
            $existingDisposisi = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                ->where('jabatan', $disposisi['jabatan'])
                ->first();
                
            // Hanya buat jika belum ada
            if (! $existingDisposisi) {
                DisposisiCuti::create([
                    'surat_cuti_id' => $suratCuti->id,
                    'user_id' => auth()->id(), // Admin yang approve
                    'jabatan' => $disposisi['jabatan'],
                    'tipe_disposisi' => $disposisi['tipe_disposisi'],
                    'status' => 'sudah',
                    'tanggal' => now(),
                    'catatan' => 'Auto-approved by admin for debugging',
                ]);
            }
        }
    }
    
    /**
     * Create auto disposisi khusus untuk puskesmas
     */
    private function createAutoDisposisiKhususPuskesmas(SuratCuti $suratCuti, \App\Models\Puskesmas $puskesmas)
    {
        // Buat disposisi untuk kepala puskesmas dengan pengecekan duplikasi
        $kepalaPuskesmas = $puskesmas->kepalaPuskesmas;
        if ($kepalaPuskesmas) {
            // Cek apakah disposisi sudah ada
            $existingDisposisi = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                ->where('user_id', $kepalaPuskesmas->id)
                ->where('jabatan', 'Kepala Puskesmas')
                ->first();
                
            // Hanya buat jika belum ada
            if (! $existingDisposisi) {
                DisposisiCuti::create([
                    'surat_cuti_id' => $suratCuti->id,
                    'user_id' => $kepalaPuskesmas->id,
                    'jabatan' => 'Kepala Puskesmas',
                    'tipe_disposisi' => 'paraf',
                    'status' => 'sudah',
                    'tanggal' => now(),
                    'catatan' => 'Auto-approved by admin for debugging',
                ]);
            }
        }
        
        // Tambahkan disposisi KADIN dengan pengecekan duplikasi
        $kadin = User::where('role', 'kadin')->first();
        if ($kadin) {
            // Cek apakah disposisi sudah ada
            $existingDisposisi = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                ->where('user_id', $kadin->id)
                ->where('jabatan', 'KADIN')
                ->first();
                
            // Hanya buat jika belum ada
            if (! $existingDisposisi) {
                DisposisiCuti::create([
                    'surat_cuti_id' => $suratCuti->id,
                    'user_id' => $kadin->id,
                    'jabatan' => 'KADIN',
                    'tipe_disposisi' => 'ttd',
                    'status' => 'sudah',
                    'tanggal' => now(),
                    'catatan' => 'Auto-approved by admin for debugging',
                ]);
            }
        }
    }
}
