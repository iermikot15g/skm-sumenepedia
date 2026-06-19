@extends('admin.layouts.master')

@section('title', 'Dashboard Pimpinan Unit - SKM Sumenep')
@section('page-title', 'Dashboard Pimpinan Unit - {{ $unit->nama ?? "Unit Saya" }}')

@section('content')
<!-- Informasi Read Only -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Anda berada dalam mode <strong>Read Only</strong>. Data ditampilkan untuk keperluan monitoring dan evaluasi.
</div>

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
    
    <!-- Ringkasan -->
    <div class="col-md-4">
        <div class="card-custom">
            <div class="card-header">Ringkasan</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nilai Tertinggi</label>
                    <div class="fw-bold text-success">
                        @if(!empty($ikm_per_unsur))
                            @php
                                $max = max($ikm_per_unsur);
                                $maxKey = array_search($max, $ikm_per_unsur);
                                $maxLabel = \App\Models\UnsurSurvei::find($maxKey)->nama ?? 'Unsur ' . $maxKey;
                            @endphp
                            {{ $maxLabel }}: {{ number_format($max, 2) }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nilai Terendah</label>
                    <div class="fw-bold text-danger">
                        @if(!empty($ikm_per_unsur))
                            @php
                                $min = min($ikm_per_unsur);
                                $minKey = array_search($min, $ikm_per_unsur);
                                $minLabel = \App\Models\UnsurSurvei::find($minKey)->nama ?? 'Unsur ' . $minKey;
                            @endphp
                            {{ $minLabel }}: {{ number_format($min, 2) }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div>
                    <label class="text-muted small">Rekomendasi</label>
                    <div>
                        @if(!empty($ikm_per_unsur))
                            @php
                                $min = min($ikm_per_unsur);
                                $minKey = array_search($min, $ikm_per_unsur);
                                $minLabel = \App\Models\UnsurSurvei::find($minKey)->nama ?? 'Unsur ' . $minKey;
                            @endphp
                            <span class="badge bg-warning text-dark">Perbaiki: {{ $minLabel }}</span>
                        @else
                            <span class="text-muted">Belum ada data</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel IKM Per Layanan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">IKM Per Layanan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Layanan</th>
                                <th>Jumlah Survei</th>
                                <th>IKM</th>
                                <th>Mutu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $layananStats = [];
                                if (!empty($survei_per_layanan)) {
                                    foreach ($survei_per_layanan as $item) {
                                        $surveis = \App\Models\Survei::where('layanan_id', $item->layanan_id)->get();
                                        $total = 0;
                                        $count = 0;
                                        foreach ($surveis as $survei) {
                                            $rata = $survei->jawaban->avg(function($j) {
                                                return $j->opsiJawabanUnsur->nilai ?? 0;
                                            });
                                            if ($rata) {
                                                $total += ($rata / 9) * 25;
                                                $count++;
                                            }
                                        }
                                        $ikm = $count > 0 ? $total / $count : 0;
                                        $layananStats[] = [
                                            'layanan' => $item->layanan,
                                            'total' => $item->total,
                                            'ikm' => $ikm,
                                        ];
                                    }
                                    usort($layananStats, function($a, $b) {
                                        return $b['ikm'] <=> $a['ikm'];
                                    });
                                }
                            @endphp
                            @forelse($layananStats as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['layanan']->nama }}</td>
                                <td>{{ $item['total'] }}</td>
                                <td>{{ number_format($item['ikm'], 2) }}</td>
                                <td>
                                    @php
                                        $mutu = $item['ikm'] >= 88.31 ? 'A' : ($item['ikm'] >= 76.61 ? 'B' : ($item['ikm'] >= 65.00 ? 'C' : 'D'));
                                    @endphp
                                    <span class="badge badge-mutu badge-mutu-{{ strtolower($mutu) }}">{{ $mutu }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
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
</script>
@endsection