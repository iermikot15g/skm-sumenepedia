<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Survei - {{ $survei->nik }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1a3a5c; }
        .header p { margin: 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f0f4f8; font-weight: 600; }
        .section-title { font-weight: 600; margin-top: 20px; margin-bottom: 10px; color: #1a3a5c; }
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fecaca; color: #991b1b; }
        .footer { text-align: center; margin-top: 30px; color: #9ca3af; font-size: 10px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Survei Kepuasan Masyarakat</h2>
        <p>Kabupaten Sumenep</p>
        <p><strong>{{ $survei->periode->nama ?? '-' }}</strong></p>
    </div>

    <div class="section-title">Informasi Responden</div>
    <table>
        <tr><td width="150"><strong>NIK</strong></td><td>{{ $survei->nik }}</td></tr>
        <tr><td><strong>Nama</strong></td><td>{{ $survei->nama ?? '-' }}</td></tr>
        <tr><td><strong>No HP</strong></td><td>{{ $survei->no_hp ?? '-' }}</td></tr>
        <tr><td><strong>Usia</strong></td><td>{{ $survei->usia ?? '-' }} tahun</td></tr>
        <tr><td><strong>Jenis Kelamin</strong></td><td>{{ $survei->jenis_kelamin == 'L' ? 'Laki-laki' : ($survei->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>
        <tr><td><strong>Pendidikan</strong></td><td>{{ $survei->pendidikan ?? '-' }}</td></tr>
        <tr><td><strong>Pekerjaan</strong></td><td>{{ $survei->pekerjaan ?? '-' }}</td></tr>
    </table>

    <div class="section-title">Informasi Survei</div>
    <table>
        <tr><td width="150"><strong>Unit</strong></td><td>{{ $survei->unitPelayanan->nama ?? '-' }}</td></tr>
        <tr><td><strong>Layanan</strong></td><td>{{ $survei->layanan->nama ?? '-' }}</td></tr>
        <tr><td><strong>Tanggal</strong></td><td>{{ $survei->tanggal_survei->format('d/m/Y H:i') }}</td></tr>
        @php
            $rata = $survei->jawaban->avg(function($j) { return $j->opsiJawabanUnsur->nilai ?? 0; });
            $ikm = $rata ? ($rata / 9) * 25 : 0;
            $mutu = $ikm >= 88.31 ? 'A' : ($ikm >= 76.61 ? 'B' : ($ikm >= 65.00 ? 'C' : 'D'));
        @endphp
        <tr><td><strong>IKM</strong></td><td>{{ number_format($ikm, 2) }}</td></tr>
        <tr><td><strong>Mutu</strong></td><td><span class="badge badge-{{ $mutu == 'A' ? 'success' : ($mutu == 'B' ? 'info' : ($mutu == 'C' ? 'warning' : 'danger')) }}">{{ $mutu }}</span></td></tr>
    </table>

    <div class="section-title">Detail Jawaban Per Unsur</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Unsur</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $jawaban = $survei->jawaban->sortBy(function($j) {
                    return $j->opsiJawabanUnsur->unsur_survei_id;
                });
            @endphp
            @foreach($jawaban as $index => $j)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $j->opsiJawabanUnsur->unsurSurvei->nama ?? 'Unsur ' . $loop->iteration }}</td>
                <td>{{ $j->opsiJawabanUnsur->nilai }}</td>
                <td>{{ $j->opsiJawabanUnsur->label }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} | SKM Kabupaten Sumenep
    </div>
</body>
</html>