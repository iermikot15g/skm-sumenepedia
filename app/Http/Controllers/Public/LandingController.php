<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\UnitPelayanan;
use App\Models\Survei;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil beberapa statistik untuk ditampilkan di landing page
        $totalUnit = UnitPelayanan::where('is_active', true)->count();
        $totalSurvei = Survei::count();
        
        // Ambil 5 OPD dengan rating tertinggi (contoh, nanti akan dihitung dari data survei)
        // Untuk sekarang, kita tampilkan OPD aktif saja
        $topOpds = UnitPelayanan::where('is_active', true)->take(5)->get();
        
        return view('public.landing', compact('totalUnit', 'totalSurvei', 'topOpds'));
    }
}