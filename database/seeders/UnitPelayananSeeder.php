<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitPelayananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unit_pelayanan')->insert([
            [
                'nama' => 'Dinas Pendidikan',
                'kode' => 'DINDIK',
                'alamat' => 'Jl. Trunojoyo No. 1, Sumenep',
                'telepon' => '0328-123456',
                'email' => 'dindik@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dinas Kesehatan',
                'kode' => 'DINKES',
                'alamat' => 'Jl. Dr. Sutomo No. 5, Sumenep',
                'telepon' => '0328-234567',
                'email' => 'dinkes@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'kode' => 'DUKCAPIL',
                'alamat' => 'Jl. KH. Wahid Hasyim No. 10, Sumenep',
                'telepon' => '0328-345678',
                'email' => 'dukcapil@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'kode' => 'PUPR',
                'alamat' => 'Jl. Diponegoro No. 8, Sumenep',
                'telepon' => '0328-456789',
                'email' => 'pupr@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'RSUD Dr. Moh. Anwar',
                'kode' => 'RSUD',
                'alamat' => 'Jl. Kesehatan No. 2, Sumenep',
                'telepon' => '0328-567890',
                'email' => 'rsud@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kecamatan Kota Sumenep',
                'kode' => 'KEC-KOTA',
                'alamat' => 'Jl. Raya Kecamatan No. 1, Sumenep',
                'telepon' => '0328-678901',
                'email' => 'kec-kota@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kecamatan Kalianget',
                'kode' => 'KEC-KALIANGET',
                'alamat' => 'Jl. Kalianget No. 15, Sumenep',
                'telepon' => '0328-789012',
                'email' => 'kec-kalianget@sumenep.go.id',
                'logo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}