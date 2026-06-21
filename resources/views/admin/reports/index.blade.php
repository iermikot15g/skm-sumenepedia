@extends('admin.layouts.master')

@section('title', 'Laporan - SKM Sumenep')
@section('page-title', 'Laporan Survei Kepuasan Masyarakat')

@section('content')
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Informasi:</strong> Generate laporan berdasarkan periode survei. Laporan akan berisi rekapitulasi IKM per unit pelayanan.
</div>

<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.reports.generate') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Periode Survei</label>
                    <select name="periode_id" class="form-select" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ $periode->is_active ? 'selected' : '' }}>
                                {{ $periode->nama }} 
                                @if($periode->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Format</label>
                    <select name="format" class="form-select" required>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-file-export me-2"></i>Generate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Laporan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-file-alt me-2" style="color: var(--gold);"></i>Preview Laporan
                <small class="text-muted ms-2">(Periode Aktif: {{ $periodeAktif->nama ?? 'Belum ada periode aktif' }})</small>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-print fa-3x text-muted mb-3" style="opacity:0.3;"></i>
                    <p class="text-muted">
                        Pilih periode dan format laporan di atas, lalu klik tombol <strong>Generate</strong>.
                    </p>
                    <p class="text-muted small">
                        Laporan akan diunduh secara otomatis setelah diproses.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Statistik Cepat -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">Statistik Cepat</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stat-card" style="border-bottom-color: var(--green-primary);">
                            <div class="number">{{ \App\Models\Survei::count() }}</div>
                            <div class="label">Total Survei</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-bottom-color: var(--gold);">
                            <div class="number">{{ \App\Models\UnitPelayanan::where('is_active', true)->count() }}</div>
                            <div class="label">Unit Aktif</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-bottom-color: var(--green-mid);">
                            <div class="number">{{ \App\Models\PeriodeSurvei::where('is_active', true)->count() }}</div>
                            <div class="label">Periode Aktif</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="border-bottom-color: var(--gold-dark);">
                            <div class="number">{{ \App\Models\Layanan::where('is_active', true)->count() }}</div>
                            <div class="label">Layanan Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection