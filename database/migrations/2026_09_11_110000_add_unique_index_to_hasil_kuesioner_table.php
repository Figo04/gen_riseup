<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_kuesioner', function (Blueprint $table) {
            $table->unique(['user_id', 'tipe_sesi']);
        });
    }

    public function down(): void
    {
        Schema::table('hasil_kuesioner', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'tipe_sesi']);
        });
    }
};
