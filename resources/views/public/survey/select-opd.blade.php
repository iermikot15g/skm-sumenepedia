<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Unit Pelayanan - SKM Sumenep</title>
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

        /* ===== HEADER ===== */
        .header {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-primary) 50%, var(--green-mid) 100%);
            color: var(--text-white);
            padding: 35px 0 30px;
            border-radius: 0 0 40px 40px;
            position: relative;
        }

        .header h2 {
            font-weight: 700;
            font-size: 2rem;
        }

        .header p {
            opacity: 0.9;
            font-weight: 300;
        }

        .header .back-link {
            position: absolute;
            top: 20px;
            left: 25px;
            color: var(--text-white);
            opacity: 0.8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .header .back-link:hover {
            opacity: 1;
            color: var(--text-white);
        }

        /* ===== SEARCH BOX ===== */
        .search-box {
            border-radius: 50px;
            padding: 14px 22px;
            border: 2px solid #d4ddd4;
            transition: all 0.3s;
            font-size: 1rem;
            background: white;
        }

        .search-box:focus {
            border-color: var(--green-primary);
            box-shadow: 0 0 0 4px rgba(26, 122, 78, 0.1);
        }

        /* ===== OPD CARD ===== */
        .opd-card {
            background: white;
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            height: 100%;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            text-decoration: none;
            color: var(--text-dark);
            display: block;
        }

        .opd-card:hover {
            transform: translateY(-6px);
            border-color: var(--gold);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .opd-card .icon {
            font-size: 2.8rem;
            color: var(--green-primary);
            margin-bottom: 12px;
        }

        .opd-card .nama {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .opd-card .kode {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .opd-card .badge-status {
            background: var(--green-light);
            color: var(--green-dark);
            font-size: 0.7rem;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 8px;
            display: inline-block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header h2 {
                font-size: 1.5rem;
            }
            .header .back-link {
                top: 12px;
                left: 15px;
                font-size: 0.85rem;
            }
            .opd-card .nama {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- ===== HEADER ===== -->
    <div class="header text-center">
        <div class="container">
            <a href="{{ route('landing') }}" class="back-link">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <h2><i class="fas fa-building me-2" style="color: var(--gold);"></i>Pilih Unit Pelayanan</h2>
            <p class="mb-0">Pilih unit/OPD tempat Anda menerima pelayanan</p>
        </div>
    </div>

    <!-- ===== CONTENT ===== -->
    <div class="container py-4">
        <!-- Search Box -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <input type="text" id="searchOpd" class="form-control search-box" 
                       placeholder="🔍 Cari unit pelayanan..." onkeyup="filterOpd()">
            </div>
        </div>

        <!-- Daftar OPD -->
        <div class="row g-4" id="opdList">
            @foreach($opds as $opd)
            <div class="col-md-3 col-6" data-nama="{{ strtolower($opd->nama) }}">
                <a href="{{ route('survey.identitas', $opd->id) }}" class="opd-card">
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="nama">{{ $opd->nama }}</div>
                    <div class="kode">{{ $opd->kode }}</div>
                    <span class="badge-status">
                        <i class="fas fa-circle" style="font-size:0.5rem; color: var(--green-primary);"></i> Aktif
                    </span>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Jika tidak ada hasil -->
        <div id="noResult" class="text-center py-5" style="display:none;">
            <i class="fas fa-search fa-3x text-muted mb-3" style="opacity:0.3;"></i>
            <p class="text-muted">Unit pelayanan tidak ditemukan.</p>
        </div>
    </div>

    <script>
        function filterOpd() {
            const search = document.getElementById('searchOpd').value.toLowerCase();
            const items = document.querySelectorAll('#opdList .col-md-3');
            let found = false;

            items.forEach(item => {
                const nama = item.getAttribute('data-nama');
                if (nama.includes(search)) {
                    item.style.display = 'block';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });

            document.getElementById('noResult').style.display = found ? 'none' : 'block';
        }
    </script>
</body>
</html>