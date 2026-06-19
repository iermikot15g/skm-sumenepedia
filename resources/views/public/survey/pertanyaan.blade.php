<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Survei - SKM Sumenep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f4f8;
        }
        .container-main {
            max-width: 800px;
            margin: 20px auto;
        }
        .card-form {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        .step .number {
            background: #1a3a5c;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .step .text {
            font-weight: 600;
            color: #1a3a5c;
        }
        .progress-container {
            margin-bottom: 25px;
        }
        .progress-container .progress {
            height: 8px;
            border-radius: 10px;
            background: #e5e7eb;
        }
        .progress-container .progress-bar {
            background: #1a3a5c;
            border-radius: 10px;
        }
        .question-card {
            border: 2px solid #f0f4f8;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .question-card:hover {
            border-color: #d1d5db;
        }
        .question-card .question-number {
            font-weight: 700;
            color: #1a3a5c;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .question-card .question-text {
            font-weight: 600;
            margin-bottom: 15px;
        }
        .question-card .options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .question-card .options .option {
            flex: 1;
            min-width: 60px;
        }
        .question-card .options .option input[type="radio"] {
            display: none;
        }
        .question-card .options .option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 5px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            text-align: center;
            background: white;
        }
        .question-card .options .option label .value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a3a5c;
        }
        .question-card .options .option input[type="radio"]:checked + label {
            border-color: #1a3a5c;
            background: #e8f0fe;
        }
        .question-card .options .option label:hover {
            border-color: #1a3a5c;
            background: #f0f4f8;
        }
        .btn-submit {
            background: #1a3a5c;
            color: white;
            padding: 14px 50px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background: #2d6a9f;
            color: white;
            transform: scale(1.02);
        }
        .error-text {
            color: red;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .survey-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-size: 0.9rem;
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
                <div class="ms-auto text-muted" style="font-size:0.9rem;">
                    <i class="fas fa-user me-1"></i>{{ $survei->nik }}
                </div>
            </div>

            <!-- Info -->
            <div class="survey-info">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Unit:</strong> {{ $survei->unitPelayanan->nama }}
                    </div>
                    <div class="col-md-6">
                        <strong>Layanan:</strong> {{ $survei->layanan->nama }}
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:0.85rem;">Progress</span>
                    <span class="text-muted" style="font-size:0.85rem;" id="progressText">0 / 9</span>
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
                    <button type="submit" class="btn btn-submit">
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

        // Update progress on load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
        });

        // Prevent form submission if not all questions answered
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
                alert('Harap jawab semua pertanyaan sebelum mengirim survei.');
            }
        });
    </script>
</body>
</html>