<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaju_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti')->onDelete('cascade');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->text('alasan');
            $table->text('alamat_selama_cuti')->nullable();
            $table->string('kontak_darurat')->nullable();
            $table->string('lampiran')->nullable();
            $table->enum('status', ['draft', 'proses', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamp('tanggal_ajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_cuti');
    }
};
