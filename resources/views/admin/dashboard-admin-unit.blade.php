@extends('admin.layouts.master')

@section('title', 'Dashboard Admin Unit - SKM Sumenep')
@section('page-title', 'Dashboard Admin Unit - {{ $unit->nama ?? "Unit Saya" }}')

@section('content')
<!-- Statistik Cards -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">{{ $total_survei_unit ?? 0 }}</div>
                    <div class="label">Total Survei</div>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">{{ $total_layanan ?? 0 }}</div>
                    <div class="label">Total Layanan</div>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">{{ $ikm_unit['ikm'] ?? '-' }}</div>
                    <div class="label">IKM</div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">
                        @if(isset($ikm_unit['mutu']))
                            <span class="badge badge-mutu badge-mutu-{{ strtolower($ikm_unit['mutu']['mutu']) }}" style="font-size:1.5rem;">
                                {{ $ikm_unit['mutu']['mutu'] }}
                            </span>
                        @else
                            -
                        @endif
                    </div>
                    <div class="label">Mutu Pelayanan</div>
                </div>
                <div class="icon">
                    <i class="fas fa-medal"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik IKM Per Unsur -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card-custom">
            <div class="card-header">IKM Per Unsur Pelayanan</div>
            <div class="card-body">
                <canvas id="ikmUnsurChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Distribusi Survei Per Layanan -->
    <div class="col-md-4">
        <div class="card-custom">
            <div class="card-header">Distribusi Survei Per Layanan</div>
            <div class="card-body">
                <canvas id="layananChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Survei Terbaru -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Survei Terbaru</span>
                <a href="{{ route('admin.data-survei') }}" class="btn btn-primary-custom btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Layanan</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($survei_terbaru ?? [] as $survei)
                            <tr>
                                <td>{{ $survei->tanggal_survei->format('d/m/Y H:i') }}</td>
                                <td>{{ $survei->nik }}</td>
                                <td>{{ $survei->nama ?? '-' }}</td>
                                <td>{{ $survei->layanan->nama ?? '-' }}</td>
                                <td>
                                    @php
                                        $rata = $survei->jawaban->avg(function($j) {
                                            return $j->opsiJawabanUnsur->nilai ?? 0;
                                        });
                                        $ikm = $rata ? ($rata / 9) * 25 : 0;
                                    @endphp
                                    {{ number_format($ikm, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.data-survei.show', $survei->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                    Belum ada data survei.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Ekspor Laporan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">Ekspor Laporan</div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admin.reports.export-pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Ekspor PDF
                    </a>
                    <a href="{{ route('admin.reports.export-excel') }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Ekspor Excel
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="fas fa-cog me-2"></i>Generate Laporan Kustom
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Generate Laporan -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Laporan Kustom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select" required>
                            <option value="">-- Pilih Periode --</option>
                            @foreach($periode_list ?? [] as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart IKM Per Unsur
    const ctx1 = document.getElementById('ikmUnsurChart').getContext('2d');
    const ikmUnsurData = @json($ikm_per_unsur ?? []);
    const unsurLabels = @json(\App\Models\UnsurSurvei::pluck('nama', 'id')->toArray() ?? []);
    
    new Chart(ctx1, {
        type: 'radar',
        data: {
            labels: Object.keys(ikmUnsurData).map(id => unsurLabels[id] || 'Unsur ' + id),
            datasets: [{
                label: 'Nilai Rata-rata',
                data: Object.values(ikmUnsurData),
                backgroundColor: 'rgba(26, 58, 92, 0.2)',
                borderColor: '#1a3a5c',
                pointBackgroundColor: '#1a3a5c',
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    min: 1,
                    max: 4,
                    ticks: {
                        stepSize: 0.5
                    }
                }
            }
        }
    });

    // Chart Distribusi Layanan
    const ctx2 = document.getElementById('layananChart').getContext('2d');
    const layananData = @json($survei_per_layanan ?? []);
    
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: layananData.map(item => item.layanan.nama),
            datasets: [{
                data: layananData.map(item => item.total),
                backgroundColor: ['#1a3a5c', '#2d6a9f', '#4a8bc2', '#6ba3d4', '#8fbce6', '#b3d4e8']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 10 }
                    }
                }
            }
        }
    });
</script>
@endsection