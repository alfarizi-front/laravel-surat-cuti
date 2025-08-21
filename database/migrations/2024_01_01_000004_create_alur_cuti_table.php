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
        Schema::create('alur_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('unit_kerja');
            $table->integer('step_ke');
            $table->string('jabatan');
            $table->enum('tipe_disposisi', ['paraf', 'ttd']);
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alur_cuti');
    }
};
