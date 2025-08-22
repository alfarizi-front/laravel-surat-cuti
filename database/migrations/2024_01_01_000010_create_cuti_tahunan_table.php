<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiTahunanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuti_tahunan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->integer('jatah_cuti')->default(12); // Jatah cuti per tahun
            $table->integer('cuti_digunakan')->default(0); // Cuti yang sudah digunakan
            $table->integer('cuti_pending')->default(0); // Cuti yang sedang pending
            $table->integer('sisa_cuti')->default(12); // Sisa cuti yang tersedia
            $table->timestamps();

            // Unique constraint untuk user dan tahun
            $table->unique(['user_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti_tahunan');
    }
}
