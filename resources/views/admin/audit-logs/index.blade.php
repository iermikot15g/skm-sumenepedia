@extends('admin.layouts.master')

@section('title', 'Audit Log - SKM Sumenep')
@section('page-title', 'Audit Log')

@section('content')
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Mencatat semua aktivitas pengguna di sistem SKM Kabupaten Sumenep.
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Aktivitas..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Aksi</label>
                <select name="action" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Entitas</label>
                <select name="entity" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($entities as $entity)
                        <option value="{{ $entity }}" {{ request('entity') == $entity ? 'selected' : '' }}>
                            {{ $entity }}
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

<!-- Tabel Logs -->
<div class="card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Entitas</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge 
                                @if($log->action == 'create') bg-success
                                @elseif($log->action == 'update') bg-primary
                                @elseif($log->action == 'delete') bg-danger
                                @elseif($log->action == 'login') bg-info
                                @elseif($log->action == 'logout') bg-secondary
                                @else bg-warning
                                @endif">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->entity_type }}</td>
                        <td>{{ Str::limit($log->description, 60) }}</td>
                        <td>
                            <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Belum ada log aktivitas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection