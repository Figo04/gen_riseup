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
        Schema::create('kuesioner_soal', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['pengetahuan', 'sikap']);
            $table->text('pertanyaan');
            $table->string('jawaban_benar')->nullable();
            $table->boolean('reverse_scored')->default(false);
            $table->unsignedTinyInteger('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_soal');
    }
};
