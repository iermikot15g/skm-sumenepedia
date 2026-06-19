@extends('admin.layouts.master')

@section('title', 'Manajemen Unit Pelayanan - SKM Sumenep')
@section('page-title', 'Manajemen Unit Pelayanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola semua unit pelayanan/OPD yang terdaftar di SKM Kabupaten Sumenep.</p>
    <a href="{{ route('admin.unit-pelayanan.create') }}" class="btn btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Tambah Unit
    </a>
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau Kode" value="{{ request('search') }}">
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
                <a href="{{ route('admin.unit-pelayanan.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Unit Pelayanan -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Nama Unit</th>
                        <th>Kode</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Total Layanan</th>
                        <th>Total Survei</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</td>
                        <td>
                            @if($unit->logo)
                                <img src="{{ asset('storage/' . $unit->logo) }}" alt="Logo" width="40" height="40" style="object-fit:cover; border-radius:8px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                    <i class="fas fa-building text-secondary"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $unit->nama }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $unit->kode }}</span></td>
                        <td>{{ $unit->telepon ?? '-' }}</td>
                        <td>
                            @if($unit->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $unit->layanan()->count() }}</td>
                        <td>{{ $unit->survei()->count() }}</td>
                        <td>
                            <a href="{{ route('admin.unit-pelayanan.edit', $unit->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.unit-pelayanan.toggle-active', $unit->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-{{ $unit->is_active ? 'warning' : 'success' }}" 
                                        onclick="return confirm('Ubah status unit ini?')">
                                    <i class="fas fa-{{ $unit->is_active ? 'times' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.unit-pelayanan.destroy', $unit->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Hapus unit ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada data unit pelayanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($units->hasPages())
    <div class="card-footer bg-white">
        {{ $units->links() }}
    </div>
    @endif
</div>
@endsection