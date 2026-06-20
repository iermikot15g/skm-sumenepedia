<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Survei - SKM Sumenep</title>
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
            max-width: 800px;
            margin: 20px auto;
        }

        .card-form {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.06);
            border-top: 5px solid var(--gold);
        }

        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .step .number {
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

        .step .text {
            font-weight: 700;
            color: var(--green-dark);
            font-size: 1.1rem;
        }

        .step .info {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .survey-info {
            background: var(--green-bg);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--gold);
        }

        .survey-info strong {
            color: var(--green-dark);
        }

        .progress-container {
            margin-bottom: 25px;
        }

        .progress-container .progress {
            height: 8px;
            border-radius: 10px;
            background: #e8ece8;
        }

        .progress-container .progress-bar {
            background: linear-gradient(90deg, var(--green-primary), var(--green-mid));
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .progress-container .progress-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .question-card {
            border: 2px solid #e8ece8;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .question-card:hover {
            border-color: var(--green-light);
        }

        .question-card .question-number {
            font-weight: 700;
            color: var(--green-primary);
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .question-card .question-text {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 1.05rem;
        }

        .question-card .options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .question-card .options .option {
            flex: 1;
            min-width: 55px;
        }

        .question-card .options .option input[type="radio"] {
            display: none;
        }

        .question-card .options .option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 5px;
            border: 2px solid #e8ece8;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            text-align: center;
            background: white;
            font-weight: 500;
        }

        .question-card .options .option label .value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--green-primary);
        }

        .question-card .options .option input[type="radio"]:checked + label {
            border-color: var(--gold);
            background: var(--gold-light);
            box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.2);
        }

        .question-card .options .option label:hover {
            border-color: var(--gold);
            background: #f8f3e8;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: white;
            padding: 14px 50px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            border: none;
        }

        .btn-submit:hover {
            transform: scale(1.02) translateY(-3px);
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

        @media (max-width: 768px) {
            .card-form {
                padding: 20px 15px;
            }
            .question-card .options .option {
                min-width: 40px;
            }
            .question-card .options .option label {
                padding: 8px 4px;
                font-size: 0.7rem;
            }
            .question-card .options .option label .value {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="card-form">
            <!-- Step Indicator -->
            <div class="step">
                <div class="number">2</div>
                <div class="text">Pertanyaan Survei</div>
                <div class="ms-auto info">
                    <i class="fas fa-user me-1"></i>{{ $survei->nik }}
                </div>
            </div>

            <!-- Info -->
            <div class="survey-info">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-building me-1" style="color: var(--gold);"></i>Unit:</strong>
                        {{ $survei->unitPelayanan->nama }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-list me-1" style="color: var(--gold);"></i>Layanan:</strong>
                        {{ $survei->layanan->nama }}
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="d-flex justify-content-between mb-1">
                    <span class="progress-text">Progress</span>
                    <span class="progress-text" id="progressText">0 / 9</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
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

            <form action="{{ route('survey.store-jawaban', $survei->id) }}" method="POST" id="surveyForm">
                @csrf

                @foreach($unsur as $index => $item)
                <div class="question-card">
                    <div class="question-number">Pertanyaan {{ $index + 1 }} dari 9</div>
                    <div class="question-text">{{ $item->nama }}</div>
                    <div class="options">
                        @foreach($item->opsiJawaban as $opsi)
                        <div class="option">
                            <input type="radio" name="jawaban[{{ $item->id }}]" 
                                   id="q{{ $item->id }}_{{ $opsi->id }}" 
                                   value="{{ $opsi->id }}" 
                                   data-nilai="{{ $opsi->nilai }}"
                                   {{ old('jawaban.' . $item->id) == $opsi->id ? 'checked' : '' }}
                                   onchange="updateProgress()">
                            <label for="q{{ $item->id }}_{{ $opsi->id }}">
                                <span class="value">{{ $opsi->nilai }}</span>
                                <span>{{ $opsi->label }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="text-center mt-4">
                    <a href="{{ route('survey.identitas', $survei->unit_pelayanan_id) }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Survei
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateProgress() {
            const total = 9;
            const radios = document.querySelectorAll('input[type="radio"]');
            const answered = new Set();

            radios.forEach(radio => {
                if (radio.checked) {
                    const name = radio.name;
                    answered.add(name);
                }
            });

            const count = answered.size;
            const percent = Math.round((count / total) * 100);

            document.getElementById('progressBar').style.width = percent + '%';
            document.getElementById('progressText').textContent = count + ' / ' + total;
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
        });

        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            const total = 9;
            const radios = document.querySelectorAll('input[type="radio"]');
            const answered = new Set();

            radios.forEach(radio => {
                if (radio.checked) {
                    const name = radio.name;
                    answered.add(name);
                }
            });

            if (answered.size < total) {
                e.preventDefault();
                alert('⚠️ Harap jawab semua pertanyaan sebelum mengirim survei.');
            }
        });
    </script>
</body>
</html>