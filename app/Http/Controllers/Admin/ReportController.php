<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitPelayanan;
use App\Models\Survei;
use App\Models\PeriodeSurvei;
use App\Models\JawabanSurvei;
use App\Models\OpsiJawabanUnsur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyReportExport;

class ReportController extends Controller
{
    /**
     * Halaman laporan untuk Admin Unit & Super Admin
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $periodeAktif = PeriodeSurvei::where('is_active', true)->first();
        $periodeList = PeriodeSurvei::all();
        
        $unitId = null;
        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
        }
        
        $unit = $unitId ? UnitPelayanan::find($unitId) : null;
        
        return view('admin.reports.index', compact('periodeAktif', 'periodeList', 'unit'));
    }

    /**
     * Generate laporan
     */
    public function generate(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'periode_id' => 'required|exists:periode_survei,id',
            'format' => 'required|in:pdf,excel',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $periode = PeriodeSurvei::find($request->periode_id);
        
        // Get data
        $query = Survei::with(['unitPelayanan', 'layanan', 'jawaban.opsiJawabanUnsur'])
            ->where('periode_id', $request->periode_id);

        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        }

        $data = $query->get();
        
        // Calculate IKM per unit
        $ikmPerUnit = $this->calculateIKMPerUnit($data);
        
        if ($request->format == 'pdf') {
            $pdf = Pdf::loadView('admin.reports.export-pdf', compact('data', 'periode', 'ikmPerUnit'));
            return $pdf->download('laporan-skp-' . $periode->nama . '.pdf');
        } else {
            return Excel::download(new SurveyReportExport($data, $periode), 'laporan-skp-' . $periode->nama . '.xlsx');
        }
    }

    /**
     * Export PDF langsung (tanpa generate)
     */
    public function exportPdf()
    {
        $user = Auth::user();
        $periode = PeriodeSurvei::where('is_active', true)->first();
        
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $query = Survei::with(['unitPelayanan', 'layanan', 'jawaban.opsiJawabanUnsur'])
            ->where('periode_id', $periode->id);

        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        }

        $data = $query->get();
        $ikmPerUnit = $this->calculateIKMPerUnit($data);
        
        $pdf = Pdf::loadView('admin.reports.export-pdf', compact('data', 'periode', 'ikmPerUnit'));
        return $pdf->download('laporan-skp-' . $periode->nama . '.pdf');
    }

    /**
     * Export Excel langsung
     */
    public function exportExcel()
    {
        $user = Auth::user();
        $periode = PeriodeSurvei::where('is_active', true)->first();
        
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $query = Survei::with(['unitPelayanan', 'layanan', 'jawaban.opsiJawabanUnsur'])
            ->where('periode_id', $periode->id);

        if ($user->role == 'admin_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        }

        $data = $query->get();
        
        return Excel::download(new SurveyReportExport($data, $periode), 'laporan-skp-' . $periode->nama . '.xlsx');
    }

    /**
     * Halaman laporan untuk Pimpinan
     */
    public function pimpinanIndex(Request $request)
    {
        $user = Auth::user();
        $periodeAktif = PeriodeSurvei::where('is_active', true)->first();
        $periodeList = PeriodeSurvei::all();
        
        $unitId = null;
        if ($user->role == 'pimpinan_unit') {
            $unitId = $user->unit_pelayanan_id;
        }
        
        $unit = $unitId ? UnitPelayanan::find($unitId) : null;
        $units = ($user->role == 'pimpinan_utama') ? UnitPelayanan::where('is_active', true)->get() : null;
        
        return view('admin.laporan.index', compact('periodeAktif', 'periodeList', 'unit', 'units'));
    }

    /**
     * Export PDF untuk Pimpinan
     */
    public function pimpinanExportPdf(Request $request)
    {
        $user = Auth::user();
        $periode = PeriodeSurvei::where('is_active', true)->first();
        
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $query = Survei::with(['unitPelayanan', 'layanan', 'jawaban.opsiJawabanUnsur'])
            ->where('periode_id', $periode->id);

        if ($user->role == 'pimpinan_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        } elseif ($user->role == 'pimpinan_utama' && $request->has('unit_id') && $request->unit_id) {
            $query->where('unit_pelayanan_id', $request->unit_id);
        }

        $data = $query->get();
        $ikmPerUnit = $this->calculateIKMPerUnit($data);
        
        $pdf = Pdf::loadView('admin.laporan.export-pdf', compact('data', 'periode', 'ikmPerUnit'));
        return $pdf->download('laporan-skp-' . $periode->nama . '.pdf');
    }

    /**
     * Export Excel untuk Pimpinan
     */
    public function pimpinanExportExcel(Request $request)
    {
        $user = Auth::user();
        $periode = PeriodeSurvei::where('is_active', true)->first();
        
        if (!$periode) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif.');
        }

        $query = Survei::with(['unitPelayanan', 'layanan', 'jawaban.opsiJawabanUnsur'])
            ->where('periode_id', $periode->id);

        if ($user->role == 'pimpinan_unit') {
            $unitId = $user->unit_pelayanan_id;
            if ($unitId) {
                $query->where('unit_pelayanan_id', $unitId);
            }
        } elseif ($user->role == 'pimpinan_utama' && $request->has('unit_id') && $request->unit_id) {
            $query->where('unit_pelayanan_id', $request->unit_id);
        }

        $data = $query->get();
        
        return Excel::download(new SurveyReportExport($data, $periode), 'laporan-skp-' . $periode->nama . '.xlsx');
    }

    /**
     * Hitung IKM per unit dari data survei
     */
    private function calculateIKMPerUnit($data)
    {
        $perUnit = [];
        
        foreach ($data->groupBy('unit_pelayanan_id') as $unitId => $surveis) {
            $unit = UnitPelayanan::find($unitId);
            if (!$unit) continue;
            
            $totalNilai = 0;
            $count = 0;
            
            foreach ($surveis as $survei) {
                $rata = $survei->jawaban->avg(function($j) {
                    return $j->opsiJawabanUnsur->nilai ?? 0;
                });
                if ($rata) {
                    $totalNilai += ($rata / 9) * 25;
                    $count++;
                }
            }
            
            $ikm = $count > 0 ? $totalNilai / $count : 0;
            $mutu = $this->getMutu($ikm);
            
            $perUnit[] = [
                'unit' => $unit,
                'total_survei' => $count,
                'ikm' => round($ikm, 2),
                'mutu' => $mutu,
            ];
        }
        
        // Urutkan dari tertinggi ke terendah
        usort($perUnit, function($a, $b) {
            return $b['ikm'] <=> $a['ikm'];
        });
        
        return $perUnit;
    }

    /**
     * Konversi IKM ke mutu pelayanan
     */
    private function getMutu($ikm)
    {
        if ($ikm >= 88.31) {
            return ['mutu' => 'A', 'kinerja' => 'Sangat Baik'];
        } elseif ($ikm >= 76.61) {
            return ['mutu' => 'B', 'kinerja' => 'Baik'];
        } elseif ($ikm >= 65.00) {
            return ['mutu' => 'C', 'kinerja' => 'Kurang Baik'];
        } else {
            return ['mutu' => 'D', 'kinerja' => 'Tidak Baik'];
        }
    }
}