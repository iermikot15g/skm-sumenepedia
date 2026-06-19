<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_jawaban_unsur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unsur_survei_id')->constrained('unsur_survei')->onDelete('cascade');
            $table->tinyInteger('nilai'); // 1-4
            $table->string('label'); // Misal: "Tidak sesuai", "Sesuai", dll.
            $table->timestamps();

            // Unique: satu unsur tidak boleh punya dua opsi dengan nilai yang sama
            $table->unique(['unsur_survei_id', 'nilai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_jawaban_unsur');
    }
};