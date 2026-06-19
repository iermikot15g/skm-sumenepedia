@extends('admin.layouts.master')

@section('title', 'Manajemen User - SKM Sumenep')
@section('page-title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola semua user yang memiliki akses ke aplikasi SKM.</p>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom">
        <i class="fas fa-user-plus me-2"></i>Tambah User
    </a>
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau Email" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin_unit" {{ request('role') == 'admin_unit' ? 'selected' : '' }}>Admin Unit</option>
                    <option value="pimpinan_unit" {{ request('role') == 'pimpinan_unit' ? 'selected' : '' }}>Pimpinan Unit</option>
                    <option value="pimpinan_utama" {{ request('role') == 'pimpinan_utama' ? 'selected' : '' }}>Pimpinan Utama</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel User -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-primary ms-1">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleLabels = [
                                    'super_admin' => 'Super Admin',
                                    'admin_unit' => 'Admin Unit',
                                    'pimpinan_unit' => 'Pimpinan Unit',
                                    'pimpinan_utama' => 'Pimpinan Utama',
                                ];
                                $roleColors = [
                                    'super_admin' => 'danger',
                                    'admin_unit' => 'primary',
                                    'pimpinan_unit' => 'warning',
                                    'pimpinan_utama' => 'info',
                                ];
                            @endphp
                            <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td>{{ $user->unitPelayanan->nama ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Hapus user ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada data user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection