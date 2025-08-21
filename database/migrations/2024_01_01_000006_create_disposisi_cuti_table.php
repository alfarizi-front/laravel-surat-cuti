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
        Schema::create('disposisi_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_cuti_id')->constrained('surat_cuti')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jabatan');
            $table->enum('status', ['pending', 'sudah'])->default('pending');
            $table->timestamp('tanggal')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi_cuti');
    }
};
