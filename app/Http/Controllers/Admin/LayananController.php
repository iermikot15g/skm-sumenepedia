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
        } else {
            // Role lain tidak boleh akses
            abort(403, 'Unauthorized access.');
        }
        
        $layanan = $query->orderBy('unit_pelayanan_id')->orderBy('nama')->paginate(15);
        
        // Untuk filter Super Admin
        $units = [];
        if ($user->role == 'super_admin') {
            $units = UnitPelayanan::where('is_active', true)->get();
        }
        
        // Untuk Admin Unit, kirimkan unit info
        $unit = null;
        if ($user->role == 'admin_unit') {
            $unit = UnitPelayanan::find($user->unit_pelayanan_id);
        }
        
        return view('admin.layanan.index', compact('layanan', 'units', 'unit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Hanya admin_unit yang boleh create
        if ($user->role != 'admin_unit') {
            abort(403, 'Unauthorized access.');
        }
        
        $unitId = $user->unit_pelayanan_id;
        if (!$unitId) {
            return redirect()->back()->with('error', 'Anda belum terhubung dengan unit pelayanan.');
        }
        
        $unit = UnitPelayanan::find($unitId);
        
        return view('admin.layanan.create', compact('unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Hanya admin_unit yang boleh store
        if ($user->role != 'admin_unit') {
            abort(403, 'Unauthorized access.');
        }
        
        $unitId = $user->unit_pelayanan_id;
        if (!$unitId) {
            return redirect()->back()->with('error', 'Anda belum terhubung dengan unit pelayanan.');
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
        
        Layanan::create([
            'unit_pelayanan_id' => $unitId,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
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
        
        // Hanya admin_unit yang boleh edit
        if ($user->role != 'admin_unit') {
            abort(403, 'Unauthorized access.');
        }
        
        $unitId = $user->unit_pelayanan_id;
        if ($layanan->unit_pelayanan_id != $unitId) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit layanan ini.');
        }
        
        $unit = UnitPelayanan::find($unitId);
        
        return view('admin.layanan.edit', compact('layanan', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $user = Auth::user();
        
        // Hanya admin_unit yang boleh update
        if ($user->role != 'admin_unit') {
            abort(403, 'Unauthorized access.');
        }
        
        $unitId = $user->unit_pelayanan_id;
        if ($layanan->unit_pelayanan_id != $unitId) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate layanan ini.');
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
            'is_active' => $request->has('is_active'),
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
        
        // Validasi akses
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($layanan->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.layanan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus layanan ini.');
            }
        } elseif ($user->role == 'super_admin') {
            // Super Admin bisa hapus semua (untuk pengawasan)
            // Tapi kita tetap kasih peringatan
        } else {
            abort(403, 'Unauthorized access.');
        }
        
        // Cek apakah layanan sudah digunakan di survei
        $used = $layanan->survei()->exists();
        if ($used) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Layanan ini sudah digunakan dalam survei dan tidak dapat dihapus.');
        }
        
        $layanan->delete();
        
        $message = 'Layanan berhasil dihapus.';
        if ($user->role == 'super_admin') {
            $message .= ' (Dihapus oleh Super Admin)';
        }
        
        return redirect()->route('admin.layanan.index')
            ->with('success', $message);
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
        } elseif ($user->role != 'super_admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $layanan->is_active = !$layanan->is_active;
        $layanan->save();
        
        $status = $layanan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.layanan.index')
            ->with('success', "Layanan berhasil {$status}.");
    }
}