<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\UnitPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query dasar
        $query = Layanan::with('unitPelayanan');
        
        // Filter berdasarkan role
        if ($user->role == 'admin_unit') {
            // Admin Unit hanya melihat layanan unitnya sendiri
            $unitId = $user->unit_pelayanan_id;
            if (!$unitId) {
                return redirect()->back()->with('error', 'Anda belum terhubung dengan unit pelayanan.');
            }
            $query->where('unit_pelayanan_id', $unitId);
        } elseif ($user->role == 'super_admin') {
            // Super Admin bisa melihat semua, dengan filter jika ada
            if ($request->has('unit_id') && $request->unit_id) {
                $query->where('unit_pelayanan_id', $request->unit_id);
            }
        }
        
        $layanan = $query->orderBy('unit_pelayanan_id')->orderBy('nama')->paginate(15);
        
        // Untuk filter Super Admin
        $units = [];
        if ($user->role == 'super_admin') {
            $units = UnitPelayanan::where('is_active', true)->get();
        }
        
        return view('admin.layanan.index', compact('layanan', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $units = [];
        
        if ($user->role == 'super_admin') {
            $units = UnitPelayanan::where('is_active', true)->get();
        } elseif ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if (!$unitId) {
                return redirect()->back()->with('error', 'Anda belum terhubung dengan unit pelayanan.');
            }
            $units = UnitPelayanan::where('id', $unitId)->get();
        }
        
        return view('admin.layanan.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'unit_pelayanan_id' => 'required|exists:unit_pelayanan,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validasi akses: Admin Unit hanya bisa membuat untuk unitnya sendiri
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($request->unit_pelayanan_id != $unitId) {
                return redirect()->back()
                    ->with('error', 'Anda hanya bisa membuat layanan untuk unit Anda sendiri.')
                    ->withInput();
            }
        }
        
        Layanan::create([
            'unit_pelayanan_id' => $request->unit_pelayanan_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        $user = Auth::user();
        
        // Validasi akses: Admin Unit hanya bisa edit layanan unitnya sendiri
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($layanan->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.layanan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit layanan ini.');
            }
        }
        
        $units = UnitPelayanan::where('is_active', true)->get();
        
        return view('admin.layanan.edit', compact('layanan', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $user = Auth::user();
        
        // Validasi akses: Admin Unit hanya bisa update layanan unitnya sendiri
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($layanan->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.layanan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengupdate layanan ini.');
            }
        }
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $layanan->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        $user = Auth::user();
        
        // Validasi akses: Admin Unit hanya bisa hapus layanan unitnya sendiri
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($layanan->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.layanan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus layanan ini.');
            }
        }
        
        // Cek apakah layanan sudah digunakan di survei
        $used = $layanan->survei()->exists();
        if ($used) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Layanan ini sudah digunakan dalam survei dan tidak dapat dihapus.');
        }
        
        $layanan->delete();
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Layanan $layanan)
    {
        $user = Auth::user();
        
        // Validasi akses
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($layanan->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.layanan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah status layanan ini.');
            }
        }
        
        $layanan->is_active = !$layanan->is_active;
        $layanan->save();
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Status layanan berhasil diubah.');
    }
}