<?php

namespace App\Models;

use App\Traits\Auditable;  // ← Perbaiki namespace
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPelayanan extends Model
{
    use HasFactory, Auditable;

    protected $table = 'unit_pelayanan';

    protected $fillable = [
        'nama',
        'kode',
        'alamat',
        'telepon',
        'email',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke layanan
    public function layanan()
    {
        return $this->hasMany(Layanan::class);
    }

    // Relasi ke survei
    public function survei()
    {
        return $this->hasMany(Survei::class);
    }

    // Scope untuk unit aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}