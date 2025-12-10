@extends('cms.layouts.base')

@section('content')
    <style>
        .table tr:first-child th {
            border-bottom-width: 2px;
            border-bottom-color: currentColor;
        }

        select option:disabled {
            color: #999;
            background-color: #f0f0f0;
            font-style: italic;
        }
    </style>

    <section id="content" class="content">
        @if (empty(request()->get('is_modal')))
            <div class="content__header content__boxed overlapping">
                <div class="content__wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">HIMA</li>
                        </ol>
                    </nav>

                    <p class="lead">

                    </p>
                </div>
            </div>
        @endif

        @if (($canInsert && request()->get('act') == 'tambah') || (($canUpdate || $canRead) && request()->get('act') == 'edit'))
            @if (empty(request()->get('is_modal')))
                <div class="content__boxed">
                    <div class="content__wrap">
            @endif
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="formData">
                                @csrf
                                @if (request()->get('act') == 'edit')
                                    @method('put')
                                @endif
                                <div class="row gy-3">
                                    <div class="col-md-12">
                                        <div class="row gx-3 gy-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="periode" class="form-label">Periode
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control triggerBtn" id="periode"
                                                            name="periode" readonly
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}
                                                            value="{{ $nGetDataHima >= 1 ? $fGetDataHima->periode : $filter_periode }}">
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="namaunit" class="form-label">Unit
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="hidden" id="kodeunit" name="kodeunit"
                                                            value="{{ $kodeunit }}">
                                                        <input type="text" class="form-control triggerBtn" id="namaunit"
                                                            name="namaunit" readonly
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}
                                                            value="{{ $namaunit }}">
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_ketua" class="form-label">Ketua
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="hidden" id="nim_ketua" name="nim_ketua"
                                                            value="{{ $nGetDataHima >= 1 ? $fGetDataHima->nim_ketua : '' }}">
                                                        <input type="text" class="form-control triggerBtn"
                                                            id="nama_ketua" name="nama_ketua"
                                                            value="{{ $nGetDataHima >= 1 && $fGetDataHima->mahasiswa_ketua ? $fGetDataHima->mahasiswa_ketua->nama : '' }}"
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}>
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_wakil" class="form-label">Wakil
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="hidden" id="nim_wakil" name="nim_wakil"
                                                            value="{{ $nGetDataHima >= 1 ? $fGetDataHima->nim_wakil : '' }}">
                                                        <input type="text" class="form-control triggerBtn"
                                                            id="nama_wakil" name="nama_wakil"
                                                            value="{{ $nGetDataHima >= 1 && $fGetDataHima->mahasiswa_wakil ? $fGetDataHima->mahasiswa_wakil->nama : '' }}"
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}>
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_ketua" class="form-label">Foto Ketua</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control triggerBtn"
                                                            id="foto_ketua" name="foto_ketua"
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}
                                                            accept="image/jpeg, image/png">
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                    @if ($nGetDataHima >= 1 && !empty($fGetDataHima['foto_ketua']))
                                                        <div style="margin-top: .75rem;">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <a href="{{ asset('images/foto/' . $fGetDataHima['foto_ketua']) }}"
                                                                        target="_blank" style="text-decoration: underline;">
                                                                        <img src="{{ asset('images/foto/' . $fGetDataHima['foto_ketua']) }}"
                                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto_wakil" class="form-label">Foto Wakil</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control triggerBtn"
                                                            id="foto_wakil" name="foto_wakil"
                                                            {{ $canInsert || $canUpdate ? '' : 'disabled' }}
                                                            accept="image/jpeg, image/png">
                                                        <span class="input-group-text text-danger">*</span>
                                                    </div>
                                                    @if ($nGetDataHima >= 1 && !empty($fGetDataHima['foto_wakil']))
                                                        <div style="margin-top: .75rem;">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <a href="{{ asset('images/foto/' . $fGetDataHima['foto_wakil']) }}"
                                                                        target="_blank"
                                                                        style="text-decoration: underline;">
                                                                        <img src="{{ asset('images/foto/' . $fGetDataHima['foto_wakil']) }}"
                                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    @if ($canInsert || $canUpdate)
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="Simpan Data" id="btnToTrigger"
                                            onclick="doSubmitForm(this)">Simpan</button>
                                    @endif
                                    <a @if (empty(request()->get('is_modal')) || (!empty(request()->get('is_lookup')) && request()->get('act') == 'tambah')) href="{!! url(
                                        $currentRoute .
                                            "?filter_periode={$filter_periode}" .
                                            "&filter_unit={$kodeunit}" .
                                            (!empty(request()->get('is_lookup')) ? '&is_lookup=1&is_modal=1' : ''),
                                    ) !!}" @else data-bs-dismiss="modal" aria-label="Close" @endif
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Batal"
                                        class="btn btn-danger">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if (empty(request()->get('is_modal')))
                </div>
                </div>
            @endif

            <script>
                $(document).ready(function() {
                    $("#nama_ketua").autocomplete({
                            source: "{{ route('master-hima.get-mahasiswa', ['kodeunit' => $kodeunit]) }}",
                            minLength: 2,
                            select: function(event, ui) {
                                $("#nama_ketua").val(ui.item.label);
                                $("#nim_ketua").val(ui.item.nim);
                                return false; // mencegah autocomplete otomatis isi value lain
                            }
                        })
                        .data("ui-autocomplete")._renderItem = function(ul, item) {
                            return $("<li class='p-2'>")
                                .append(`
                                    <div class="row">
                                        <div class="col-auto">
                                            ${item.nim}
                                        </div>
                                        <div class="col">
                                            ${item.label}
                                        </div>
                                        <div class="col-auto">
                                            ${item.prodi}
                                        </div>
                                    </div>
                                `)
                                .appendTo(ul);
                        };

                    $("#nama_wakil").autocomplete({
                            source: "{{ route('master-hima.get-mahasiswa', ['kodeunit' => $kodeunit]) }}",
                            minLength: 2,
                            select: function(event, ui) {
                                $("#nama_wakil").val(ui.item.label);
                                $("#nim_wakil").val(ui.item.nim);
                                return false; // mencegah autocomplete otomatis isi value lain
                            }
                        })
                        .data("ui-autocomplete")._renderItem = function(ul, item) {
                            return $("<li class='p-2'>")
                                .append(`
                                    <div class="row">
                                        <div class="col-auto">
                                            ${item.nim}
                                        </div>
                                        <div class="col">
                                            ${item.label}
                                        </div>
                                        <div class="col-auto">
                                            ${item.prodi}
                                        </div>
                                    </div>
                                `)
                                .appendTo(ul);
                        };
                });
            </script>

            <script>
                @if (!empty(request()->get('is_modal')))
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                @endif

                $('input.triggerBtn').keypress(function(e) {
                    var code = e.keyCode || e.which;
                    if (code === 13) {
                        e.preventDefault();
                        document.getElementById("btnToTrigger").click();
                    }
                });

                @if ($canInsert || $canUpdate)

                    function doSubmitForm(doc_id) {

                        doc_id.disabled = true;

                        Swal.fire({
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });

                        @if ($nGetDataHima >= 1)
                            var id_hima = '{{ $id_hima }}';
                            var url = "{{ url($currentRoute) . '/update/' . $id_hima }}";
                            var method = "put";
                        @else
                            var url = "{{ url($currentRoute) . '/add' }}";
                            var method = 'post';
                        @endif

                        var form = document.querySelector('form#formData');
                        var formData = new FormData(form);

                        @if ($nGetDataHima >= 1)
                            formData.append('id_hima', id_hima);
                        @endif

                        fetch(url, {
                                method: 'post',
                                body: formData
                            })
                            .then(response => response.json())
                            .then((data) => {

                                if (data.msg_resp != 'error') {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Data Berhasil Disimpan'
                                    }).then(function(result) {

                                        @if ($nGetDataHima >= 1)

                                            window.location.reload();
                                        @else

                                            window.location.href =
                                                '{!! url($currentRoute . "?filter_periode={$filter_periode}" . "&filter_unit={$kodeunit}") !!}';
                                        @endif
                                    });
                                } else {

                                    Swal.close();
                                    doc_id.disabled = false;
                                    showToastr(data.msg_resp, data.msg_desc);
                                }
                            });
                    }
                @endif
            </script>
        @else
            @php
            @endphp
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
                                                @if ($canInsert)
                                                    <div class="col-auto col-md-auto">
                                                        <a href="{{ url($currentRoute) . "?filter_periode={$filter_periode}&filter_unit={$filter_unit}&act=tambah" }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-title="Tambah Data" class="btn btn-primary btn-sm"><i
                                                                class="fa fa-plus me-1"></i> Tambah</a>
                                                    </div>
                                                @endif
                                                <div class="col-sm-auto">
                                                    <select class="form-control form-control-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-title="Filter Periode"
                                                        id="filter_periode" name="filter_periode" onchange="submit()">
                                                        @foreach ($fGetPeriode as $item)
                                                            <option value="{{ $item->periode_akademik }}"
                                                                {{ $filter_periode == $item->periode_akademik ? 'selected' : '' }}>
                                                                {{ 'Periode' . ' ' . $item->periode_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <select class="form-control form-control-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-title="Filter Unit"
                                                        id="filter_unit" name="filter_unit" onchange="submit()">
                                                        @foreach ($fGetUnit as $item)
                                                            <option value="{{ $item->kodeunit }}"
                                                                {{ $item->level == '2' || $item->kodeunit == '1040' ? '' : 'disabled' }}
                                                                {{ in_array($item->kodeunit, ['1041', '1042', '1043']) ? 'disabled' : '' }}
                                                                {{ $filter_unit == $item->kodeunit ? 'selected' : '' }}>
                                                                {{ $item->namaunit }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control form-control-sm triggerBtn"
                                                        placeholder="Pencarian HIMA" name="filter_keyword"
                                                        value="{{ request()->get('filter_keyword') }}">
                                                </div>
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
                                                    <th style="width: 3%;" rowspan="2">No.</th>
                                                    <th style="width: 40%;" class="text-center" colspan="3">Ketua</th>
                                                    <th style="width: 40%;" class="text-center" colspan="3">Wakil
                                                        Ketua</th>
                                                    @if ($canSetAktif || $canDelete)
                                                        <th style="width: 10%;" rowspan="2"></th>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th style="width: 5%;">NIM</th>
                                                    <th style="width: 18%;">Nama</th>
                                                    <th style="width: 12%;">Unit</th>
                                                    <th style="width: 5%;">NIM</th>
                                                    <th style="width: 18%;">Nama</th>
                                                    <th style="width: 12%;">Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($fGetDataHima as $item)
                                                    <tr>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {{ $posisiHalaman + $loop->iteration }}.
                                                        </td>
                                                        {{-- <td class="align-middle">
                                                            <img src="{{ asset('images/foto/' . $item->foto_ketua) }}"
                                                                style="max-width: 200px;"><br><b>File Hima</b>: <a
                                                                href="{{ asset('images/foto/' . $item->foto_ketua) }}"
                                                                class="text-primary" target="_blank">
                                                                {!! Helper::highLight($item->foto_ketua, request()->get('filter_keyword')) !!}
                                                            </a>
                                                        </td> --}}
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {!! Helper::highLight($item->nim_ketua, $filter_keyword) !!}
                                                        </td>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {!! Helper::highLight(optional($item->mahasiswa_ketua)->nama ?? '', $filter_keyword) !!}
                                                        </td>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {{ optional(optional($item->mahasiswa_ketua)->prodi)->namaunit ?? '' }}
                                                        </td>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {!! Helper::highLight($item->nim_wakil, $filter_keyword) !!}
                                                        </td>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {!! Helper::highLight(optional($item->mahasiswa_wakil)->nama ?? '', $filter_keyword) !!}
                                                        </td>
                                                        <td class="align-middle" style="cursor: pointer;"
                                                            onclick="openModal('Edit HIMA', '{{ $item->id }}')">
                                                            {{ optional(optional($item->mahasiswa_wakil)->prodi)->namaunit ?? '' }}
                                                        </td>
                                                        <td class="align-middle text-nowrap">
                                                            @if ($canDelete)
                                                                <button class="btn btn-danger btn-xs"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title="Hapus Data" style="min-width: 25px;"
                                                                    onclick="doDelete('{{ $item->id }}')">
                                                                    <i class="fa fa-trash"></i>
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

            <div class="modal fade" id="modal">
                <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
                    <div class="modal-content" style="background-color: #eee;">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="modalTitle"></h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                aria-label="Close"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="modal-body" id="modalBody" style="margin: 1rem; padding: 0;">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalConstraint">
                <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
                    <div class="modal-content" style="background-color: #eee;">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="modalTitleConstraint"></h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                aria-label="Close"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="modal-body" id="modalBodyConstraint" style="margin: 1rem; padding: 0;">
                            <iframe allowtransparency="true" id="frameModalConstraint"
                                style="width: 100%; min-height: 60vh; border: 0px;"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $('input.triggerBtn').keypress(function(e) {
                    var code = e.keyCode || e.which;
                    if (code === 13) {
                        e.preventDefault();
                        document.getElementById('form_filter').submit();
                    }
                });

                function openModal(title, id) {

                    var modal = new bootstrap.Modal(document.getElementById('modal'), {

                    });

                    document.getElementById('modalTitle').innerHTML = title;

                    $('#modalBody').load(
                        '{!! url($currentRoute . '?act=edit' . '&is_modal=1&id=') !!}' + id
                    );

                    modal.show();

                    var modalContainerEl = document.getElementById('modal')
                    modalContainerEl.addEventListener('hidden.bs.modal', function(event) {
                        $('#modalBody').empty();
                    });
                }

                // function openModalConstraint(id) {

                //     $(".tooltip").tooltip("hide");

                //     var modal = new bootstrap.Modal(document.getElementById('modalConstraint'), {

                //     });

                //     document.getElementById('modalTitleConstraint').innerHTML = 'Constraint';

                //     $('#frameModalConstraint').attr('src', '{{ url($currentRoute) . '/get-constraint/' }}' + id + '?no-menu=1');

                //     modal.show();

                //     var modalContainerEl = document.getElementById('modalConstraint')
                //     modalContainerEl.addEventListener('hidden.bs.modal', function(event) {
                //         if (event.target.id == 'modalConstraint') {

                //             $('#frameModalConstraint').removeAttr('src');
                //         }
                //     });
                // }
                function openModalConstraint(id) {

                    var modal = new bootstrap.Modal(document.getElementById('modal'), {

                    });

                    document.getElementById('modalTitle').innerHTML = "Constraint";

                    $('#modalBody').load(
                        '{{ url($currentRoute) . '/get-constraint/' }}' + id
                    );

                    modal.show();

                    var modalContainerEl = document.getElementById('modal')
                    modalContainerEl.addEventListener('hidden.bs.modal', function(event) {
                        $('#modalBody').empty();
                    });
                }

                @if ($canDelete)

                    function doDelete(id) {

                        Swal.fire({
                            text: 'Apakah Anda yakin ingin menghapus Data ini?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#1f3574',
                            cancelButtonColor: '#b62b39',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.value) {

                                fetch("{{ url($currentRoute) . '/delete/' }}" + id, {
                                        method: "delete",
                                        body: new URLSearchParams({
                                            _token: '{{ csrf_token() }}',
                                            _method: 'delete',
                                        })
                                    })
                                    .then(response => response.json())
                                    .then((data) => {

                                        if (data.msg_resp != 'error') {

                                            window.location.reload();
                                        } else {

                                            showToastr(data.msg_resp, data.msg_desc);

                                            if (data.constraint == "1") {
                                                openModalConstraint(id);
                                            }
                                        }
                                    });
                            }
                        });
                    }
                @endif
            </script>
        @endif
    </section>
@endsection
