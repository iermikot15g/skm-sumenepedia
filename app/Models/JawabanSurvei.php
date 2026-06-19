<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSurvei extends Model
{
    use HasFactory;

    protected $table = 'jawaban_survei';

    protected $fillable = [
        'survei_id',
        'opsi_jawaban_unsur_id',
    ];

    // Relasi ke survei
    public function survei()
    {
        return $this->belongsTo(Survei::class);
    }

    // Relasi ke opsi jawaban unsur
    public function opsiJawabanUnsur()
    {
        return $this->belongsTo(OpsiJawabanUnsur::class);
    }

    // Mendapatkan nilai jawaban (1-4)
    public function getNilaiAttribute()
    {
        return $this->opsiJawabanUnsur->nilai;
    }

    // Mendapatkan label jawaban
    public function getLabelAttribute()
    {
        return $this->opsiJawabanUnsur->label;
    }
}