<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survei', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_pelayanan_id')->constrained('unit_pelayanan')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode_survei')->onDelete('cascade');
            $table->string('nik', 16);
            $table->string('nama')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->tinyInteger('usia')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('pendidikan', ['SD/MI', 'SMP/MTs', 'SMA/MA/SMK', 'D1/D2', 'D3', 'D4/S1', 'S2', 'S3'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->dateTime('tanggal_survei');
            $table->timestamps();

            // Unique constraint: 1 NIK per periode per unit
            $table->unique(['nik', 'unit_pelayanan_id', 'periode_id'], 'unique_survei');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei');
    }
};