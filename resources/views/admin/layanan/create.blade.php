@extends('admin.layouts.master')

@section('title', 'Tambah Layanan - SKM Sumenep')
@section('page-title', 'Tambah Layanan Baru')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.layanan.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Unit Pelayanan</label>
                @if(auth()->user()->role == 'admin_unit')
                    <input type="hidden" name="unit_pelayanan_id" value="{{ auth()->user()->unit_pelayanan_id }}">
                    <p class="form-control-static">
                        {{ auth()->user()->unitPelayanan->nama ?? 'Unit tidak ditemukan' }}
                    </p>
                @else
                    <select name="unit_pelayanan_id" class="form-select @error('unit_pelayanan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_pelayanan_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_pelayanan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @endif
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                       placeholder="Masukkan nama layanan" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                          rows="3" placeholder="Deskripsi layanan">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Aktif</label>
                </div>
                <small class="text-muted">Layanan aktif akan muncul di pilihan survei.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Simpan</button>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection