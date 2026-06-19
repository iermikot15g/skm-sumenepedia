<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PeriodeSurveiSeeder::class,
            UnitPelayananSeeder::class,
            LayananSeeder::class,
            UnsurDanOpsiSeeder::class,
            UserSeeder::class,
        ]);
    }
}