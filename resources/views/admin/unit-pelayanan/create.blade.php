@extends('admin.layouts.master')

@section('title', 'Tambah Unit Pelayanan - SKM Sumenep')
@section('page-title', 'Tambah Unit Pelayanan')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.unit-pelayanan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Nama Unit <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           placeholder="Nama unit pelayanan" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                           placeholder="Kode unit (contoh: DINDIK)" value="{{ old('kode') }}" required>
                    <small class="text-muted">Kode harus unik dan terdiri dari huruf kapital.</small>
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                          rows="2" placeholder="Alamat lengkap unit">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                           placeholder="Nomor telepon" value="{{ old('telepon') }}">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Email unit" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                       accept="image/*">
                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktif</label>
                </div>
                <small class="text-muted">Unit aktif akan muncul di daftar pilihan survei.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Simpan</button>
                <a href="{{ route('admin.unit-pelayanan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection