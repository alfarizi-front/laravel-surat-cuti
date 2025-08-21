<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CapStempelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,kadin']);
    }

    /**
     * Display cap stempel management page
     */
    public function index()
    {
        // Get KADIN and Kepala Puskesmas users who can have cap stempel
        $users = User::where(function($q) {
                        $q->where('role', 'kadin')
                          ->orWhere('jabatan', 'like', '%Kepala Dinas%');
                    })
                    ->orWhere(function($q) {
                        $q->where('role', 'kapus')
                          ->orWhere('jabatan', 'like', '%Kepala Puskesmas%');
                    })
                    ->orderBy('nama')
                    ->get();

        $currentUser = Auth::user();

        return view('admin.cap-stempel.index', compact('users', 'currentUser'));
    }

    /**
     * Show upload form for specific user
     */
    public function create(Request $request)
    {
        $userId = $request->get('user_id');
        $user = null;
        
        if ($userId) {
            $user = User::findOrFail($userId);
        } else {
            $user = Auth::user();
        }

        return view('admin.cap-stempel.create', compact('user'));
    }

    /**
     * Store cap stempel
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'cap_stempel' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'gunakan_cap' => 'nullable|boolean'
        ]);

        $user = User::findOrFail($request->user_id);

        $updateData = [];

        // Handle tanda tangan upload
        if ($request->hasFile('tanda_tangan')) {
            // Delete old signature if exists
            if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
                Storage::disk('public')->delete($user->tanda_tangan);
            }
            
            $signaturePath = $request->file('tanda_tangan')->store('signatures', 'public');
            $updateData['tanda_tangan'] = $signaturePath;
        }

        // Handle cap stempel upload
        if ($request->hasFile('cap_stempel')) {
            // Delete old cap if exists
            if ($user->cap_stempel && Storage::disk('public')->exists($user->cap_stempel)) {
                Storage::disk('public')->delete($user->cap_stempel);
            }
            
            $capPath = $request->file('cap_stempel')->store('cap-stempel', 'public');
            $updateData['cap_stempel'] = $capPath;
        }

        // Update gunakan_cap setting
        $updateData['gunakan_cap'] = $request->has('gunakan_cap');

        // Update user
        if (!empty($updateData)) {
            DB::table('users')->where('id', $user->id)->update($updateData);
        }

        $message = 'Data berhasil diupdate!';
        if ($request->hasFile('tanda_tangan')) {
            $message = 'Tanda tangan berhasil diupload!';
        }
        if ($request->hasFile('cap_stempel')) {
            $message .= ' Cap/stempel berhasil diupload!';
        }

        return redirect()->route('admin.cap-stempel.index')->with('success', $message);
    }

    /**
     * Delete cap stempel or signature
     */
    public function destroy(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $type = $request->get('type', 'signature'); // 'signature' or 'cap'

        if ($type === 'cap') {
            // Delete cap stempel
            if ($user->cap_stempel && Storage::disk('public')->exists($user->cap_stempel)) {
                Storage::disk('public')->delete($user->cap_stempel);
            }
            
            DB::table('users')->where('id', $user->id)->update([
                'cap_stempel' => null,
                'gunakan_cap' => false
            ]);
            
            $message = 'Cap/stempel berhasil dihapus!';
        } else {
            // Delete signature
            if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
                Storage::disk('public')->delete($user->tanda_tangan);
            }
            
            DB::table('users')->where('id', $user->id)->update(['tanda_tangan' => null]);
            
            $message = 'Tanda tangan berhasil dihapus!';
        }

        return redirect()->route('admin.cap-stempel.index')->with('success', $message);
    }

    /**
     * Toggle cap stempel usage
     */
    public function toggle(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $newStatus = !$user->gunakan_cap;
        
        DB::table('users')->where('id', $user->id)->update(['gunakan_cap' => $newStatus]);
        
        $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.cap-stempel.index')
                        ->with('success', "Cap/stempel untuk {$user->nama} berhasil {$status}!");
    }

    /**
     * Preview cap stempel
     */
    public function preview($userId)
    {
        $user = User::findOrFail($userId);
        
        return view('admin.cap-stempel.preview', compact('user'));
    }

    /**
     * Bulk upload cap stempel
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'cap_stempel' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $capPath = $request->file('cap_stempel')->store('cap-stempel', 'public');
        $updatedCount = 0;

        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);
            if ($user) {
                // Delete old cap if exists
                if ($user->cap_stempel && Storage::disk('public')->exists($user->cap_stempel)) {
                    Storage::disk('public')->delete($user->cap_stempel);
                }
                
                DB::table('users')->where('id', $userId)->update([
                    'cap_stempel' => $capPath,
                    'gunakan_cap' => true
                ]);
                
                $updatedCount++;
            }
        }

        return redirect()->route('admin.cap-stempel.index')
                        ->with('success', "Cap/stempel berhasil diupload untuk {$updatedCount} user!");
    }
}
