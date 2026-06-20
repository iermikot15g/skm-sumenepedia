<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SKM Sumenep</title>
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
        .card-login {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .card-login .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .card-login .brand h3 {
            color: #1a3a5c;
            font-weight: 700;
            margin-bottom: 0;
        }
        .card-login .brand small {
            color: #6b7280;
        }
        .card-login .brand .icon {
            font-size: 3rem;
            color: #1a3a5c;
            margin-bottom: 10px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
        }
        .form-control:focus {
            border-color: #1a3a5c;
            box-shadow: none;
        }
        .btn-login {
            background: #1a3a5c;
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            border: none;
        }
        .btn-login:hover {
            background: #2d6a9f;
            color: white;
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="card-login">
        <div class="brand">
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>SKM Sumenep</h3>
            <small>Survei Kepuasan Masyarakat</small>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('landing') }}" class="text-muted text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>