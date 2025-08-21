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
        Schema::create('atasan_setup', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pegawai'); // ASN, PPPK
            $table->string('level_atasan'); // sekdin, kadin, kepala, kasubag
            $table->string('nama_jabatan');
            $table->boolean('perlu_tanda_tangan')->default(true);
            $table->boolean('perlu_cap_stempel')->default(false);
            $table->integer('urutan_disposisi')->default(1);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Add column to users for signature setup status
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('signature_setup_completed')->default(false)->after('gunakan_cap');
            $table->timestamp('signature_setup_at')->nullable()->after('signature_setup_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atasan_setup');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['signature_setup_completed', 'signature_setup_at']);
        });
    }
};
