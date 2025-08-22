<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuskesmasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('puskesmas', function (Blueprint $table) {
            $table->id();
 
            $table->string('nama');
 
 
            $table->string('nama');
 
 
            $table->string('nama');
 
            $table->string('nama_puskesmas');
 
 
 
            $table->string('kepala_puskesmas')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->string('cap_stempel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puskesmas');
    }
}
