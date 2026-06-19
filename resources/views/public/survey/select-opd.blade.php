<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Unit Pelayanan - SKM Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f4f8;
        }
        .header {
            background: #1a3a5c;
            color: white;
            padding: 30px 0;
        }
        .opd-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            height: 100%;
        }
        .opd-card:hover {
            transform: translateY(-5px);
            border-color: #1a3a5c;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .opd-card .icon {
            font-size: 3rem;
            color: #1a3a5c;
            margin-bottom: 15px;
        }
        .opd-card .nama {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .opd-card .kode {
            font-size: 0.85rem;
            color: #6b7280;
        }
        .search-box {
            border-radius: 50px;
            padding: 12px 20px;
            border: 2px solid #e5e7eb;
        }
        .search-box:focus {
            border-color: #1a3a5c;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h2>Pilih Unit Pelayanan</h2>
            <p class="mb-0">Pilih unit/OPD tempat Anda menerima pelayanan</p>
        </div>
    </div>

    <div class="container py-4">
        <!-- Search Box -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <input type="text" id="searchOpd" class="form-control search-box" 
                       placeholder="Cari unit pelayanan..." onkeyup="filterOpd()">
            </div>
        </div>

        <!-- Daftar OPD -->
        <div class="row g-4" id="opdList">
            @foreach($opds as $opd)
            <div class="col-md-3 col-6" data-nama="{{ strtolower($opd->nama) }}">
                <a href="{{ route('survey.identitas', $opd->id) }}" class="text-decoration-none text-dark">
                    <div class="opd-card">
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="nama">{{ $opd->nama }}</div>
                        <div class="kode">{{ $opd->kode }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function filterOpd() {
            const search = document.getElementById('searchOpd').value.toLowerCase();
            const items = document.querySelectorAll('#opdList .col-md-3');
            items.forEach(item => {
                const nama = item.getAttribute('data-nama');
                if (nama.includes(search)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>