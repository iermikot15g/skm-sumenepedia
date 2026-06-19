@extends('admin.layouts.master')

@section('title', 'Detail Survei - SKM Sumenep')
@section('page-title', 'Detail Survei')

@section('content')
<div class="row g-4">
    <!-- Informasi Responden -->
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header">Informasi Responden</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>NIK</strong></td>
                        <td>{{ $survei->nik }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $survei->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>{{ $survei->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Usia</strong></td>
                        <td>{{ $survei->usia ?? '-' }} tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>{{ $survei->jenis_kelamin == 'L' ? 'Laki-laki' : ($survei->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan</strong></td>
                        <td>{{ $survei->pendidikan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pekerjaan</strong></td>
                        <td>{{ $survei->pekerjaan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Informasi Survei -->
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header">Informasi Survei</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Unit</strong></td>
                        <td>{{ $survei->unitPelayanan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Layanan</strong></td>
                        <td>{{ $survei->layanan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Periode</strong></td>
                        <td>{{ $survei->periode->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>{{ $survei->tanggal_survei->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>IKM</strong></td>
                        <td>
                            @php
                                $rata = $survei->jawaban->avg(function($j) {
                                    return $j->opsiJawabanUnsur->nilai ?? 0;
                                });
                                $ikm = $rata ? ($rata / 9) * 25 : 0;
                            @endphp
                            <strong>{{ number_format($ikm, 2) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Mutu</strong></td>
                        <td>
                            @php
                                $mutu = $ikm >= 88.31 ? 'A' : ($ikm >= 76.61 ? 'B' : ($ikm >= 65.00 ? 'C' : 'D'));
                            @endphp
                            <span class="badge badge-mutu badge-mutu-{{ strtolower($mutu) }}" style="font-size:1rem;">
                                {{ $mutu }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Jawaban -->
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">Detail Jawaban Per Unsur</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                                <td>
                                    <span class="badge 
                                        @if($j->opsiJawabanUnsur->nilai >= 4) bg-success
                                        @elseif($j->opsiJawabanUnsur->nilai >= 3) bg-info
                                        @elseif($j->opsiJawabanUnsur->nilai >= 2) bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ $j->opsiJawabanUnsur->nilai }}
                                    </span>
                                </td>
                                <td>{{ $j->opsiJawabanUnsur->label }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="col-12">
        <a href="{{ route('admin.data-survei') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.data-survei.export-pdf', $survei->id) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>
</div>
@endsection