@extends('admin.layouts.master')

@section('title', 'Dashboard Super Admin - SKM Sumenep')
@section('page-title', 'Dashboard Super Admin')

@section('content')
<div class="row g-4">
    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="number">{{ $total_survei }}</div>
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
                    <div class="number">{{ $total_unit }}</div>
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
                    <div class="number">{{ $total_layanan }}</div>
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
                    <div class="number">{{ $total_user }}</div>
                    <div class="label">Total User</div>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- IKM Per Unit -->
    <div class="col-md-8">
        <div class="card-custom">
            <div class="card-header">Peringkat IKM Per Unit Pelayanan</div>
            <div class="card-body">
                <canvas id="ikmChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Periode Aktif -->
    <div class="col-md-4">
        <div class="card-custom">
            <div class="card-header">Periode Aktif</div>
            <div class="card-body">
                @if($periode_aktif)
                    <h4>{{ $periode_aktif->nama }}</h4>
                    <p class="text-muted">
                        {{ $periode_aktif->tanggal_mulai->format('d M Y') }} - 
                        {{ $periode_aktif->tanggal_selesai->format('d M Y') }}
                    </p>
                    <span class="badge bg-success">Aktif</span>
                @else
                    <p class="text-warning">Belum ada periode aktif</p>
                    <a href="{{ route('admin.periode.index') }}" class="btn btn-primary-custom btn-sm">Atur Periode</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Survei Per Periode -->
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header">Survei Per Periode</div>
            <div class="card-body">
                <canvas id="periodeChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top 5 Unit -->
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header">Top 5 Unit Pelayanan</div>
            <div class="card-body">
                @if(!empty($ikm_per_unit))
                    <ul class="list-unstyled">
                        @foreach(array_slice($ikm_per_unit, 0, 5) as $item)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span>{{ $item['unit']->nama }}</span>
                                <span>
                                    <span class="badge badge-mutu badge-mutu-{{ strtolower($item['mutu']['mutu']) }}">
                                        {{ $item['mutu']['mutu'] }} - {{ $item['ikm'] }}
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada data survei</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart IKM Per Unit
    const ctx1 = document.getElementById('ikmChart').getContext('2d');
    const ikmData = @json($ikm_per_unit);
    
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ikmData.map(item => item.unit.nama.substring(0, 15) + '...'),
            datasets: [{
                label: 'IKM',
                data: ikmData.map(item => item.ikm),
                backgroundColor: ikmData.map(item => {
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
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function(value) { return value + '%'; }
                    }
                }
            }
        }
    });

    // Chart Periode
    const ctx2 = document.getElementById('periodeChart').getContext('2d');
    const periodeData = @json($survei_per_periode);
    
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: periodeData.map(item => item.periode.nama),
            datasets: [{
                data: periodeData.map(item => item.total),
                backgroundColor: ['#1a3a5c', '#2d6a9f', '#4a8bc2', '#6ba3d4', '#8fbce6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection