<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SignatureController extends Controller
{
    /**
     * Show signature upload form
     */
    public function show()
    {
        $user = Auth::user();
        return view('signature.upload', compact('user'));
    }

    /**
     * Show signature setup page for new users
     */
    public function setup()
    {
        $user = Auth::user();

        // If already setup, redirect to upload page
        if ($user->signature_setup_completed && $user->tanda_tangan) {
            return redirect()->route('signature.upload');
        }

        return view('signature.setup', compact('user'));
    }

    /**
     * Complete signature setup
     */
    public function completeSetup(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:1024',
            'cap_stempel' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'gunakan_cap' => 'nullable|boolean'
        ]);

        $user = Auth::user();

        // Store signature
        $signaturePath = $request->file('signature')->store('signatures', 'public');

        $updateData = [
            'tanda_tangan' => $signaturePath,
            'signature_setup_completed' => true,
            'signature_setup_at' => now()
        ];

        // Handle cap stempel for KADIN
        if ($request->hasFile('cap_stempel') && ($user->role === 'kadin' || str_contains(strtolower($user->jabatan), 'kepala dinas'))) {
            $capPath = $request->file('cap_stempel')->store('cap-stempel', 'public');
            $updateData['cap_stempel'] = $capPath;
            $updateData['gunakan_cap'] = $request->has('gunakan_cap');
        }

        // Update user
        DB::table('users')->where('id', $user->id)->update($updateData);

        return redirect()->route('dashboard')
                        ->with('success', 'Setup tanda tangan berhasil! Sekarang Anda dapat menggunakan semua fitur sistem.');
    }

    /**
     * Upload signature
     */
    public function upload(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:1024', // Max 1MB
        ]);

        $user = Auth::user();

        // Delete old signature if exists
        if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
            Storage::disk('public')->delete($user->tanda_tangan);
        }

        // Store new signature
        $path = $request->file('signature')->store('signatures', 'public');

        // Update user
        DB::table('users')->where('id', $user->id)->update(['tanda_tangan' => $path]);

        return back()->with('success', 'Tanda tangan berhasil diupload!');
    }

    /**
     * Delete signature
     */
    public function delete()
    {
        $user = Auth::user();

        if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
            Storage::disk('public')->delete($user->tanda_tangan);
        }

        DB::table('users')->where('id', $user->id)->update(['tanda_tangan' => null]);

        return back()->with('success', 'Tanda tangan berhasil dihapus!');
    }

    /**
     * Generate signature canvas (for drawing signature)
     */
    public function canvas()
    {
        return view('signature.canvas');
    }

    /**
     * Save canvas signature
     */
    public function saveCanvas(Request $request)
    {
        $request->validate([
            'signature_data' => 'required|string',
        ]);

        $user = Auth::user();

        // Decode base64 image
        $imageData = $request->signature_data;
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageData = base64_decode($imageData);

        // Generate filename
        $filename = 'signatures/signature_' . $user->id . '_' . time() . '.png';

        // Delete old signature if exists
        if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
            Storage::disk('public')->delete($user->tanda_tangan);
        }

        // Save new signature
        Storage::disk('public')->put($filename, $imageData);

        // Update user
        DB::table('users')->where('id', $user->id)->update(['tanda_tangan' => $filename]);

        return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan!'
        ]);
    }
}
