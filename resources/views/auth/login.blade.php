<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SKM Sumenep</title>
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
            --gold-light: #e8d5b5;
            --text-dark: #1a2e28;
            --text-muted: #5a7a6a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--green-bg) 0%, var(--green-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-login {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 45px 35px;
            box-shadow: 0 20px 60px rgba(13, 92, 59, 0.12);
            border-top: 5px solid var(--gold);
        }

        .card-login .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .card-login .brand .icon {
            font-size: 3rem;
            color: var(--green-primary);
            margin-bottom: 10px;
        }

        .card-login .brand h3 {
            color: var(--green-dark);
            font-weight: 700;
            margin-bottom: 0;
        }

        .card-login .brand small {
            color: var(--text-muted);
            font-weight: 400;
        }

        .card-login .brand .leaf {
            color: var(--gold);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #e8ece8;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--green-primary);
            box-shadow: 0 0 0 4px rgba(26, 122, 78, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: white;
            padding: 14px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            border: none;
            font-size: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 122, 78, 0.3);
            color: white;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: var(--green-primary);
        }

        .alert {
            border-radius: 12px;
            border-left: 4px solid;
        }

        .alert-danger {
            border-left-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="card-login">
        <div class="brand">
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
                <i class="fas fa-leaf leaf" style="font-size: 1.2rem; margin-left: -8px;"></i>
            </div>
            <h3>SKM Sumenep</h3>
            <small>Survei Kepuasan Masyarakat</small>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope me-2" style="color: var(--green-primary);"></i>Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label"><i class="fas fa-lock me-2" style="color: var(--green-primary);"></i>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="back-link">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>