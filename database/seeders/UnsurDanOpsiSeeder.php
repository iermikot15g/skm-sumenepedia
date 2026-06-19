<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnsurDanOpsiSeeder extends Seeder
{
    public function run(): void
    {
        // Data 9 unsur survei
        $unsur = [
            [
                'nama' => 'Kesesuaian Persyaratan Pelayanan',
                'deskripsi' => 'Kesesuaian persyaratan yang diminta dengan layanan yang diberikan',
            ],
            [
                'nama' => 'Kemudahan Prosedur',
                'deskripsi' => 'Kemudahan dalam mengikuti prosedur pelayanan',
            ],
            [
                'nama' => 'Kecepatan Pelayanan',
                'deskripsi' => 'Kecepatan waktu penyelesaian pelayanan',
            ],
            [
                'nama' => 'Kewajaran Biaya',
                'deskripsi' => 'Kewajaran biaya atau tarif yang dikenakan',
            ],
            [
                'nama' => 'Kesesuaian Hasil Pelayanan',
                'deskripsi' => 'Kesesuaian hasil pelayanan dengan yang dijanjikan',
            ],
            [
                'nama' => 'Kompetensi Petugas',
                'deskripsi' => 'Kompetensi dan kemampuan petugas dalam memberikan pelayanan',
            ],
            [
                'nama' => 'Kesopanan & Keramahan',
                'deskripsi' => 'Sikap sopan dan ramah petugas dalam melayani',
            ],
            [
                'nama' => 'Sarana & Prasarana',
                'deskripsi' => 'Kelengkapan dan kenyamanan sarana dan prasarana pelayanan',
            ],
            [
                'nama' => 'Penanganan Pengaduan',
                'deskripsi' => 'Ketersediaan dan pengelolaan sarana pengaduan, saran, dan masukan',
            ],
        ];

        // Insert unsur survei dan simpan ID-nya
        $unsurIds = [];
        foreach ($unsur as $index => $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            $id = DB::table('unsur_survei')->insertGetId($item);
            $unsurIds[$index + 1] = $id; // Simpan mapping id unsur (1-9) ke id di database
        }

        // Data opsi jawaban untuk setiap unsur
        $opsi = [
            // Unsur 1: Kesesuaian Persyaratan Pelayanan
            ['unsur_survei_id' => $unsurIds[1], 'nilai' => 1, 'label' => 'Tidak sesuai'],
            ['unsur_survei_id' => $unsurIds[1], 'nilai' => 2, 'label' => 'Kurang sesuai'],
            ['unsur_survei_id' => $unsurIds[1], 'nilai' => 3, 'label' => 'Sesuai'],
            ['unsur_survei_id' => $unsurIds[1], 'nilai' => 4, 'label' => 'Sangat sesuai'],

            // Unsur 2: Kemudahan Prosedur
            ['unsur_survei_id' => $unsurIds[2], 'nilai' => 1, 'label' => 'Tidak mudah'],
            ['unsur_survei_id' => $unsurIds[2], 'nilai' => 2, 'label' => 'Kurang mudah'],
            ['unsur_survei_id' => $unsurIds[2], 'nilai' => 3, 'label' => 'Mudah'],
            ['unsur_survei_id' => $unsurIds[2], 'nilai' => 4, 'label' => 'Sangat mudah'],

            // Unsur 3: Kecepatan Pelayanan
            ['unsur_survei_id' => $unsurIds[3], 'nilai' => 1, 'label' => 'Tidak cepat'],
            ['unsur_survei_id' => $unsurIds[3], 'nilai' => 2, 'label' => 'Kurang cepat'],
            ['unsur_survei_id' => $unsurIds[3], 'nilai' => 3, 'label' => 'Cepat'],
            ['unsur_survei_id' => $unsurIds[3], 'nilai' => 4, 'label' => 'Sangat cepat'],

            // Unsur 4: Kewajaran Biaya
            ['unsur_survei_id' => $unsurIds[4], 'nilai' => 1, 'label' => 'Sangat mahal'],
            ['unsur_survei_id' => $unsurIds[4], 'nilai' => 2, 'label' => 'Cukup mahal'],
            ['unsur_survei_id' => $unsurIds[4], 'nilai' => 3, 'label' => 'Murah'],
            ['unsur_survei_id' => $unsurIds[4], 'nilai' => 4, 'label' => 'Gratis'],

            // Unsur 5: Kesesuaian Hasil Pelayanan
            ['unsur_survei_id' => $unsurIds[5], 'nilai' => 1, 'label' => 'Tidak sesuai'],
            ['unsur_survei_id' => $unsurIds[5], 'nilai' => 2, 'label' => 'Kurang sesuai'],
            ['unsur_survei_id' => $unsurIds[5], 'nilai' => 3, 'label' => 'Sesuai'],
            ['unsur_survei_id' => $unsurIds[5], 'nilai' => 4, 'label' => 'Sangat sesuai'],

            // Unsur 6: Kompetensi Petugas
            ['unsur_survei_id' => $unsurIds[6], 'nilai' => 1, 'label' => 'Tidak kompeten'],
            ['unsur_survei_id' => $unsurIds[6], 'nilai' => 2, 'label' => 'Kurang kompeten'],
            ['unsur_survei_id' => $unsurIds[6], 'nilai' => 3, 'label' => 'Kompeten'],
            ['unsur_survei_id' => $unsurIds[6], 'nilai' => 4, 'label' => 'Sangat kompeten'],

            // Unsur 7: Kesopanan & Keramahan
            ['unsur_survei_id' => $unsurIds[7], 'nilai' => 1, 'label' => 'Tidak sopan'],
            ['unsur_survei_id' => $unsurIds[7], 'nilai' => 2, 'label' => 'Kurang sopan'],
            ['unsur_survei_id' => $unsurIds[7], 'nilai' => 3, 'label' => 'Sopan'],
            ['unsur_survei_id' => $unsurIds[7], 'nilai' => 4, 'label' => 'Sangat sopan'],

            // Unsur 8: Sarana & Prasarana
            ['unsur_survei_id' => $unsurIds[8], 'nilai' => 1, 'label' => 'Buruk'],
            ['unsur_survei_id' => $unsurIds[8], 'nilai' => 2, 'label' => 'Cukup'],
            ['unsur_survei_id' => $unsurIds[8], 'nilai' => 3, 'label' => 'Baik'],
            ['unsur_survei_id' => $unsurIds[8], 'nilai' => 4, 'label' => 'Sangat baik'],

            // Unsur 9: Penanganan Pengaduan
            ['unsur_survei_id' => $unsurIds[9], 'nilai' => 1, 'label' => 'Tidak tersedia'],
            ['unsur_survei_id' => $unsurIds[9], 'nilai' => 2, 'label' => 'Tersedia tidak berfungsi'],
            ['unsur_survei_id' => $unsurIds[9], 'nilai' => 3, 'label' => 'Kurang maksimal'],
            ['unsur_survei_id' => $unsurIds[9], 'nilai' => 4, 'label' => 'Dikelola baik'],
        ];

        // Insert opsi jawaban
        foreach ($opsi as $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            DB::table('opsi_jawaban_unsur')->insert($item);
        }
    }
}