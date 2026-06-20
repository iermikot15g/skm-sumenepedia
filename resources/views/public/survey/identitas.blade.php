<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Responden - SKM Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ========== GREEN LUXURY THEME ========== */
        :root {
            --green-dark: #0d5c3b;
            --green-primary: #1a7a4e;
            --green-mid: #28a06b;
            --green-light: #d4edda;
            --green-bg: #f0faf4;
            --gold: #c8a96e;
            --gold-dark: #b8955a;
            --gold-light: #e8d5b5;
            --text-dark: #0d2b1f;
            --text-muted: #3d5a4a;
            --text-white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--green-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        .container-main {
            max-width: 700px;
            margin: 30px auto;
        }

        .card-form {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.06);
            border-top: 5px solid var(--gold);
        }

        .card-form .step {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .card-form .step .number {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: white;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        .card-form .step .text {
            font-weight: 700;
            color: var(--green-dark);
            font-size: 1.1rem;
        }

        .card-form .step .unit-name {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #e8ece8;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--green-primary);
            box-shadow: 0 0 0 4px rgba(26, 122, 78, 0.1);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
        }

        .required-star {
            color: #dc3545;
            margin-left: 3px;
        }

        .btn-next {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: white;
            padding: 14px 45px;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
            font-size: 1.05rem;
        }

        .btn-next:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(26, 122, 78, 0.3);
            color: white;
        }

        .btn-back {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-back:hover {
            color: var(--green-primary);
        }

        .alert {
            border-radius: 12px;
            border-left: 4px solid;
        }

        .alert-danger {
            border-left-color: #dc3545;
        }

        .text-muted-custom {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .card-form {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="card-form">
            <!-- Step Indicator -->
            <div class="step">
                <div class="number">1</div>
                <div class="text">Data Responden</div>
                <div class="ms-auto unit-name">
                    <i class="fas fa-building me-1" style="color: var(--gold);"></i>{{ $unit->nama }}
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('survey.store-identitas', $unit->id) }}" method="POST">
                @csrf

                <!-- Pilih Layanan -->
                <div class="mb-4">
                    <label class="form-label">Layanan yang Digunakan <span class="required-star">*</span></label>
                    <select name="layanan_id" class="form-select @error('layanan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanan as $layananItem)
                            <option value="{{ $layananItem->id }}" {{ old('layanan_id') == $layananItem->id ? 'selected' : '' }}>
                                {{ $layananItem->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- NIK -->
                <div class="mb-4">
                    <label class="form-label">NIK <span class="required-star">*</span></label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                           placeholder="16 digit NIK" maxlength="16" value="{{ old('nik') }}" required>
                    <small class="text-muted-custom">
                        <i class="fas fa-info-circle me-1"></i>NIK akan digunakan untuk memastikan 1 orang hanya bisa mengisi 1 kali per periode.
                    </small>
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nama <span class="text-muted">(opsional)</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" value="{{ old('nama') }}">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nomor HP/WA <span class="text-muted">(opsional)</span></label>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Usia <span class="text-muted">(opsional)</span></label>
                        <input type="number" name="usia" class="form-control" placeholder="Tahun" min="1" max="100" value="{{ old('usia') }}">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Jenis Kelamin <span class="text-muted">(opsional)</span></label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Pendidikan <span class="text-muted">(opsional)</span></label>
                        <select name="pendidikan" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="SD/MI" {{ old('pendidikan') == 'SD/MI' ? 'selected' : '' }}>SD/MI</option>
                            <option value="SMP/MTs" {{ old('pendidikan') == 'SMP/MTs' ? 'selected' : '' }}>SMP/MTs</option>
                            <option value="SMA/MA/SMK" {{ old('pendidikan') == 'SMA/MA/SMK' ? 'selected' : '' }}>SMA/MA/SMK</option>
                            <option value="D1/D2" {{ old('pendidikan') == 'D1/D2' ? 'selected' : '' }}>D1/D2</option>
                            <option value="D3" {{ old('pendidikan') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="D4/S1" {{ old('pendidikan') == 'D4/S1' ? 'selected' : '' }}>D4/S1</option>
                            <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                </div>

                <!-- Pekerjaan -->
                <div class="mb-4">
                    <label class="form-label">Pekerjaan <span class="text-muted">(opsional)</span></label>
                    <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan Anda" value="{{ old('pekerjaan') }}">
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('survey.select-opd') }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn-next">
                        Lanjut ke Pertanyaan <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>