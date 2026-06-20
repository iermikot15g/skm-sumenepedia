<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Masyarakat - Kabupaten Sumenep</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--green-bg);
            color: var(--text-dark);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-primary) 50%, var(--green-mid) 100%);
            color: var(--text-white);
            padding: 80px 0 70px;
            border-radius: 0 0 60px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.02);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero .container {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--text-white);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .hero .lead {
            font-weight: 300;
            opacity: 0.95;
            color: var(--text-white);
            font-size: 1.3rem;
        }

        .hero .sub-text {
            opacity: 0.9;
            font-size: 1.1rem;
            color: var(--text-white);
        }

        /* ===== BUTTONS ===== */
        .btn-login-header {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(255,255,255,0.15);
            color: var(--text-white);
            padding: 8px 22px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
            font-size: 0.9rem;
            backdrop-filter: blur(4px);
            z-index: 2;
        }

        .btn-login-header:hover {
            background: rgba(255,255,255,0.3);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .btn-survey {
            background: var(--gold);
            color: var(--text-dark);
            font-weight: 700;
            padding: 16px 45px;
            border-radius: 50px;
            font-size: 1.2rem;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 20px rgba(200, 169, 110, 0.3);
        }

        .btn-survey:hover {
            transform: scale(1.05) translateY(-3px);
            background: var(--gold-dark);
            color: var(--text-dark);
            box-shadow: 0 8px 30px rgba(200, 169, 110, 0.4);
        }

        /* ===== STATISTICS ===== */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.06);
            text-align: center;
            transition: all 0.3s;
            border-bottom: 4px solid var(--gold);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--green-primary);
            line-height: 1.2;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--gold);
            opacity: 0.3;
            margin-bottom: 5px;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            margin-top: 60px;
            padding: 25px 0;
            background: white;
            border-top: 2px solid var(--green-light);
        }

        .footer p {
            margin-bottom: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .footer .gold-text {
            color: var(--gold-dark);
            font-weight: 600;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .btn-login-header {
                top: 15px;
                right: 15px;
                padding: 6px 16px;
                font-size: 0.8rem;
            }
            .stat-number {
                font-size: 2rem;
            }
            .btn-survey {
                padding: 14px 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- ===== HERO SECTION ===== -->
    <div class="hero text-center">
        <div class="container">
            <!-- Tombol Login -->
            <a href="{{ route('login') }}" class="btn-login-header">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>

            <!-- Logo -->
            <img src="{{ asset('images/logo-sumenep.png') }}" alt="Logo Sumenep" height="80" class="mb-4" style="filter: brightness(0) invert(1);">

            <h1>Survei Kepuasan Masyarakat</h1>
            <p class="lead mb-2">Kabupaten Sumenep</p>
            <p class="sub-text mb-4">Berikan penilaian Anda terhadap pelayanan publik yang Anda terima.</p>

            <a href="{{ route('survey.select-opd') }}" class="btn-survey">
                <i class="fas fa-pencil-alt me-2"></i>Mulai Isi Survei
            </a>
        </div>
    </div>

    <!-- ===== STATISTICS ===== -->
    <div class="container mt-5">
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">{{ $totalUnit }}</div>
                    <div class="stat-label">Unit Pelayanan</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="stat-number">{{ $totalSurvei }}</div>
                    <div class="stat-label">Total Survei</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="footer">
        <div class="container">
            <p>
                <i class="fas fa-leaf me-1" style="color: var(--green-primary);"></i>
                &copy; 2024 <span class="gold-text">Pemerintah Kabupaten Sumenep</span>
                <i class="fas fa-leaf ms-1" style="color: var(--green-primary);"></i>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>