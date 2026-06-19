<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // Dapatkan ID unit pelayanan
        $dindik = DB::table('unit_pelayanan')->where('kode', 'DINDIK')->first();
        $dinkes = DB::table('unit_pelayanan')->where('kode', 'DINKES')->first();
        $dukcapil = DB::table('unit_pelayanan')->where('kode', 'DUKCAPIL')->first();
        $pupr = DB::table('unit_pelayanan')->where('kode', 'PUPR')->first();
        $rsud = DB::table('unit_pelayanan')->where('kode', 'RSUD')->first();
        $kecKota = DB::table('unit_pelayanan')->where('kode', 'KEC-KOTA')->first();
        $kecKalianget = DB::table('unit_pelayanan')->where('kode', 'KEC-KALIANGET')->first();

        $layanan = [];

        // Dinas Pendidikan
        $layanan[] = ['unit_pelayanan_id' => $dindik->id, 'nama' => 'Penerbitan Ijazah', 'deskripsi' => 'Pelayanan penerbitan ijazah untuk jenjang SD, SMP, SMA/SMK', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dindik->id, 'nama' => 'Perizinan Sekolah', 'deskripsi' => 'Perizinan pendirian dan operasional sekolah', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dindik->id, 'nama' => 'Beasiswa', 'deskripsi' => 'Pendaftaran dan penyaluran beasiswa', 'is_active' => true];

        // Dinas Kesehatan
        $layanan[] = ['unit_pelayanan_id' => $dinkes->id, 'nama' => 'Penerbitan Sertifikat Kesehatan', 'deskripsi' => 'Penerbitan sertifikat kesehatan untuk berbagai keperluan', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dinkes->id, 'nama' => 'Imunisasi', 'deskripsi' => 'Program imunisasi dasar dan lanjutan', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dinkes->id, 'nama' => 'Konsultasi Kesehatan', 'deskripsi' => 'Konsultasi kesehatan gratis', 'is_active' => true];

        // Dukcapil
        $layanan[] = ['unit_pelayanan_id' => $dukcapil->id, 'nama' => 'Pembuatan KTP', 'deskripsi' => 'Pembuatan KTP baru, perpanjangan, dan perubahan data', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dukcapil->id, 'nama' => 'Pembuatan KK', 'deskripsi' => 'Pembuatan Kartu Keluarga baru dan perubahan', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dukcapil->id, 'nama' => 'Pencatatan Akta Kelahiran', 'deskripsi' => 'Pencatatan dan penerbitan akta kelahiran', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $dukcapil->id, 'nama' => 'Pencatatan Akta Kematian', 'deskripsi' => 'Pencatatan dan penerbitan akta kematian', 'is_active' => true];

        // PUPR
        $layanan[] = ['unit_pelayanan_id' => $pupr->id, 'nama' => 'Perizinan Bangunan', 'deskripsi' => 'Perizinan mendirikan bangunan (IMB)', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $pupr->id, 'nama' => 'Perizinan Penggunaan Lahan', 'deskripsi' => 'Perizinan penggunaan lahan untuk pembangunan', 'is_active' => true];

        // RSUD
        $layanan[] = ['unit_pelayanan_id' => $rsud->id, 'nama' => 'Pelayanan Rawat Jalan', 'deskripsi' => 'Pelayanan kesehatan rawat jalan', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $rsud->id, 'nama' => 'Pelayanan Rawat Inap', 'deskripsi' => 'Pelayanan kesehatan rawat inap', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $rsud->id, 'nama' => 'Pelayanan Gawat Darurat', 'deskripsi' => 'Pelayanan kesehatan gawat darurat (IGD)', 'is_active' => true];

        // Kecamatan Kota Sumenep
        $layanan[] = ['unit_pelayanan_id' => $kecKota->id, 'nama' => 'Pembuatan Surat Domisili', 'deskripsi' => 'Pembuatan surat keterangan domisili', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $kecKota->id, 'nama' => 'Pembuatan Surat Keterangan', 'deskripsi' => 'Pembuatan surat keterangan berbagai keperluan', 'is_active' => true];

        // Kecamatan Kalianget
        $layanan[] = ['unit_pelayanan_id' => $kecKalianget->id, 'nama' => 'Pembuatan Surat Domisili', 'deskripsi' => 'Pembuatan surat keterangan domisili', 'is_active' => true];
        $layanan[] = ['unit_pelayanan_id' => $kecKalianget->id, 'nama' => 'Pembuatan Surat Keterangan', 'deskripsi' => 'Pembuatan surat keterangan berbagai keperluan', 'is_active' => true];

        foreach ($layanan as $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            DB::table('layanan')->insert($item);
        }
    }
}