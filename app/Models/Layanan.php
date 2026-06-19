<?php

namespace App\Models;

use App\Traits\Auditable;  // ← Perbaiki namespace
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory, Auditable;

    protected $table = 'layanan';

    protected $fillable = [
        'unit_pelayanan_id',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke unit pelayanan
    public function unitPelayanan()
    {
        return $this->belongsTo(UnitPelayanan::class);
    }

    // Relasi ke survei
    public function survei()
    {
        return $this->hasMany(Survei::class);
    }

    // Scope untuk layanan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}