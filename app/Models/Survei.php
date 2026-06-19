<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survei extends Model
{
    use HasFactory;

    protected $table = 'survei';

    protected $fillable = [
        'unit_pelayanan_id',
        'layanan_id',
        'periode_id',
        'nik',
        'nama',
        'no_hp',
        'usia',
        'jenis_kelamin',
        'pendidikan',
        'pekerjaan',
        'tanggal_survei',
    ];

    protected $casts = [
        'tanggal_survei' => 'datetime',
        'usia' => 'integer',
    ];

    // Relasi ke unit pelayanan
    public function unitPelayanan()
    {
        return $this->belongsTo(UnitPelayanan::class);
    }

    // Relasi ke layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    // Relasi ke periode survei
    public function periode()
    {
        return $this->belongsTo(PeriodeSurvei::class, 'periode_id');
    }

    // Relasi ke jawaban survei
    public function jawaban()
    {
        return $this->hasMany(JawabanSurvei::class);
    }

    // Cek apakah NIK sudah digunakan untuk periode dan unit tertentu
    public static function isNIKUsed($nik, $unitId, $periodeId)
    {
        return self::where('nik', $nik)
            ->where('unit_pelayanan_id', $unitId)
            ->where('periode_id', $periodeId)
            ->exists();
    }
}