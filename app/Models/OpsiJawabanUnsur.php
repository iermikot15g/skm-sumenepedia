<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawabanUnsur extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban_unsur';

    protected $fillable = [
        'unsur_survei_id',
        'nilai',
        'label',
    ];

    // Relasi ke unsur survei
    public function unsurSurvei()
    {
        return $this->belongsTo(UnsurSurvei::class);
    }

    // Relasi ke jawaban survei
    public function jawabanSurvei()
    {
        return $this->hasMany(JawabanSurvei::class);
    }
}