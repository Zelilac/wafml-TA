@extends('cms.layouts.base')

@section('content')
    <style>
        .table tr:first-child th {
            border-bottom-width: 2px;
            border-bottom-color: currentColor;
        }
    </style>

    <section id="content" class="content">
        @if (empty(request()->get('is_modal')))
            <div class="content__header content__boxed overlapping">
                <div class="content__wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tahun Periode</li>
                        </ol>
                    </nav>

                    <p class="lead">

                    </p>
                </div>
            </div>
        @endif

        <div class="content__boxed">
            <div class="content__wrap">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <form action="{{ url($currentRoute) }}" method="get" id="form_filter">
                                        @if (!empty(request()->get('is_lookup')))
                                            <input type="hidden" name="is_lookup" value="1">
                                            <input type="hidden" name="is_modal" value="1">
                                        @endif
                                        <div class="row gy-2 gx-2">
                                            <div class="col-auto col-md-auto">
                                                <a href="{{ url($currentRoute) }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Refresh Data"
                                                    class="btn btn-light btn-sm"><i class="fa fa-sync me-1"></i>
                                                    Refresh</a>
                                            </div>
                                            {{-- <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm triggerBtn"
                                                    placeholder="Pencarian Tahun Periode" name="filter_keyword"
                                                    value="{{ request()->get('filter_keyword') }}">
                                            </div> --}}
                                            <div class="col-auto mt-2 my-md-auto ms-auto">
                                                Total Data: {{ $jumlahData }}
                                            </div>
                                        </div>
                                        {{-- <div class="row gy-2 gx-2 mt-3">
                                            </div> --}}
                                    </form>
                                </div>

                                <div class="table-responsive text-nowrap">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 3%;">No.</th>
                                                <th>Tahun Periode</th>
                                                <th style="width: 10%;" class="text-center">Aktif</th>
                                                @if ($canSetAktif)
                                                    <th style="width: 10%;"></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fGetDataPeriode as $item)
                                                <tr>
                                                    <td class="align-middle">
                                                        {{ $posisiHalaman + $loop->iteration }}.
                                                    </td>
                                                    <td class="align-middle">
                                                        {{ $item->periode_akademik }}
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        {!! Helper::checkAktif(e($item->aktif_pemilih)) !!}
                                                    </td>
                                                    <td class="align-middle text-nowrap">
                                                        @if ($canSetAktif)
                                                            <button type="button" class="btn btn-success btn-xs me-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-title="Set Status Data" style="min-width: 25px;"
                                                                onclick="doSetAktif('{{ $item->min_periode }}')">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            @if ($canSetAktif)
                function doSetAktif(periode) {

                    fetch("{{ url($currentRoute) . '/set' . '/' }}" + periode, {
                            method: "post",
                            body: new URLSearchParams({
                                _token: '{{ csrf_token() }}',
                                _method: 'put',
                            })
                        })
                        .then(response => response.json())
                        .then((data) => {

                            if (data.msg_resp != 'error') {

                                window.location.reload();
                            } else {

                                showToastr(data.msg_resp, data.msg_desc);
                            }
                        });
                }
            @endif
        </script>
    </section>
@endsection
