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
        Schema::table('sub_bagian', function (Blueprint $table) {
            $table->renameColumn('konten', 'konten_view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_bagian', function (Blueprint $table) {
            $table->renameColumn('konten_view', 'konten');
        });
    }
};
