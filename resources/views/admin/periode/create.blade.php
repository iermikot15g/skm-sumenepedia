@extends('admin.layouts.master')

@section('title', 'Tambah Periode Survei - SKM Sumenep')
@section('page-title', 'Tambah Periode Survei Baru')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.periode.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Nama Periode <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                       placeholder="Contoh: Triwulan I 2024" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select name="tahun" class="form-select @error('tahun') is-invalid @enderror" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ old('tahun') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Triwulan <span class="text-danger">*</span></label>
                    <select name="triwulan" class="form-select @error('triwulan') is-invalid @enderror" required>
                        <option value="">-- Pilih Triwulan --</option>
                        <option value="1" {{ old('triwulan') == 1 ? 'selected' : '' }}>I (Jan - Mar)</option>
                        <option value="2" {{ old('triwulan') == 2 ? 'selected' : '' }}>II (Apr - Jun)</option>
                        <option value="3" {{ old('triwulan') == 3 ? 'selected' : '' }}>III (Jul - Sep)</option>
                        <option value="4" {{ old('triwulan') == 4 ? 'selected' : '' }}>IV (Okt - Des)</option>
                    </select>
                    @error('triwulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                           value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                           value="{{ old('tanggal_selesai') }}" required>
                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktifkan Sekarang</label>
                </div>
                <small class="text-muted">Jika diaktifkan, periode lain akan otomatis dinonaktifkan.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Simpan</button>
                <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection