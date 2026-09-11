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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('usia')->after('email');
            $table->enum('jenis_kelamin', ['L', 'P'])->after('usia');
            $table->string('sekolah')->nullable()->after('jenis_kelamin');
            $table->string('kelas')->nullable()->after('sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['usia', 'jenis_kelamin', 'sekolah', 'kelas']);
        });
    }
};
