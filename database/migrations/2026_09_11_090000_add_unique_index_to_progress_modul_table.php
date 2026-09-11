<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_modul', function (Blueprint $table) {
            $table->unique(['user_id', 'sub_bagian_id']);
        });
    }

    public function down(): void
    {
        Schema::table('progress_modul', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'sub_bagian_id']);
        });
    }
};
