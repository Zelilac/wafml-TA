@extends('cms.layouts.base')

@section('content')
    <section id="content" class="content">
        @if (empty(request()->get('is_modal')))
            <div class="content__header content__boxed overlapping">
                <div class="content__wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        </ol>
                    </nav>

                    <p class="lead">

                    </p>
                </div>
            </div>
        @endif

        <div class="content__boxed">
            <div class="content__wrap">
                <div class="row gy-3">
                    <div class="col-lg-12">
                        <form action="{{ url('dashboard') }}" method="get" id="form_filter">
                            <div class="row">
                                <div class="col-lg-auto">
                                    <select class="form-control form-control-sm" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Filter Periode" id="filter_periode"
                                        name="filter_periode" onchange="submit()">
                                        @foreach ($fGetPeriode as $item)
                                            <option value="{{ $item->periode_akademik }}"
                                                {{ $filter_periode == $item->periode_akademik ? 'selected' : '' }}>
                                                {{ 'Periode' . ' ' . $item->periode_akademik_2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-12">
                        <div class="row gy-3">
                            @php
                                $bg_colors = ['bg-info', 'bg-success', 'bg-danger'];
                            @endphp
                            @php
                                $nama_total = ['Pemilih', 'yang Sudah Memilih', 'yang Belum Memilih'];
                            @endphp
                            @foreach ($nama_total as $item)
                                @php
                                    $totalMahasiswa = '';
                                    switch ($loop->index) {
                                        case 0:
                                            $totalMahasiswa = $qGetMahasiswa->count();
                                            break;
                                        case 1:
                                            $totalMahasiswa = $qGetMahasiswa
                                                ->whereHas('votes', function ($q) use ($filter_periode) {
                                                    $filter_periode = str_replace('-', '/', $filter_periode);
                                                    $q->where('periode', $filter_periode);
                                                })
                                                ->count();
                                            break;
                                        case 2:
                                            $totalMahasiswa = $qGetMahasiswa
                                                ->whereDoesntHave('votes', function ($q) use ($filter_periode) {
                                                    $filter_periode = str_replace('-', '/', $filter_periode);
                                                    $q->where('periode', $filter_periode);
                                                })
                                                ->count();
                                            break;
                                        default:
                                            $totalMahasiswa = $qGetMahasiswa->count();
                                            break;
                                    }
                                @endphp
                                <div class="col-sm-4">
                                    <div
                                        class="card {{ $bg_colors[$loop->index % count($bg_colors)] }} text-white p-3 h-100">
                                        <div class="row gx-2 mb-3">
                                            <div class="col-auto">
                                                <h5 class="">
                                                    <i class="demo-psi-file-edit text-white text-opacity-75 fs-3"></i>
                                                </h5>
                                            </div>
                                            <div class="col">
                                                <h5 class="">
                                                    Total {{ $item }}
                                                </h5>
                                            </div>
                                        </div>

                                        <ul class="list-group list-group-borderless">
                                            <li
                                                class="list-group-item p-0 text-white d-flex justify-content-between align-items-start">
                                                <div class="me-auto">Jumlah</div>
                                                <span class="fw-bold">{{ $totalMahasiswa }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <div class="row gy-2 gx-2">
                                        <div class="col-sm-auto my-auto">
                                            <h5 class="mb-4">Pemilih Presiden BEM</h5>
                                        </div>
                                    </div>

                                    <canvas id="chartBarPresidenBem" height="115"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <div class="row gy-2 gx-2">
                                        <div class="col-sm-auto my-auto">
                                            <h5 class="mb-4">Pemilih HIMA</h5>
                                        </div>
                                    </div>

                                    <canvas id="chartBarHima" height="115"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('dashboard.chart-bar-presiden-bem', ['filter_periode' => $filter_periode]) }}", {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    var name = data.map(x => x.name);
                    var jumlah = data.map(x => x.jumlah);

                    const ctxBarPresidenBem = document.getElementById('chartBarPresidenBem').getContext(
                        '2d');
                    const chartBarPresidenBem = new Chart(ctxBarPresidenBem, {
                        type: 'bar',
                        data: {
                            labels: name,
                            datasets: [{
                                data: jumlah,
                                backgroundColor: 'rgba(37, 71, 106, 0.9)',
                                borderColor: 'rgba(37, 71, 106, 1)',
                            }]
                        },
                        plugins: [ChartDataLabels],
                        options: {
                            plugins: {
                                legend: {
                                    display: false,
                                    position: 'bottom',
                                    align: 'left'
                                },
                                datalabels: {
                                    color: '#fff'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                });

            fetch("{{ route('dashboard.chart-bar-hima', ['filter_periode' => $filter_periode]) }}", {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    var name = data.map(x => x.name);
                    var jumlah = data.map(x => x.jumlah);

                    const ctxBarHima = document.getElementById('chartBarHima').getContext(
                        '2d');
                    const chartBarHima = new Chart(ctxBarHima, {
                        type: 'bar',
                        data: {
                            labels: name,
                            datasets: [{
                                data: jumlah,
                                backgroundColor: 'rgba(37, 71, 106, 0.9)',
                                borderColor: 'rgba(37, 71, 106, 1)',
                            }]
                        },
                        plugins: [ChartDataLabels],
                        options: {
                            plugins: {
                                legend: {
                                    display: false,
                                    position: 'bottom',
                                    align: 'left'
                                },
                                datalabels: {
                                    color: '#fff'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                });
        });
    </script>
@endsection
