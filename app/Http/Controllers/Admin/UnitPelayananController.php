<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UnitPelayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UnitPelayanan::query();
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('kode', 'like', "%{$request->search}%");
        }
        
        // Filter status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
        
        $units = $query->orderBy('nama')->paginate(15);
        
        return view('admin.unit-pelayanan.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.unit-pelayanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:unit_pelayanan,kode',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $path = $logo->storeAs('unit-logos', $filename, 'public');
            $data['logo'] = $path;
        }

        UnitPelayanan::create($data);

        return redirect()->route('admin.unit-pelayanan.index')
            ->with('success', 'Unit pelayanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitPelayanan $unitPelayanan)
    {
        return view('admin.unit-pelayanan.edit', compact('unitPelayanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitPelayanan $unitPelayanan)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:unit_pelayanan,kode,' . $unitPelayanan->id,
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload logo baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($unitPelayanan->logo && Storage::disk('public')->exists($unitPelayanan->logo)) {
                Storage::disk('public')->delete($unitPelayanan->logo);
            }
            
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $path = $logo->storeAs('unit-logos', $filename, 'public');
            $data['logo'] = $path;
        }

        $unitPelayanan->update($data);

        return redirect()->route('admin.unit-pelayanan.index')
            ->with('success', 'Unit pelayanan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitPelayanan $unitPelayanan)
    {
        // Cek apakah unit sudah memiliki data survei
        $hasSurvei = $unitPelayanan->survei()->exists();
        if ($hasSurvei) {
            return redirect()->route('admin.unit-pelayanan.index')
                ->with('error', 'Unit ini sudah memiliki data survei dan tidak dapat dihapus.');
        }

        // Hapus logo
        if ($unitPelayanan->logo && Storage::disk('public')->exists($unitPelayanan->logo)) {
            Storage::disk('public')->delete($unitPelayanan->logo);
        }

        $unitPelayanan->delete();

        return redirect()->route('admin.unit-pelayanan.index')
            ->with('success', 'Unit pelayanan berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(UnitPelayanan $unitPelayanan)
    {
        $unitPelayanan->is_active = !$unitPelayanan->is_active;
        $unitPelayanan->save();

        return redirect()->route('admin.unit-pelayanan.index')
            ->with('success', 'Status unit pelayanan berhasil diubah.');
    }
}