@extends('admin.layouts.master')

@section('title', 'Manajemen Periode Survei - SKM Sumenep')
@section('page-title', 'Manajemen Periode Survei')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola periode survei untuk mengatur jadwal pengumpulan data.</p>
    <a href="{{ route('admin.periode.create') }}" class="btn btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Tambah Periode
    </a>
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama Periode" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Periode -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Periode</th>
                        <th>Tahun</th>
                        <th>Triwulan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Total Survei</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $periode)
                    <tr>
                        <td>{{ $loop->iteration + ($periodes->currentPage() - 1) * $periodes->perPage() }}</td>
                        <td><strong>{{ $periode->nama }}</strong></td>
                        <td>{{ $periode->tahun }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $periode->triwulan == 1 ? 'I' : ($periode->triwulan == 2 ? 'II' : ($periode->triwulan == 3 ? 'III' : 'IV')) }}
                            </span>
                        </td>
                        <td>{{ $periode->tanggal_mulai->format('d/m/Y') }}</td>
                        <td>{{ $periode->tanggal_selesai->format('d/m/Y') }}</td>
                        <td>
                            @if($periode->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $periode->survei()->count() }}</td>
                        <td>
                            <a href="{{ route('admin.periode.edit', $periode->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$periode->is_active)
                                <form action="{{ route('admin.periode.activate', $periode->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            onclick="return confirm('Aktifkan periode ini?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.periode.destroy', $periode->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Hapus periode ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada data periode survei.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($periodes->hasPages())
    <div class="card-footer bg-white">
        {{ $periodes->links() }}
    </div>
    @endif
</div>
@endsection