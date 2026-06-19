<?php

namespace App\Models;

use App\Traits\Auditable;  // ← Perbaiki namespace
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeSurvei extends Model
{
    use HasFactory, Auditable;

    protected $table = 'periode_survei';

    protected $fillable = [
        'nama',
        'tahun',
        'triwulan',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke survei
    public function survei()
    {
        return $this->hasMany(Survei::class, 'periode_id');
    }

    // Scope untuk periode aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}