<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Masyarakat - Kabupaten Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #1a3a5c 0%, #2d6a9f 100%);
            color: white;
            padding: 80px 0;
            border-radius: 0 0 50px 50px;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }
        .btn-survey {
            background: #fbbf24;
            color: #1a3a5c;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        .btn-survey:hover {
            transform: scale(1.05);
            background: #f59e0b;
            color: #1a3a5c;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a3a5c;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .opd-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .opd-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero text-center">
        <div class="container">
            <img src="{{ asset('images/logo-sumenep.png') }}" alt="Logo Sumenep" height="80" class="mb-4">
            <h1>Survei Kepuasan Masyarakat</h1>
            <p class="lead mb-4">Kabupaten Sumenep</p>
            <p class="mb-4">Berikan penilaian Anda terhadap pelayanan publik yang Anda terima.</p>
            <a href="{{ route('survey.select-opd') }}" class="btn btn-survey">
                <i class="fas fa-pencil-alt me-2"></i>Mulai Isi Survei
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="container mt-5">
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalUnit }}</div>
                    <div class="stat-label">Unit Pelayanan</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalSurvei }}</div>
                    <div class="stat-label">Total Survei</div>
                </div>
            </div>
        </div>
    </div>

    <!-- OPD Terbaik -->
    <div class="container mt-5">
        <h3 class="text-center mb-4">Unit Pelayanan Aktif</h3>
        <div class="row g-4">
            @foreach($topOpds as $opd)
            <div class="col-md-2 col-6">
                <div class="opd-card text-center">
                    <div class="mb-2">
                        <i class="fas fa-building fa-2x" style="color: #1a3a5c;"></i>
                    </div>
                    <h6 class="mb-0">{{ $opd->nama }}</h6>
                    <small class="text-muted">{{ $opd->kode }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-5 py-4" style="background: #f8f9fa;">
        <p class="mb-0 text-muted">&copy; 2024 Pemerintah Kabupaten Sumenep</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>