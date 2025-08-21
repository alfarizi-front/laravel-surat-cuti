<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratCuti;
use App\Models\JenisCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display laporan surat cuti dengan filter
     */
    public function index(Request $request)
    {
        // Manual check admin role
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        try {
            $query = SuratCuti::with(['pengaju', 'jenisCuti'])
                              ->where('status', 'disetujui');

            // Filter berdasarkan tanggal
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_awal', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_akhir', '<=', $request->tanggal_selesai);
            }

            // Filter berdasarkan bulan dan tahun
            if ($request->filled('bulan') && $request->filled('tahun')) {
                $query->whereMonth('tanggal_awal', $request->bulan)
                      ->whereYear('tanggal_awal', $request->tahun);
            }

            // Filter berdasarkan unit kerja
            if ($request->filled('unit_kerja')) {
                $query->whereHas('pengaju', function($q) use ($request) {
                    $q->where('unit_kerja', $request->unit_kerja);
                });
            }

            // Filter berdasarkan jenis pegawai
            if ($request->filled('jenis_pegawai')) {
                $query->whereHas('pengaju', function($q) use ($request) {
                    $q->where('jenis_pegawai', $request->jenis_pegawai);
                });
            }

            // Filter berdasarkan jenis cuti
            if ($request->filled('jenis_cuti_id')) {
                $query->where('jenis_cuti_id', $request->jenis_cuti_id);
            }

            // Filter berdasarkan nama pegawai
            if ($request->filled('nama_pegawai')) {
                $query->whereHas('pengaju', function($q) use ($request) {
                    $q->where('nama', 'like', "%{$request->nama_pegawai}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'tanggal_awal');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $suratCuti = $query->paginate(20)->withQueryString();

            // Data untuk filter options
            $jenisCuti = JenisCuti::all();
            $unitKerja = User::distinct()->pluck('unit_kerja')->filter()->sort();
            $jenisPegawai = ['ASN', 'PPPK'];

            // Statistik
            $statistik = $this->getStatistik($request);

            return view('admin.laporan.index', compact(
                'suratCuti',
                'jenisCuti',
                'unitKerja',
                'jenisPegawai',
                'statistik'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan ke Excel/CSV
     */
    public function export(Request $request)
    {
        // Manual check admin role
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $query = SuratCuti::with(['pengaju', 'jenisCuti'])
                          ->where('status', 'disetujui');

        // Apply same filters as index
        $this->applyFilters($query, $request);

        $suratCuti = $query->with(['pengaju', 'jenisCuti'])->orderBy('tanggal_awal', 'desc')->get();

        $filename = 'laporan-surat-cuti-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($suratCuti) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Pegawai',
                'NIP',
                'Unit Kerja',
                'Jabatan',
                'Jenis Pegawai',
                'Jenis Cuti',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Jumlah Hari',
                'Alasan',
                'Status',
                'Tanggal Disetujui'
            ]);

            // Data
            foreach ($suratCuti as $index => $surat) {
                fputcsv($file, [
                    $index + 1,
                    $surat->pengaju->nama,
                    $surat->pengaju->nip ?: '-',
                    $surat->pengaju->unit_kerja,
                    $surat->pengaju->jabatan,
                    $surat->pengaju->jenis_pegawai,
                    $surat->jenisCuti->nama ?? 'Cuti',
                    $surat->tanggal_awal->format('d/m/Y'),
                    $surat->tanggal_akhir->format('d/m/Y'),
                    $surat->jumlah_hari,
                    $surat->alasan,
                    ucfirst($surat->status),
                    $surat->updated_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistik untuk dashboard
     */
    private function getStatistik($request)
    {
        $query = SuratCuti::where('status', 'disetujui');
        $this->applyFilters($query, $request);

        $suratCutiData = $query->with(['pengaju', 'jenisCuti'])->get();
        $totalSurat = $suratCutiData->count();
        $totalHari = $suratCutiData->sum('jumlah_hari'); // Menggunakan accessor

        // Statistik per jenis cuti
        $perJenisCuti = $suratCutiData->groupBy('jenis_cuti_id')->map(function ($group, $jenisCutiId) {
            return (object) [
                'jenis_cuti_id' => $jenisCutiId,
                'total' => $group->count(),
                'total_hari' => $group->sum('jumlah_hari'),
                'jenisCuti' => $group->first()->jenisCuti
            ];
        })->values();

        // Statistik per unit kerja
        $perUnitKerja = $suratCutiData->groupBy('pengaju.unit_kerja')->map(function ($group, $unitKerja) {
            return (object) [
                'unit_kerja' => $unitKerja,
                'total' => $group->count(),
                'total_hari' => $group->sum('jumlah_hari')
            ];
        })->values();

        // Statistik per bulan (6 bulan terakhir)
        $perBulan = $suratCutiData->filter(function ($surat) {
            return $surat->tanggal_awal >= Carbon::now()->subMonths(6);
        })->groupBy(function ($surat) {
            return $surat->tanggal_awal->format('Y-m');
        })->map(function ($group, $periode) {
            $date = Carbon::createFromFormat('Y-m', $periode);
            return (object) [
                'bulan' => $date->month,
                'tahun' => $date->year,
                'total' => $group->count(),
                'total_hari' => $group->sum('jumlah_hari')
            ];
        })->sortByDesc(function ($item) {
            return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
        })->values();

        return [
            'total_surat' => $totalSurat,
            'total_hari' => $totalHari,
            'per_jenis_cuti' => $perJenisCuti,
            'per_unit_kerja' => $perUnitKerja,
            'per_bulan' => $perBulan,
            'rata_rata_hari' => $totalSurat > 0 ? round($totalHari / $totalSurat, 1) : 0
        ];
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_awal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_akhir', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_awal', $request->bulan)
                  ->whereYear('tanggal_awal', $request->tahun);
        }

        if ($request->filled('unit_kerja')) {
            $query->whereHas('pengaju', function($q) use ($request) {
                $q->where('unit_kerja', $request->unit_kerja);
            });
        }

        if ($request->filled('jenis_pegawai')) {
            $query->whereHas('pengaju', function($q) use ($request) {
                $q->where('jenis_pegawai', $request->jenis_pegawai);
            });
        }

        if ($request->filled('jenis_cuti_id')) {
            $query->where('jenis_cuti_id', $request->jenis_cuti_id);
        }

        if ($request->filled('nama_pegawai')) {
            $query->whereHas('pengaju', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama_pegawai . '%');
            });
        }
    }

    /**
     * Print laporan
     */
    public function print(Request $request)
    {
        // Manual check admin role
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $query = SuratCuti::with(['pengaju', 'jenisCuti'])
                          ->where('status', 'disetujui');

        $this->applyFilters($query, $request);
        $suratCuti = $query->orderBy('tanggal_awal', 'desc')->get();
        $statistik = $this->getStatistik($request);

        return view('admin.laporan.print', compact('suratCuti', 'statistik'));
    }
}
