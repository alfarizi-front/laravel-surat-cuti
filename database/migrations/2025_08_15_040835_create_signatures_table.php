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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan')->unique(); // Position/role
            $table->string('nama'); // Official name
            $table->string('nip')->nullable(); // Employee ID
            $table->text('signature_path')->nullable(); // Path to signature image
            $table->text('stamp_path')->nullable(); // Path to stamp/cap image
            $table->boolean('is_active')->default(true); // Active status
            $table->text('keterangan')->nullable(); // Additional notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
