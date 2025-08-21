<?php

namespace App\Http\Controllers;

use App\Models\DisposisiCuti;
use App\Models\SuratCuti;
use App\Models\CutiTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * DisposisiController
 * 
 * Handles the disposisi (approval) workflow for surat cuti.
 * Features:
 * - Flexible processing (no sequential order required)
 * - Conditional logic for Sekretariat (Umpeg OR Perencanaan Keu)
 * - Complete history tracking
 */
class DisposisiController extends Controller
{
    /**
     * Show disposisi form for processing
     */
    public function show(DisposisiCuti $disposisi)
    {
        $this->authorizeDisposisi($disposisi);

        if ($disposisi->status === 'sudah') {
            return back()->with('info', 'Disposisi sudah diproses sebelumnya.');
        }

        $disposisi->load(['suratCuti.pengaju', 'suratCuti.jenisCuti']);

        return view('disposisi.show', compact('disposisi'));
    }

    /**
     * Process disposisi (approve/reject)
     */
    public function process(Request $request, DisposisiCuti $disposisi)
    {
        try {
            \Log::info('Disposisi process started', [
                'disposisi_id' => $disposisi->id,
                'user_id' => \Auth::id(),
                'request_data' => $request->all()
            ]);

            $this->authorizeDisposisi($disposisi);

            if ($disposisi->status === 'sudah') {
                \Log::warning('Disposisi already processed', ['disposisi_id' => $disposisi->id]);
                return back()->with('error', 'Disposisi sudah diproses sebelumnya.');
            }

            $validatedData = $request->validate([
                'action' => 'required|in:approve,reject',
                'catatan' => 'nullable|string|max:500'
            ]);

            \Log::info('Validation passed', ['validated_data' => $validatedData]);

            $this->updateDisposisi($disposisi, $validatedData['catatan']);

            \Log::info('Disposisi updated', [
                'disposisi_id' => $disposisi->id,
                'new_status' => $disposisi->fresh()->status
            ]);

            $suratCuti = $disposisi->suratCuti;

            if ($validatedData['action'] === 'reject') {
                \Log::info('Processing rejection', ['surat_cuti_id' => $suratCuti->id]);
                return $this->handleRejection($suratCuti);
            }

            \Log::info('Processing approval', ['surat_cuti_id' => $suratCuti->id]);
            return $this->handleApproval($disposisi, $suratCuti);

        } catch (\Exception $e) {
            \Log::error('Disposisi process error', [
                'disposisi_id' => $disposisi->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memproses disposisi: ' . $e->getMessage());
        }
    }

    /**
     * Show pending disposisi for current user
     */
    public function pending()
    {
        $user = Auth::user();
        
        $pendingDisposisi = DisposisiCuti::where('user_id', $user->id)
                                        ->where('status', 'pending')
                                        ->with(['suratCuti.pengaju', 'suratCuti.jenisCuti'])
                                        ->latest()
                                        ->paginate(10);

        return view('disposisi.pending', compact('pendingDisposisi'));
    }

    /**
     * Show disposisi history for current user
     */
    public function history()
    {
        $user = Auth::user();

        // Disposisi yang pernah diproses oleh user
        $myDisposisi = DisposisiCuti::where('user_id', $user->id)
                                    ->with(['suratCuti.pengaju', 'suratCuti.jenisCuti'])
                                    ->orderByDesc('created_at')
                                    ->paginate(10, ['*'], 'disposisi_page');

        // Pengajuan surat cuti milik user
        $mySubmissions = SuratCuti::where('pengaju_id', $user->id)
                                  ->with(['jenisCuti', 'disposisiCuti.user'])
                                  ->orderByDesc('created_at')
                                  ->paginate(10, ['*'], 'pengajuan_page');

        return view('disposisi.history', compact('myDisposisi', 'mySubmissions'));
    }

    /**
     * Authorize user access to disposisi
     */
    private function authorizeDisposisi(DisposisiCuti $disposisi): void
    {
        if ($disposisi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk disposisi ini.');
        }
    }

    /**
     * Update disposisi status and timestamp
     */
    private function updateDisposisi(DisposisiCuti $disposisi, ?string $catatan): void
    {
        \Log::info('Updating disposisi', [
            'disposisi_id' => $disposisi->id,
            'current_status' => $disposisi->status,
            'catatan' => $catatan
        ]);

        $updateData = [
            'status' => 'sudah',
            'tanggal' => now(),
            'catatan' => $catatan
        ];

        $result = $disposisi->update($updateData);

        if (!$result) {
            \Log::error('Failed to update disposisi', [
                'disposisi_id' => $disposisi->id,
                'update_data' => $updateData
            ]);
            throw new \Exception('Gagal mengupdate disposisi');
        }

        \Log::info('Disposisi updated successfully', [
            'disposisi_id' => $disposisi->id,
            'new_status' => $disposisi->fresh()->status
        ]);
    }

    /**
     * Handle surat cuti rejection
     */
    private function handleRejection(SuratCuti $suratCuti)
    {
        $suratCuti->update(['status' => 'ditolak']);

        $user = $suratCuti->pengaju;
        $cutiTahunan = $user->getCutiTahunan();
        $cutiTahunan->rejectCuti($suratCuti->jumlah_hari);

        return redirect()->route('dashboard')
                       ->with('success', 'Surat cuti telah ditolak. Cuti pending telah dikembalikan.');
    }

    /**
     * Handle surat cuti approval with flexible logic
     */
    private function handleApproval(DisposisiCuti $disposisi, SuratCuti $suratCuti)
    {
        // Apply conditional logic for Sekretariat
        $this->applyConditionalLogic($disposisi, $suratCuti);

        // Check if surat is ready for completion using flexible logic
        if ($this->isReadyForCompletion($suratCuti)) {
            return $this->completeSuratCuti($suratCuti);
        }

        return redirect()->route('dashboard')
                       ->with('success', 'Disposisi berhasil diproses. Surat akan disetujui setelah semua persyaratan tanda tangan terpenuhi.');
    }

    /**
     * Check if surat cuti is ready for completion using flexible logic
     */
    private function isReadyForCompletion(SuratCuti $suratCuti): bool
    {
        $disposisiList = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)->get();

        // Get all required signatures (TTD) - these are mandatory
        $requiredSignatures = $disposisiList->where('tipe_disposisi', 'ttd');
        $approvedSignatures = $requiredSignatures->where('status', 'sudah');

        // Check if all required signatures (TTD) are completed
        $allSignaturesComplete = $requiredSignatures->count() === $approvedSignatures->count();

        // For flexible approval: if all TTD are done, check if at least 80% of paraf are done
        if ($allSignaturesComplete) {
            $requiredParaf = $disposisiList->where('tipe_disposisi', 'paraf');
            $approvedParaf = $requiredParaf->where('status', 'sudah');

            if ($requiredParaf->count() === 0) {
                // No paraf required, only signatures
                return true;
            }

            $parafCompletionRate = $approvedParaf->count() / $requiredParaf->count();

            // Flexible: Allow completion if at least 80% of paraf are done and all TTD are done
            return $parafCompletionRate >= 0.8;
        }

        // Fallback: Traditional check - all disposisi must be completed
        $pendingCount = $disposisiList->where('status', 'pending')->count();
        return $pendingCount === 0;
    }

    /**
     * Apply conditional logic for Sekretariat workflow
     * If Umpeg OR Perencanaan Keu approves, auto-approve the other
     */
    private function applyConditionalLogic(DisposisiCuti $disposisi, SuratCuti $suratCuti): void
    {
        if ($suratCuti->pengaju->unit_kerja !== 'Sekretariat') {
            return;
        }

        $conditionalRoles = ['Kasubag Umpeg', 'Kasubag Perencanaan Keu'];
        
        if (!in_array($disposisi->jabatan, $conditionalRoles)) {
            return;
        }

        $otherRole = $disposisi->jabatan === 'Kasubag Umpeg' 
                   ? 'Kasubag Perencanaan Keu' 
                   : 'Kasubag Umpeg';

        $updated = DisposisiCuti::where('surat_cuti_id', $suratCuti->id)
                               ->where('jabatan', $otherRole)
                               ->where('status', 'pending')
                               ->update([
                                   'status' => 'sudah',
                                   'tanggal' => now(),
                                   'catatan' => "Otomatis disetujui karena {$disposisi->jabatan} telah menyetujui"
                               ]);

        if ($updated > 0) {
            Log::info("Conditional approval applied: {$otherRole} auto-approved for surat cuti {$suratCuti->id}");
        }
    }

    /**
     * Complete surat cuti approval process
     */
    private function completeSuratCuti(SuratCuti $suratCuti)
    {
        $suratCuti->update(['status' => 'disetujui']);

        $user = $suratCuti->pengaju;
        $cutiTahunan = $user->getCutiTahunan();
        $cutiTahunan->approveCuti($suratCuti->jumlah_hari);

        return redirect()->route('dashboard')
                       ->with('success', 'Surat cuti telah disetujui dan proses disposisi selesai. Cuti telah dicatat dalam sistem.');
    }
}