<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSisaCutiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sisa_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('tahun'); // 2023, 2024, 2025, etc
            $table->integer('sisa_awal')->default(12); // Sisa cuti awal tahun
            $table->integer('cuti_diambil')->default(0); // Cuti yang sudah diambil
            $table->integer('sisa_akhir')->default(12); // Sisa cuti akhir (sisa_awal - cuti_diambil)
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();

            // Unique constraint: satu user hanya punya satu record per tahun
            $table->unique(['user_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisa_cuti');
    }
}
