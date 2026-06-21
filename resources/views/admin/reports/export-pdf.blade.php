<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan SKM - {{ $periode->nama }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #0d2b1f;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a7a4e;
            padding-bottom: 15px;
        }
        .header h2 {
            color: #0d5c3b;
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 3px 0;
            color: #3d5a4a;
        }
        .header .periode {
            background: #d4edda;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #d4ddd4;
            text-align: left;
        }
        th {
            background: #f0faf4;
            font-weight: 700;
            color: #0d5c3b;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge-mutu {
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 10px;
        }
        .badge-a { background: #d4edda; color: #0d5c3b; }
        .badge-b { background: #cce5ff; color: #004085; }
        .badge-c { background: #fff3cd; color: #856404; }
        .badge-d { background: #f8d7da; color: #721c24; }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #d4ddd4;
            color: #3d5a4a;
            font-size: 9px;
        }
        .summary {
            margin-top: 20px;
            background: #f8faf8;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #c8a96e;
        }
        .summary table {
            border: none;
            margin: 0;
        }
        .summary td {
            border: none;
            padding: 5px 10px;
        }
        .gold-text {
            color: #c8a96e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Survei Kepuasan Masyarakat</h2>
        <p><strong>Kabupaten Sumenep</strong></p>
        <p>Laporan Periode: <span class="periode">{{ $periode->nama }}</span></p>
        <p style="font-size:10px; color:#3d5a4a;">
            Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    @if($data->isEmpty())
        <div class="text-center" style="padding:40px 0; color:#3d5a4a;">
            <p><strong>Belum ada data survei pada periode ini.</strong></p>
        </div>
    @else
        <!-- IKM Per Unit -->
        <h4 style="color: #0d5c3b; margin-top: 20px;">Rekapitulasi IKM Per Unit Pelayanan</h4>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unit Pelayanan</th>
                    <th class="text-center">Jumlah Survei</th>
                    <th class="text-center">IKM</th>
                    <th class="text-center">Mutu</th>
                    <th class="text-center">Kinerja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ikmPerUnit as $index => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item['unit']->nama }}</td>
                    <td class="text-center">{{ $item['total_survei'] }}</td>
                    <td class="text-center"><strong>{{ $item['ikm'] }}</strong></td>
                    <td class="text-center">
                        <span class="badge-mutu badge-{{ strtolower($item['mutu']['mutu']) }}">
                            {{ $item['mutu']['mutu'] }}
                        </span>
                    </td>
                    <td>{{ $item['mutu']['kinerja'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <table>
                <tr>
                    <td><strong>Total Unit</strong></td>
                    <td>: {{ $ikmPerUnit->count() }}</td>
                    <td style="width:50px;"></td>
                    <td><strong>Total Survei</strong></td>
                    <td>: {{ $data->count() }}</td>
                </tr>
                <tr>
                    <td><strong>Unit dengan Mutu A</strong></td>
                    <td>: {{ $ikmPerUnit->where('mutu.mutu', 'A')->count() }}</td>
                    <td></td>
                    <td><strong>Unit dengan Mutu B</strong></td>
                    <td>: {{ $ikmPerUnit->where('mutu.mutu', 'B')->count() }}</td>
                </tr>
                <tr>
                    <td><strong>Unit dengan Mutu C</strong></td>
                    <td>: {{ $ikmPerUnit->where('mutu.mutu', 'C')->count() }}</td>
                    <td></td>
                    <td><strong>Unit dengan Mutu D</strong></td>
                    <td>: {{ $ikmPerUnit->where('mutu.mutu', 'D')->count() }}</td>
                </tr>
            </table>
        </div>

        <!-- Detail Data -->
        <h4 style="color: #0d5c3b; margin-top: 25px;">Detail Data Survei</h4>
        <table style="font-size: 9px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Unit</th>
                    <th>Layanan</th>
                    <th class="text-center">IKM</th>
                    <th class="text-center">Mutu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $survei)
                @php
                    $rata = $survei->jawaban->avg(function($j) {
                        return $j->opsiJawabanUnsur->nilai ?? 0;
                    });
                    $ikm = $rata ? ($rata / 9) * 25 : 0;
                    $mutu = $ikm >= 88.31 ? 'A' : ($ikm >= 76.61 ? 'B' : ($ikm >= 65.00 ? 'C' : 'D'));
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $survei->nik }}</td>
                    <td>{{ $survei->nama ?? '-' }}</td>
                    <td>{{ $survei->unitPelayanan->nama ?? '-' }}</td>
                    <td>{{ $survei->layanan->nama ?? '-' }}</td>
                    <td class="text-center">{{ number_format($ikm, 2) }}</td>
                    <td class="text-center">
                        <span class="badge-mutu badge-{{ strtolower($mutu) }}">
                            {{ $mutu }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>
            <i class="fas fa-leaf" style="color: #1a7a4e;"></i>
            Laporan ini dibuat secara otomatis oleh Sistem SKM Kabupaten Sumenep
            <i class="fas fa-leaf" style="color: #1a7a4e;"></i>
        </p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>