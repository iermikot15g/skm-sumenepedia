<?php

namespace App\Models;

use App\Traits\Auditable;  // ← Perbaiki namespace
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit_pelayanan_id', // Tambahkan kolom ini di migrasi
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke unit pelayanan
    public function unitPelayanan()
    {
        return $this->belongsTo(UnitPelayanan::class);
    }

    // Cek role
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdminUnit()
    {
        return $this->role === 'admin_unit';
    }

    public function isPimpinanUnit()
    {
        return $this->role === 'pimpinan_unit';
    }

    public function isPimpinanUtama()
    {
        return $this->role === 'pimpinan_utama';
    }
}