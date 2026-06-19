@extends('admin.layouts.master')

@section('title', 'Edit Layanan - SKM Sumenep')
@section('page-title', 'Edit Layanan')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="form-label">Unit Pelayanan</label>
                <p class="form-control-static">
                    <strong>{{ $layanan->unitPelayanan->nama }}</strong>
                </p>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                       placeholder="Masukkan nama layanan" value="{{ old('nama', $layanan->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                          rows="3" placeholder="Deskripsi layanan">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktif</label>
                </div>
                <small class="text-muted">Layanan aktif akan muncul di pilihan survei.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Update</button>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection