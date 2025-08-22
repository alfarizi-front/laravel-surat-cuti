<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicateDisposisiAndAddUniqueConstraint extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove duplicate disposisi entries
        $this->removeDuplicateDisposisi();

        // Add unique constraint to prevent future duplicates
        Schema::table('disposisi_cuti', function (Blueprint $table) {
            $table->unique(['surat_cuti_id', 'user_id', 'jabatan'], 'unique_disposisi');
        });

        // Add missing tipe_disposisi column if it doesn't exist
        if (! Schema::hasColumn('disposisi_cuti', 'tipe_disposisi')) {
            Schema::table('disposisi_cuti', function (Blueprint $table) {
                $table->enum('tipe_disposisi', ['paraf', 'ttd'])->default('paraf')->after('jabatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisi_cuti', function (Blueprint $table) {
            $table->dropUnique('unique_disposisi');
            if (Schema::hasColumn('disposisi_cuti', 'tipe_disposisi')) {
                $table->dropColumn('tipe_disposisi');
            }
        });
    }

    /**
     * Remove duplicate disposisi entries, keeping only the first occurrence
     */
    private function removeDuplicateDisposisi(): void
    {
        // Get all duplicates
        $duplicates = DB::table('disposisi_cuti')
            ->select('surat_cuti_id', 'user_id', 'jabatan', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as count'))
            ->groupBy('surat_cuti_id', 'user_id', 'jabatan')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            // Delete all duplicates except the first one (keep_id)
            DB::table('disposisi_cuti')
                ->where('surat_cuti_id', $duplicate->surat_cuti_id)
                ->where('user_id', $duplicate->user_id)
                ->where('jabatan', $duplicate->jabatan)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();

            echo 'Removed '.($duplicate->count - 1)." duplicate(s) for surat_cuti_id: {$duplicate->surat_cuti_id}, user_id: {$duplicate->user_id}, jabatan: {$duplicate->jabatan}\n";
        }

        if ($duplicates->count() > 0) {
            echo 'Successfully removed duplicates for '.$duplicates->count()." disposisi groups.\n";
        } else {
            echo "No duplicate disposisi entries found.\n";
        }
    }
}
