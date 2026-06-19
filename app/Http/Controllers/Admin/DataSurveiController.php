<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survei;
use App\Models\Layanan;
use App\Models\PeriodeSurvei;
use App\Models\UnitPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DataSurveiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Survei::with(['unitPelayanan', 'layanan', 'periode', 'jawaban.opsiJawabanUnsur']);
        
        // Filter berdasarkan role
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        } elseif ($user->role == 'super_admin') {
            // Super Admin bisa filter semua
            if ($request->has('unit_id') && $request->unit_id) {
                $query->where('unit_pelayanan_id', $request->unit_id);
            }
        }
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        // Filter layanan
        if ($request->has('layanan_id') && $request->layanan_id) {
            $query->where('layanan_id', $request->layanan_id);
        }
        
        // Filter periode
        if ($request->has('periode_id') && $request->periode_id) {
            $query->where('periode_id', $request->periode_id);
        }
        
        // Filter tanggal
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal_survei', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal_survei', '<=', $request->end_date);
        }
        
        $survei_data = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Data untuk filter
        $periode_list = PeriodeSurvei::all();
        $layanan_list = Layanan::where('is_active', true)->get();
        
        return view('admin.data-survei.index', compact('survei_data', 'periode_list', 'layanan_list'));
    }
    
    public function show($id)
    {
        $survei = Survei::with([
            'unitPelayanan', 
            'layanan', 
            'periode',
            'jawaban.opsiJawabanUnsur.unsurSurvei'
        ])->findOrFail($id);
        
        // Validasi akses untuk admin_unit
        $user = Auth::user();
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($survei->unit_pelayanan_id != $unitId) {
                return redirect()->route('admin.data-survei')
                    ->with('error', 'Anda tidak memiliki akses ke data survei ini.');
            }
        }
        
        return view('admin.data-survei.show', compact('survei'));
    }
    
    public function destroy($id)
    {
        $survei = Survei::findOrFail($id);
        
        // Hanya super admin yang bisa hapus
        if (Auth::user()->role != 'super_admin') {
            return redirect()->route('admin.data-survei')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data survei.');
        }
        
        $survei->jawaban()->delete();
        $survei->delete();
        
        return redirect()->route('admin.data-survei')
            ->with('success', 'Data survei berhasil dihapus.');
    }
    
    public function exportPdf($id)
    {
        $survei = Survei::with([
            'unitPelayanan', 
            'layanan', 
            'jawaban.opsiJawabanUnsur.unsurSurvei'
        ])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.data-survei.export-pdf', compact('survei'));
        return $pdf->download('survei-' . $survei->nik . '.pdf');
    }
}