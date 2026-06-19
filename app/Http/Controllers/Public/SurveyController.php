<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\UnitPelayanan;
use App\Models\Layanan;
use App\Models\PeriodeSurvei;
use App\Models\Survei;
use App\Models\UnsurSurvei;
use App\Models\JawabanSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Halaman pilih OPD
     */
    public function selectOpd()
    {
        // Ambil semua OPD aktif
        $opds = UnitPelayanan::where('is_active', true)->get();
        
        return view('public.survey.select-opd', compact('opds'));
    }
    
    /**
     * Halaman identitas responden
     */
    public function identitas($unitId)
    {
        $unit = UnitPelayanan::findOrFail($unitId);
        $layanan = Layanan::where('unit_pelayanan_id', $unitId)
            ->where('is_active', true)
            ->get();
        
        return view('public.survey.identitas', compact('unit', 'layanan'));
    }
    
    /**
     * Simpan data identitas responden
     */
    public function storeIdentitas(Request $request, $unitId)
    {
        $unit = UnitPelayanan::findOrFail($unitId);
        
        // Validasi
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:layanan,id',
            'nik' => 'required|string|size:16',
            'nama' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'usia' => 'nullable|integer|min:1|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'pendidikan' => 'nullable|in:SD/MI,SMP/MTs,SMA/MA/SMK,D1/D2,D3,D4/S1,S2,S3',
            'pekerjaan' => 'nullable|string|max:255',
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nik.size' => 'NIK harus 16 digit',
            'layanan_id.required' => 'Silakan pilih layanan',
            'layanan_id.exists' => 'Layanan yang dipilih tidak valid',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Cek periode survei aktif
        $periode = PeriodeSurvei::where('is_active', true)->first();
        if (!$periode) {
            return redirect()->back()
                ->with('error', 'Belum ada periode survei yang aktif. Silakan hubungi admin.');
        }
        
        // Cek apakah NIK sudah pernah mengisi survei untuk periode dan unit ini
        $exists = Survei::where('nik', $request->nik)
            ->where('unit_pelayanan_id', $unitId)
            ->where('periode_id', $periode->id)
            ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withErrors(['nik' => 'NIK ini sudah pernah mengisi survei untuk periode ini di unit ini.'])
                ->withInput();
        }
        
        // Simpan data survei
        $survei = Survei::create([
            'unit_pelayanan_id' => $unitId,
            'layanan_id' => $request->layanan_id,
            'periode_id' => $periode->id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_survei' => now(),
        ]);
        
        // Simpan ID survei di session untuk digunakan di halaman pertanyaan
        Session::put('survei_id', $survei->id);
        
        // Redirect ke halaman pertanyaan
        return redirect()->route('survey.pertanyaan', $survei->id);
    }
    
    /**
     * Halaman pertanyaan survei (9 unsur)
     */
    public function pertanyaan($surveiId)
    {
        $survei = Survei::with(['unitPelayanan', 'layanan'])->findOrFail($surveiId);
        
        // Ambil semua 9 unsur dengan opsi jawabannya
        $unsur = UnsurSurvei::with('opsiJawaban')->get();
        
        // Pastikan ada 9 unsur
        if ($unsur->count() < 9) {
            return redirect()->route('survey.select-opd')
                ->with('error', 'Data pertanyaan survei belum lengkap. Silakan hubungi admin.');
        }
        
        return view('public.survey.pertanyaan', compact('survei', 'unsur'));
    }
    
    /**
     * Simpan jawaban survei
     */
    public function storeJawaban(Request $request, $surveiId)
    {
        $survei = Survei::findOrFail($surveiId);
        
        // Validasi: pastikan semua 9 pertanyaan dijawab
        $validator = Validator::make($request->all(), [
            'jawaban' => 'required|array|min:9|max:9',
            'jawaban.*' => 'required|exists:opsi_jawaban_unsur,id',
        ], [
            'jawaban.required' => 'Semua pertanyaan harus dijawab',
            'jawaban.min' => 'Semua pertanyaan harus dijawab',
            'jawaban.*.required' => 'Pertanyaan harus dijawab semua',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Simpan jawaban
        foreach ($request->jawaban as $unsurId => $opsiJawabanId) {
            JawabanSurvei::create([
                'survei_id' => $surveiId,
                'opsi_jawaban_unsur_id' => $opsiJawabanId,
            ]);
        }
        
        // Hapus session survei_id
        Session::forget('survei_id');
        
        // Redirect ke halaman selesai
        return redirect()->route('survey.selesai');
    }
    
    /**
     * Halaman selesai / terima kasih
     */
    public function selesai()
    {
        return view('public.survey.selesai');
    }
    
    /**
     * Ambil daftar layanan berdasarkan OPD (AJAX)
     */
    public function getLayanan($unitId)
    {
        $layanan = Layanan::where('unit_pelayanan_id', $unitId)
            ->where('is_active', true)
            ->get();
        
        return response()->json($layanan);
    }
}