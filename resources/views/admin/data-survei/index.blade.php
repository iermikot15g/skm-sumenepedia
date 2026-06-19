@extends('admin.layouts.master')

@section('title', 'Data Survei - SKM Sumenep')
@section('page-title', 'Data Survei')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        @if(auth()->user()->role == 'super_admin')
            <p class="text-muted mb-0">Lihat semua data survei dari seluruh unit pelayanan.</p>
        @else
            <p class="text-muted mb-0">Lihat data survei untuk unit Anda.</p>
        @endif
    </div>
    <a href="{{ route('admin.reports.export-excel') }}" class="btn btn-success">
        <i class="fas fa-file-excel me-2"></i>Ekspor Excel
    </a>
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="NIK atau Nama" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Layanan</label>
                <select name="layanan_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($layanan_list ?? [] as $layanan)
                        <option value="{{ $layanan->id }}" {{ request('layanan_id') == $layanan->id ? 'selected' : '' }}>
                            {{ $layanan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Periode</label>
                <select name="periode_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($periode_list ?? [] as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Survei -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>Layanan</th>
                        <th>IKM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($survei_data ?? [] as $survei)
                    <tr>
                        <td>{{ $loop->iteration + ($survei_data->currentPage() - 1) * $survei_data->perPage() }}</td>
                        <td>{{ $survei->tanggal_survei->format('d/m/Y H:i') }}</td>
                        <td>{{ $survei->nik }}</td>
                        <td>{{ $survei->nama ?? '-' }}</td>
                        <td>{{ $survei->unitPelayanan->nama ?? '-' }}</td>
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
                            <a href="{{ route('admin.data-survei.export-pdf', $survei->id) }}" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @if(auth()->user()->role == 'super_admin')
                                <form action="{{ route('admin.data-survei.destroy', $survei->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Hapus data survei ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada data survei.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($survei_data) && $survei_data->hasPages())
    <div class="card-footer bg-white">
        {{ $survei_data->links() }}
    </div>
    @endif
</div>
@endsection