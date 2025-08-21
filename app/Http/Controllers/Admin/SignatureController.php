<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index()
    {
        $signatures = Signature::orderBy('jabatan')->get();
        return view('admin.signatures.index', compact('signatures'));
    }

    public function create()
    {
        return view('admin.signatures.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255|unique:signatures,jabatan',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stamp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $data = $request->only(['jabatan', 'nama', 'nip', 'keterangan']);
        $data['is_active'] = $request->has('is_active');

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $signatureFile = $request->file('signature');
            $signatureName = 'signature_' . time() . '_' . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/signatures'), $signatureName);
            $data['signature_path'] = 'uploads/signatures/' . $signatureName;
        }

        // Handle stamp upload
        if ($request->hasFile('stamp')) {
            $stampFile = $request->file('stamp');
            $stampName = 'stamp_' . time() . '_' . uniqid() . '.' . $stampFile->getClientOriginalExtension();
            $stampFile->move(public_path('uploads/signatures'), $stampName);
            $data['stamp_path'] = 'uploads/signatures/' . $stampName;
        }

        Signature::create($data);

        return redirect()->route('admin.signatures.index')
                        ->with('success', 'Signature berhasil ditambahkan');
    }

    public function edit(Signature $signature)
    {
        return view('admin.signatures.edit', compact('signature'));
    }

    public function update(Request $request, Signature $signature)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255|unique:signatures,jabatan,' . $signature->id,
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stamp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $data = $request->only(['jabatan', 'nama', 'nip', 'keterangan']);
        $data['is_active'] = $request->has('is_active');

        // Handle signature upload
        if ($request->hasFile('signature')) {
            // Delete old signature
            if ($signature->signature_path && file_exists(public_path($signature->signature_path))) {
                unlink(public_path($signature->signature_path));
            }

            $signatureFile = $request->file('signature');
            $signatureName = 'signature_' . time() . '_' . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/signatures'), $signatureName);
            $data['signature_path'] = 'uploads/signatures/' . $signatureName;
        }

        // Handle stamp upload
        if ($request->hasFile('stamp')) {
            // Delete old stamp
            if ($signature->stamp_path && file_exists(public_path($signature->stamp_path))) {
                unlink(public_path($signature->stamp_path));
            }

            $stampFile = $request->file('stamp');
            $stampName = 'stamp_' . time() . '_' . uniqid() . '.' . $stampFile->getClientOriginalExtension();
            $stampFile->move(public_path('uploads/signatures'), $stampName);
            $data['stamp_path'] = 'uploads/signatures/' . $stampName;
        }

        $signature->update($data);

        return redirect()->route('admin.signatures.index')
                        ->with('success', 'Signature berhasil diupdate');
    }

    public function destroy(Signature $signature)
    {
        // Delete files
        if ($signature->signature_path && file_exists(public_path($signature->signature_path))) {
            unlink(public_path($signature->signature_path));
        }
        if ($signature->stamp_path && file_exists(public_path($signature->stamp_path))) {
            unlink(public_path($signature->stamp_path));
        }

        $signature->delete();

        return redirect()->route('admin.signatures.index')
                        ->with('success', 'Signature berhasil dihapus');
    }
}
