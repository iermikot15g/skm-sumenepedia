<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - SKM Sumenep</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--green-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-success {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 550px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(13, 92, 59, 0.08);
            border-top: 5px solid var(--gold);
        }

        .icon-success {
            width: 100px;
            height: 100px;
            background: var(--green-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            border: 3px solid var(--green-primary);
        }

        .icon-success i {
            font-size: 3.5rem;
            color: var(--green-primary);
        }

        .card-success h2 {
            color: var(--green-dark);
            font-weight: 700;
            font-size: 2rem;
        }

        .card-success p {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .card-success .leaf-decoration {
            color: var(--gold);
            font-size: 1.2rem;
        }

        .btn-home {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            border: none;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(26, 122, 78, 0.3);
            color: white;
        }

        .btn-survey-again {
            background: var(--gold);
            color: var(--text-dark);
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin-left: 10px;
            border: none;
        }

        .btn-survey-again:hover {
            background: var(--gold-dark);
            color: var(--text-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(200, 169, 110, 0.3);
        }

        @media (max-width: 768px) {
            .card-success {
                padding: 30px 20px;
                margin: 15px;
            }
            .btn-survey-again {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="card-success">
        <div class="icon-success">
            <i class="fas fa-check-circle"></i>
        </div>

        <h2>
            <i class="fas fa-leaf leaf-decoration me-1"></i>
            Terima Kasih!
            <i class="fas fa-leaf leaf-decoration ms-1"></i>
        </h2>

        <p>
            Survei kepuasan masyarakat Anda telah berhasil dikirim.<br>
            Apresiasi kami atas partisipasi Anda dalam meningkatkan kualitas pelayanan publik di Kabupaten Sumenep.
        </p>

        <div>
            <a href="{{ route('landing') }}" class="btn-home">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
            <a href="{{ route('survey.select-opd') }}" class="btn-survey-again">
                <i class="fas fa-pencil-alt me-2"></i>Isi Survei Lain
            </a>
        </div>
    </div>
</body>
</html>