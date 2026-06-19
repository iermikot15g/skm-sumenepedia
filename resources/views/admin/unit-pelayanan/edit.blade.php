@extends('admin.layouts.master')

@section('title', 'Edit Unit Pelayanan - SKM Sumenep')
@section('page-title', 'Edit Unit Pelayanan')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.unit-pelayanan.update', $unitPelayanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Nama Unit <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           placeholder="Nama unit pelayanan" value="{{ old('nama', $unitPelayanan->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                           placeholder="Kode unit" value="{{ old('kode', $unitPelayanan->kode) }}" required>
                    <small class="text-muted">Kode harus unik dan terdiri dari huruf kapital.</small>
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                          rows="2" placeholder="Alamat lengkap unit">{{ old('alamat', $unitPelayanan->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                           placeholder="Nomor telepon" value="{{ old('telepon', $unitPelayanan->telepon) }}">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Email unit" value="{{ old('email', $unitPelayanan->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Logo Saat Ini</label>
                @if($unitPelayanan->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $unitPelayanan->logo) }}" alt="Logo" width="100" height="100" style="object-fit:cover; border-radius:8px;">
                    </div>
                @else
                    <p class="text-muted">Belum ada logo.</p>
                @endif
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                       accept="image/*">
                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</small>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $unitPelayanan->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktif</label>
                </div>
                <small class="text-muted">Unit aktif akan muncul di daftar pilihan survei.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Update</button>
                <a href="{{ route('admin.unit-pelayanan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection