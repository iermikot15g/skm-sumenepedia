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

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $periodeAktif = PeriodeSurvei::where('is_active', true)->first();
        
        // Data dasar
        $totalSurvei = Survei::count();
        $totalUnit = UnitPelayanan::where('is_active', true)->count();
        
        // Default data
        $data = [
            'total_survei' => $totalSurvei,
            'total_unit' => $totalUnit,
            'periode_aktif' => $periodeAktif,
        ];
        
        // Berdasarkan role
        switch ($user->role) {
            case 'super_admin':
                return $this->superAdminDashboard($data);
                
            case 'admin_unit':
                return $this->adminUnitDashboard($data);
                
            case 'pimpinan_unit':
                return $this->pimpinanUnitDashboard($data);
                
            case 'pimpinan_utama':
                return $this->pimpinanUtamaDashboard($data);
                
            default:
                return view('admin.dashboard', $data);
        }
    }
    
    /**
     * Dashboard Super Admin - Melihat semua data
     */
    private function superAdminDashboard($data)
    {
        // Statistik tambahan untuk Super Admin
        $data['total_user'] = \App\Models\User::count();
        $data['total_layanan'] = \App\Models\Layanan::count();
        $data['survei_per_periode'] = Survei::selectRaw('periode_id, count(*) as total')
            ->groupBy('periode_id')
            ->with('periode')
            ->get();
        
        // IKM per unit (rata-rata)
        $data['ikm_per_unit'] = $this->calculateIKMPerUnit();
        
        return view('admin.dashboard-super', $data);
    }
    
    /**
     * Dashboard Admin Unit - Melihat data unitnya sendiri
     */
    private function adminUnitDashboard($data)
    {
        $user = Auth::user();
        $unitId = $user->unit_pelayanan_id;
        
        if (!$unitId) {
            return view('admin.dashboard-admin-unit', $data)->with('error', 'Anda belum terhubung dengan unit pelayanan.');
        }
        
        $unit = UnitPelayanan::find($unitId);
        
        // Statistik untuk unit ini
        $data['unit'] = $unit;
        $data['total_survei_unit'] = Survei::where('unit_pelayanan_id', $unitId)->count();
        $data['survei_per_layanan'] = Survei::selectRaw('layanan_id, count(*) as total')
            ->where('unit_pelayanan_id', $unitId)
            ->groupBy('layanan_id')
            ->with('layanan')
            ->get();
        
        // IKM unit
        $data['ikm_unit'] = $this->calculateIKMForUnit($unitId);
        $data['ikm_per_unsur'] = $this->calculateIKMPerUnsur($unitId);
        
        // Data survei terbaru
        $data['survei_terbaru'] = Survei::where('unit_pelayanan_id', $unitId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard-admin-unit', $data);
    }
    
    /**
     * Dashboard Pimpinan Unit - Read only untuk unitnya
     */
    private function pimpinanUnitDashboard($data)
    {
        $user = Auth::user();
        $unitId = $user->unit_pelayanan_id;
        
        if (!$unitId) {
            return view('admin.dashboard-pimpinan-unit', $data)->with('error', 'Anda belum terhubung dengan unit pelayanan.');
        }
        
        $unit = UnitPelayanan::find($unitId);
        $data['unit'] = $unit;
        $data['total_survei_unit'] = Survei::where('unit_pelayanan_id', $unitId)->count();
        $data['ikm_unit'] = $this->calculateIKMForUnit($unitId);
        $data['ikm_per_unsur'] = $this->calculateIKMPerUnsur($unitId);
        
        return view('admin.dashboard-pimpinan-unit', $data);
    }
    
    /**
     * Dashboard Pimpinan Utama - Melihat semua unit
     */
    private function pimpinanUtamaDashboard($data)
    {
        // Statistik semua unit
        $data['ikm_per_unit'] = $this->calculateIKMPerUnit();
        $data['unit_terbaik'] = $this->getTopUnits(5);
        $data['unit_terburuk'] = $this->getBottomUnits(5);
        $data['survei_per_bulan'] = Survei::selectRaw('YEAR(tanggal_survei) as tahun, MONTH(tanggal_survei) as bulan, count(*) as total')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(12)
            ->get();
        
        return view('admin.dashboard-pimpinan-utama', $data);
    }
    
    /**
     * Hitung IKM untuk satu unit
     */
    private function calculateIKMForUnit($unitId)
    {
        // Ambil semua jawaban untuk unit ini
        $jawaban = JawabanSurvei::whereHas('survei', function($query) use ($unitId) {
            $query->where('unit_pelayanan_id', $unitId);
        })->with('opsiJawabanUnsur')->get();
        
        if ($jawaban->isEmpty()) {
            return null;
        }
        
        // Kelompokkan per unsur
        $perUnsur = [];
        foreach ($jawaban as $j) {
            $unsurId = $j->opsiJawabanUnsur->unsur_survei_id;
            if (!isset($perUnsur[$unsurId])) {
                $perUnsur[$unsurId] = [];
            }
            $perUnsur[$unsurId][] = $j->opsiJawabanUnsur->nilai;
        }
        
        // Hitung rata-rata per unsur
        $rataPerUnsur = [];
        foreach ($perUnsur as $unsurId => $nilai) {
            $rataPerUnsur[$unsurId] = array_sum($nilai) / count($nilai);
        }
        
        // IKM = (Jumlah rata-rata per unsur / 9) * 25
        $totalRata = array_sum($rataPerUnsur);
        $ikm = ($totalRata / 9) * 25;
        
        return [
            'ikm' => round($ikm, 2),
            'mutu' => $this->getMutu($ikm),
            'per_unsur' => $rataPerUnsur,
        ];
    }
    
    /**
     * Hitung IKM per unsur untuk satu unit
     */
    private function calculateIKMPerUnsur($unitId)
    {
        $jawaban = JawabanSurvei::whereHas('survei', function($query) use ($unitId) {
            $query->where('unit_pelayanan_id', $unitId);
        })->with('opsiJawabanUnsur')->get();
        
        if ($jawaban->isEmpty()) {
            return [];
        }
        
        $perUnsur = [];
        foreach ($jawaban as $j) {
            $unsurId = $j->opsiJawabanUnsur->unsur_survei_id;
            if (!isset($perUnsur[$unsurId])) {
                $perUnsur[$unsurId] = [];
            }
            $perUnsur[$unsurId][] = $j->opsiJawabanUnsur->nilai;
        }
        
        $hasil = [];
        foreach ($perUnsur as $unsurId => $nilai) {
            $rata = array_sum($nilai) / count($nilai);
            $hasil[$unsurId] = round($rata, 2);
        }
        
        return $hasil;
    }
    
    /**
     * Hitung IKM per unit untuk semua unit
     */
    private function calculateIKMPerUnit()
    {
        $units = UnitPelayanan::where('is_active', true)->get();
        $hasil = [];
        
        foreach ($units as $unit) {
            $ikm = $this->calculateIKMForUnit($unit->id);
            if ($ikm) {
                $hasil[] = [
                    'unit' => $unit,
                    'ikm' => $ikm['ikm'],
                    'mutu' => $ikm['mutu'],
                ];
            }
        }
        
        // Urutkan dari tertinggi ke terendah
        usort($hasil, function($a, $b) {
            return $b['ikm'] <=> $a['ikm'];
        });
        
        return $hasil;
    }
    
    /**
     * Dapatkan unit terbaik
     */
    private function getTopUnits($limit = 5)
    {
        $all = $this->calculateIKMPerUnit();
        return array_slice($all, 0, $limit);
    }
    
    /**
     * Dapatkan unit terburuk
     */
    private function getBottomUnits($limit = 5)
    {
        $all = $this->calculateIKMPerUnit();
        $reversed = array_reverse($all);
        return array_slice($reversed, 0, $limit);
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