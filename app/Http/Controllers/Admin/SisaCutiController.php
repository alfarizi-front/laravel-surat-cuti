<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SisaCuti;
use App\Models\User;
use Illuminate\Http\Request;

class SisaCutiController extends Controller
{
    public function index()
    {
        $sisaCutiData = SisaCuti::with('user')
            ->orderBy('tahun', 'desc')
            ->orderBy('user_id')
            ->paginate(20);

        return view('admin.sisa-cuti.index', compact('sisaCutiData'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'admin')->orderBy('nama')->get();
        $tahunOptions = range(date('Y') - 2, date('Y') + 1);

        return view('admin.sisa-cuti.create', compact('users', 'tahunOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'sisa_awal' => 'required|integer|min:0|max:24',
            'cuti_diambil' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $data = $request->only(['user_id', 'tahun', 'sisa_awal', 'cuti_diambil', 'keterangan']);
        $data['sisa_akhir'] = $data['sisa_awal'] - $data['cuti_diambil'];
        $data['is_active'] = true;

        SisaCuti::updateOrCreate(
            ['user_id' => $data['user_id'], 'tahun' => $data['tahun']],
            $data
        );

        return redirect()->route('admin.sisa-cuti.index')
                        ->with('success', 'Data sisa cuti berhasil disimpan');
    }

    public function edit(SisaCuti $sisaCuti)
    {
        $users = User::where('role', '!=', 'admin')->orderBy('nama')->get();
        $tahunOptions = range(date('Y') - 2, date('Y') + 1);

        return view('admin.sisa-cuti.edit', compact('sisaCuti', 'users', 'tahunOptions'));
    }

    public function update(Request $request, SisaCuti $sisaCuti)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'sisa_awal' => 'required|integer|min:0|max:24',
            'cuti_diambil' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $data = $request->only(['user_id', 'tahun', 'sisa_awal', 'cuti_diambil', 'keterangan']);
        $data['sisa_akhir'] = $data['sisa_awal'] - $data['cuti_diambil'];

        $sisaCuti->update($data);

        return redirect()->route('admin.sisa-cuti.index')
                        ->with('success', 'Data sisa cuti berhasil diupdate');
    }

    public function destroy(SisaCuti $sisaCuti)
    {
        $sisaCuti->delete();

        return redirect()->route('admin.sisa-cuti.index')
                        ->with('success', 'Data sisa cuti berhasil dihapus');
    }

    /**
     * Bulk create sisa cuti untuk semua user
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'sisa_awal' => 'required|integer|min:0|max:24'
        ]);

        $users = User::where('role', '!=', 'admin')->get();
        $created = 0;

        foreach ($users as $user) {
            $existing = SisaCuti::where('user_id', $user->id)
                               ->where('tahun', $request->tahun)
                               ->exists();

            if (!$existing) {
                SisaCuti::create([
                    'user_id' => $user->id,
                    'tahun' => $request->tahun,
                    'sisa_awal' => $request->sisa_awal,
                    'cuti_diambil' => 0,
                    'sisa_akhir' => $request->sisa_awal,
                    'is_active' => true,
                    'keterangan' => 'Bulk created for year ' . $request->tahun
                ]);
                $created++;
            }
        }

        return redirect()->route('admin.sisa-cuti.index')
                        ->with('success', "Berhasil membuat {$created} data sisa cuti untuk tahun {$request->tahun}");
    }

    /**
     * Sync sisa cuti berdasarkan surat cuti yang sudah disetujui
     */
    public function syncFromSuratCuti()
    {
        // Implementation untuk sync otomatis dari surat cuti yang sudah disetujui
        // Akan diimplementasikan nanti

        return redirect()->route('admin.sisa-cuti.index')
                        ->with('info', 'Fitur sync otomatis akan segera tersedia');
    }
}
