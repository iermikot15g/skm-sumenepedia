@extends('admin.layouts.master')

@section('title', 'Edit User - SKM Sumenep')
@section('page-title', 'Edit User')

@section('content')
<div class="card-custom">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Nama lengkap" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Email user" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Minimal 8 karakter (kosongkan jika tidak diubah)">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Ulangi password jika diubah">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" id="roleSelect" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-4" id="unitField">
                    <label class="form-label">Unit Pelayanan</label>
                    <select name="unit_pelayanan_id" class="form-select @error('unit_pelayanan_id') is-invalid @enderror">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_pelayanan_id', $user->unit_pelayanan_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Wajib untuk role Admin Unit dan Pimpinan Unit.</small>
                    @error('unit_pelayanan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const unitField = document.getElementById('unitField');
        const role = this.value;
        
        if (role === 'admin_unit' || role === 'pimpinan_unit') {
            unitField.style.display = 'block';
            document.querySelector('select[name="unit_pelayanan_id"]').required = true;
        } else {
            unitField.style.display = 'none';
            document.querySelector('select[name="unit_pelayanan_id"]').required = false;
            document.querySelector('select[name="unit_pelayanan_id"]').value = '';
        }
    });
    
    // Trigger on load
    document.getElementById('roleSelect').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection