<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Puskesmas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PuskesmasController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $puskesmas = Puskesmas::orderBy('nama')->get();

        return view('admin.puskesmas.index', compact('puskesmas'));
    }

    public function edit(Puskesmas $puskesma)
    {
        return view('admin.puskesmas.edit', compact('puskesma'));
    }

    public function update(Request $request, Puskesmas $puskesma)
    {
        $data = $request->validate([
            'kepala_puskesmas' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:20',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'cap_stempel' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        if ($request->hasFile('tanda_tangan')) {
            if ($puskesma->tanda_tangan && Storage::disk('public')->exists($puskesma->tanda_tangan)) {
                Storage::disk('public')->delete($puskesma->tanda_tangan);
            }
            $data['tanda_tangan'] = $request->file('tanda_tangan')->store('puskesmas/signatures', 'public');
        }

        if ($request->hasFile('cap_stempel')) {
            if ($puskesma->cap_stempel && Storage::disk('public')->exists($puskesma->cap_stempel)) {
                Storage::disk('public')->delete($puskesma->cap_stempel);
            }
            $data['cap_stempel'] = $request->file('cap_stempel')->store('puskesmas/cap', 'public');
        }

        $puskesma->update($data);

        return redirect()->route('admin.puskesmas.index')->with('success', 'Data puskesmas diperbarui');
    }
}
