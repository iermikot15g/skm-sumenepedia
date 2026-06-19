<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        DB::table('users')->insert([
            'name' => 'Super Admin SKM',
            'email' => 'admin@skm.sumenep.go.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'unit_pelayanan_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Admin Unit - Dinas Pendidikan
        $dindik = DB::table('unit_pelayanan')->where('kode', 'DINDIK')->first();
        if ($dindik) {
            DB::table('users')->insert([
                'name' => 'Admin Dinas Pendidikan',
                'email' => 'admin@dindik.sumenep.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_unit',
                'unit_pelayanan_id' => $dindik->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Admin Unit - Dinas Kesehatan
        $dinkes = DB::table('unit_pelayanan')->where('kode', 'DINKES')->first();
        if ($dinkes) {
            DB::table('users')->insert([
                'name' => 'Admin Dinas Kesehatan',
                'email' => 'admin@dinkes.sumenep.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_unit',
                'unit_pelayanan_id' => $dinkes->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Admin Unit - Dukcapil
        $dukcapil = DB::table('unit_pelayanan')->where('kode', 'DUKCAPIL')->first();
        if ($dukcapil) {
            DB::table('users')->insert([
                'name' => 'Admin Dukcapil',
                'email' => 'admin@dukcapil.sumenep.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_unit',
                'unit_pelayanan_id' => $dukcapil->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pimpinan Unit - Dinas Pendidikan
        if ($dindik) {
            DB::table('users')->insert([
                'name' => 'Kepala Dinas Pendidikan',
                'email' => 'kepala@dindik.sumenep.go.id',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan_unit',
                'unit_pelayanan_id' => $dindik->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pimpinan Unit - Dinas Kesehatan
        if ($dinkes) {
            DB::table('users')->insert([
                'name' => 'Kepala Dinas Kesehatan',
                'email' => 'kepala@dinkes.sumenep.go.id',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan_unit',
                'unit_pelayanan_id' => $dinkes->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pimpinan Utama - Sekretaris Daerah
        DB::table('users')->insert([
            'name' => 'Sekretaris Daerah',
            'email' => 'sekda@sumenep.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan_utama',
            'unit_pelayanan_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}