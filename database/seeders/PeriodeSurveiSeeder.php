<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSurveiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('periode_survei')->insert([
            [
                'nama' => 'Triwulan I 2024',
                'tahun' => 2024,
                'triwulan' => 1,
                'tanggal_mulai' => '2024-01-01',
                'tanggal_selesai' => '2024-03-31',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Triwulan II 2024',
                'tahun' => 2024,
                'triwulan' => 2,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-06-30',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Triwulan III 2024',
                'tahun' => 2024,
                'triwulan' => 3,
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2024-09-30',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Triwulan IV 2024',
                'tahun' => 2024,
                'triwulan' => 4,
                'tanggal_mulai' => '2024-10-01',
                'tanggal_selesai' => '2024-12-31',
                'is_active' => true, // Periode aktif saat ini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Triwulan I 2025',
                'tahun' => 2025,
                'triwulan' => 1,
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-03-31',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}