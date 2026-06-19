<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnsurSurvei extends Model
{
    use HasFactory;

    protected $table = 'unsur_survei';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    // Relasi ke opsi jawaban
    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawabanUnsur::class);
    }

    // Relasi ke jawaban survei (melalui opsi_jawaban_unsur)
    public function jawabanSurvei()
    {
        return $this->hasManyThrough(
            JawabanSurvei::class,
            OpsiJawabanUnsur::class,
            'unsur_survei_id', // Foreign key di opsi_jawaban_unsur
            'opsi_jawaban_unsur_id', // Foreign key di jawaban_survei
            'id', // Local key di unsur_survei
            'id' // Local key di opsi_jawaban_unsur
        );
    }
}