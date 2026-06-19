@extends('admin.layouts.master')

@section('title', 'Dashboard Pimpinan Utama - SKM Sumenep')
@section('page-title', 'Dashboard Pimpinan Utama - Kabupaten Sumenep')

@section('content')
<!-- Informasi Read Only -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Anda berada dalam mode <strong>Read Only</strong>. Data ditampilkan untuk keperluan monitoring dan evaluasi seluruh unit pelayanan.
</div>

<!-- Statistik Cards -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">{{ $total_survei ?? 0 }}</div>
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
                    <div class="number">{{ $total_unit ?? 0 }}</div>
                    <div class="label">Unit Pelayanan</div>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">
                        @php
                            $avgIkm = collect($ikm_per_unit ?? [])->avg('ikm');
                        @endphp
                        {{ number_format($avgIkm, 2) }}
                    </div>
                    <div class="label">Rata-rata IKM</div>
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
                        @php
                            $top = collect($ikm_per_unit ?? [])->first();
                        @endphp
                        @if($top)
                            <span class="badge badge-mutu badge-mutu-{{ strtolower($top['mutu']['mutu']) }}" style="font-size:1.5rem;">
                                {{ $top['mutu']['mutu'] }}
                            </span>
                        @else
                            -
                        @endif
                    </div>
                    <div class="label">Mutu Tertinggi</div>
                </div>
                <div class="icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik IKM Per Unit -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">Peringkat IKM Per Unit Pelayanan</div>
            <div class="card-body">
                <canvas id="ikmUnitChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top & Bottom Units -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header text-success">
                <i class="fas fa-arrow-up me-2"></i>5 Unit Terbaik
            </div>
            <div class="card-body p-0">
                <ul class="list-unstyled mb-0">
                    @forelse($unit_terbaik ?? [] as $item)
                    <li class="d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                        <span>
                            <span class="badge bg-success me-2">{{ $loop->iteration }}</span>
                            {{ $item['unit']->nama }}
                        </span>
                        <span>
                            <span class="badge badge-mutu badge-mutu-{{ strtolower($item['mutu']['mutu']) }}">
                                {{ $item['mutu']['mutu'] }} - {{ $item['ikm'] }}
                            </span>
                        </span>
                    </li>
                    @empty
                    <li class="text-center py-4 text-muted">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header text-danger">
                <i class="fas fa-arrow-down me-2"></i>5 Unit Terbawah
            </div>
            <div class="card-body p-0">
                <ul class="list-unstyled mb-0">
                    @forelse($unit_terburuk ?? [] as $item)
                    <li class="d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                        <span>
                            <span class="badge bg-danger me-2">{{ $loop->iteration }}</span>
                            {{ $item['unit']->nama }}
                        </span>
                        <span>
                            <span class="badge badge-mutu badge-mutu-{{ strtolower($item['mutu']['mutu']) }}">
                                {{ $item['mutu']['mutu'] }} - {{ $item['ikm'] }}
                            </span>
                        </span>
                    </li>
                    @empty
                    <li class="text-center py-4 text-muted">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Trend Survei Per Bulan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">Trend Survei Per Bulan</div>
            <div class="card-body">
                <canvas id="trendChart"></canvas>
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
                    <a href="{{ route('admin.laporan.export-pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Ekspor PDF
                    </a>
                    <a href="{{ route('admin.laporan.export-excel') }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Ekspor Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart IKM Per Unit
    const ctx1 = document.getElementById('ikmUnitChart').getContext('2d');
    const ikmUnitData = @json($ikm_per_unit ?? []);
    
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ikmUnitData.map(item => item.unit.nama.substring(0, 20) + '...'),
            datasets: [{
                label: 'IKM',
                data: ikmUnitData.map(item => item.ikm),
                backgroundColor: ikmUnitData.map(item => {
                    const mutu = item.mutu.mutu;
                    if (mutu === 'A') return '#10b981';
                    if (mutu === 'B') return '#3b82f6';
                    if (mutu === 'C') return '#f59e0b';
                    return '#ef4444';
                }),
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'IKM: ' + context.raw + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function(value) { return value + '%'; }
                    }
                }
            }
        }
    });

    // Chart Trend Per Bulan
    const ctx2 = document.getElementById('trendChart').getContext('2d');
    const trendData = @json($survei_per_bulan ?? []);
    const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: trendData.map(item => bulanNames[item.bulan - 1] + ' ' + item.tahun),
            datasets: [{
                label: 'Jumlah Survei',
                data: trendData.map(item => item.total),
                borderColor: '#1a3a5c',
                backgroundColor: 'rgba(26, 58, 92, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#1a3a5c',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection