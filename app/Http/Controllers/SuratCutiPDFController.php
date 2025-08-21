<?php

namespace App\Http\Controllers;

use App\Models\SuratCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratCutiPDFController extends Controller
{
    public function generate(SuratCuti $suratCuti)
    {
        // Hitung masa kerja
        $tanggal_masuk = Carbon::parse($suratCuti->pengaju->tanggal_masuk);
        $masa_kerja = $tanggal_masuk->diffInYears(Carbon::now()) . ' Tahun ' . 
                     $tanggal_masuk->diffInMonths(Carbon::now()) % 12 . ' Bulan';

        // Ambil data atasan
        $atasan = User::where('jabatan', 'like', '%Kepala%')
                     ->where('unit_kerja', $suratCuti->pengaju->unit_kerja)
                     ->first();

        // Ambil data kepala dinas
        $kepala_dinas = User::where('jabatan', 'Kepala Dinas')
                           ->first();

        // Hitung sisa cuti
        $sisa_cuti = 12; // Default jatah cuti tahunan
        $sisa_cuti_tahun_lalu = 0;

        // Cek penggunaan cuti tahun ini
        $cuti_terpakai = SuratCuti::where('pengaju_id', $suratCuti->pengaju_id)
                                 ->whereYear('created_at', date('Y'))
                                 ->where('status', 'disetujui')
                                 ->sum('lama_hari');
        
        $sisa_cuti -= $cuti_terpakai;

        $pdf = PDF::loadView('pdf.surat-cuti', [
            'surat' => $suratCuti,
            'masa_kerja' => $masa_kerja,
            'atasan' => $atasan,
            'kepala_dinas' => $kepala_dinas,
            'sisa_cuti' => $sisa_cuti,
            'sisa_cuti_tahun_lalu' => $sisa_cuti_tahun_lalu
        ]);

        return $pdf->stream('surat-cuti-' . $suratCuti->nomor_surat . '.pdf');
    }
}
