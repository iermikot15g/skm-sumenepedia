<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - SKM Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-success {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 550px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .icon-success {
            width: 100px;
            height: 100px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        .icon-success i {
            font-size: 3.5rem;
            color: #065f46;
        }
        .card-success h2 {
            color: #1a3a5c;
            font-weight: 700;
        }
        .card-success p {
            color: #6b7280;
            margin-bottom: 30px;
        }
        .btn-home {
            background: #1a3a5c;
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-home:hover {
            background: #2d6a9f;
            color: white;
            transform: scale(1.02);
        }
        .btn-survey-again {
            background: #fbbf24;
            color: #1a3a5c;
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin-left: 10px;
        }
        .btn-survey-again:hover {
            background: #f59e0b;
            color: #1a3a5c;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="card-success">
        <div class="icon-success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Terima Kasih!</h2>
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