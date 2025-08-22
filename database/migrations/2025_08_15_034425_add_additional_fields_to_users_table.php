<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nip');
            $table->string('telepon', 20)->nullable()->after('alamat');
            $table->string('masa_kerja', 50)->nullable()->after('telepon');
            $table->string('golongan', 50)->nullable()->after('masa_kerja');
            $table->string('pangkat', 100)->nullable()->after('golongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'telepon', 'masa_kerja', 'golongan', 'pangkat']);
        });
    }
}
