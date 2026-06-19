<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodeSurveiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PeriodeSurvei::query();
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', "%{$request->search}%");
        }
        
        // Filter status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
        
        $periodes = $query->orderBy('tahun', 'desc')
                          ->orderBy('triwulan', 'desc')
                          ->paginate(15);
        
        return view('admin.periode.index', compact('periodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.periode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'triwulan' => 'required|integer|min:1|max:4',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Jika periode ini diaktifkan, nonaktifkan periode lain
        if ($request->has('is_active')) {
            PeriodeSurvei::where('is_active', true)->update(['is_active' => false]);
        }

        PeriodeSurvei::create([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'triwulan' => $request->triwulan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode survei berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeSurvei $periode)
    {
        return view('admin.periode.edit', compact('periode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodeSurvei $periode)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'triwulan' => 'required|integer|min:1|max:4',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Jika periode ini diaktifkan, nonaktifkan periode lain
        if ($request->has('is_active') && !$periode->is_active) {
            PeriodeSurvei::where('is_active', true)->update(['is_active' => false]);
        }

        $periode->update([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'triwulan' => $request->triwulan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode survei berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeSurvei $periode)
    {
        // Cek apakah periode sudah memiliki data survei
        $hasSurvei = $periode->survei()->exists();
        if ($hasSurvei) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Periode ini sudah memiliki data survei dan tidak dapat dihapus.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode survei berhasil dihapus.');
    }

    /**
     * Activate a periode
     */
    public function activate(PeriodeSurvei $periode)
    {
        // Nonaktifkan semua periode
        PeriodeSurvei::where('is_active', true)->update(['is_active' => false]);
        
        // Aktifkan periode yang dipilih
        $periode->is_active = true;
        $periode->save();

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode '{$periode->nama}' berhasil diaktifkan.");
    }
}