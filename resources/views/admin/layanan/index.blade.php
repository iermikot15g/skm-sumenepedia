@extends('admin.layouts.master')

@section('title', 'Manajemen Layanan - SKM Sumenep')
@section('page-title', 'Manajemen Layanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        @if(auth()->user()->role == 'super_admin')
            <p class="text-muted mb-0">Lihat semua layanan dari seluruh unit pelayanan.</p>
        @elseif(auth()->user()->role == 'admin_unit')
            <p class="text-muted mb-0">Kelola layanan untuk unit <strong>{{ $unit->nama ?? 'Anda' }}</strong>.</p>
        @endif
    </div>
    @if(auth()->user()->role == 'admin_unit')
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary-custom">
            <i class="fas fa-plus me-2"></i>Tambah Layanan
        </a>
    @endif
</div>

<!-- Filter untuk Super Admin -->
@if(auth()->user()->role == 'super_admin')
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Unit</label>
                <select name="unit_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $unitItem)
                        <option value="{{ $unitItem->id }}" {{ request('unit_id') == $unitItem->id ? 'selected' : '' }}>
                            {{ $unitItem->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Tabel Layanan -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Layanan</th>
                        <th>Unit</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Total Survei</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($layanan as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($layanan->currentPage() - 1) * $layanan->perPage() }}</td>
                        <td><strong>{{ $item->nama }}</strong></td>
                        <td>{{ $item->unitPelayanan->nama ?? '-' }}</td>
                        <td>{{ Str::limit($item->deskripsi, 40) }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $item->survei()->count() }}</td>
                        <td>
                            @if(auth()->user()->role == 'admin_unit')
                                <a href="{{ route('admin.layanan.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.layanan.toggle', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $item->is_active ? 'warning' : 'success' }}" 
                                            onclick="return confirm('Ubah status layanan ini?')">
                                        <i class="fas fa-{{ $item->is_active ? 'times' : 'check' }}"></i>
                                    </button>
                                </form>
                            @endif
                            @if(auth()->user()->role == 'super_admin')
                                <form action="{{ route('admin.layanan.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Hapus layanan ini? (Data survei terkait akan tetap tersimpan)')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada data layanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($layanan->hasPages())
    <div class="card-footer bg-white">
        {{ $layanan->links() }}
    </div>
    @endif
</div>
@endsection