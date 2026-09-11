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
        Schema::create('sub_bagian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('modul')->onDelete('cascade');
            $table->string('judul');
            $table->longText('konten');
            $table->unsignedTinyInteger('urutan');
            $table->string('video_youtube_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_bagian');
    }
};
