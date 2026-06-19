<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_survei', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_id')->constrained('survei')->onDelete('cascade');
            $table->foreignId('opsi_jawaban_unsur_id')->constrained('opsi_jawaban_unsur')->onDelete('cascade');
            $table->timestamps();

            // Unique: 1 survei tidak boleh punya 2 jawaban untuk unsur yang sama
            $table->unique(['survei_id', 'opsi_jawaban_unsur_id'], 'unique_jawaban_survei');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_survei');
    }
};