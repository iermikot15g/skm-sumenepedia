@extends('admin.layouts.master')

@section('title', 'Laporan - SKM Sumenep')
@section('page-title', 'Laporan Survei Kepuasan Masyarakat')

@section('content')
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    @if(auth()->user()->role == 'pimpinan_unit')
        Anda dapat melihat laporan untuk unit <strong>{{ $unit->nama ?? 'Anda' }}</strong>.
    @else
        Anda dapat melihat laporan untuk seluruh unit pelayanan di Kabupaten Sumenep.
    @endif
</div>

<div class="card-custom">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.export-pdf') }}" target="_blank">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <select name="periode_id" class="form-select" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ $periode->is_active ? 'selected' : '' }}>
                                {{ $periode->nama }} {{ $periode->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if(auth()->user()->role == 'pimpinan_utama')
                <div class="col-md-4">
                    <label class="form-label">Unit Pelayanan</label>
                    <select name="unit_id" class="form-select">
                        <option value="">-- Semua Unit --</option>
                        @foreach($units as $unitItem)
                            <option value="{{ $unitItem->id }}">{{ $unitItem->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" name="format" value="pdf" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </button>
                        <button type="submit" name="format" value="excel" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </button>
                    </div>
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
                <i class="fas fa-file-alt me-2"></i>Preview Laporan
                <small class="text-muted ms-2">(Periode Aktif: {{ $periodeAktif->nama ?? 'Belum ada periode aktif' }})</small>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-print fa-3x text-muted mb-3"></i>
                    <p class="text-muted">
                        Pilih periode dan format laporan di atas, lalu klik tombol <strong>PDF</strong> atau <strong>Excel</strong>.
                    </p>
                    <p class="text-muted small">
                        Laporan akan diunduh secara otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection