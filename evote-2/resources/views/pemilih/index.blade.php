@extends('pemilih.layouts.base')

@section('content')
    <style>
        .custom-progress-step {
            position: relative;
            /* align-items: center; */
        }

        .custom-progress-step .row {
            position: relative;
        }

        .custom-progress-step .row::after {
            content: "";
            position: absolute;
            top: 30%;
            right: -50%;
            width: 100%;
            z-index: 1;
            height: 2px;
            background-color: #ccc;
            transform: translateY(-50%);
        }

        /* Hilangkan garis di elemen terakhir */
        .custom-progress-step .row:last-child::after {
            display: none;
        }

        @media (max-width: 991px) {
            .custom-progress-step .row::after {
                top: 20%;
            }

            .custom-progress-step .col.text-center {
                margin-top: .2rem !important;
                font-size: .75em;
            }
        }
    </style>

    <style>
        .candidate-list {
            display: block;
        }

        .candidate-card {
            cursor: pointer;
            display: block;
            position: relative;
            margin-bottom: 2rem;
        }

        .candidate-card input[type="radio"] {
            display: none;
        }

        .candidate-card .candidate-number {
            position: absolute;
            left: 1rem;
            top: 1rem;
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #ccc;
            background: #000;
            color: #fff;
            font-size: 2.5rem;
            font-family: impact;
        }

        .candidate-card .card-content {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            transition: 0.2s ease;
            background: #fff;
        }

        .candidate-card .card-content .candidate-img {
            width: 10rem;
            max-width: 100%;
            height: 10rem;
            border-radius: 10px;
            border: 1px solid #ccc;
            object-fit: cover;
            display: flex;
            margin-left: auto;
            margin-right: auto;
        }

        .candidate-card .card-content p {
            margin-bottom: 0;
            font-size: .9rem;
            color: #22272b;
        }

        .candidate-card .candidate-checked {
            display: none;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 8rem;
            height: 8rem;
            /* display: flex; */
            align-items: center;
            justify-content: center;
        }

        .candidate-card:hover .card-content {
            background-color: #f5f5f5;
        }

        .candidate-card input[type="radio"]:checked+.card-content {
            /* border-color: #3950a2; */
            /* box-shadow: 0px 0px 8px rgba(78, 142, 247, 0.5); */
            /* background-color: #3950a2; */
            background-color: #D0E5F7;
        }

        .candidate-card input[type="radio"]:checked+.card-content .candidate-checked {
            display: flex;
        }

        .candidate-card input[type="radio"]:checked+.card-content p {
            /* color: #fff; */
            color: #22272b;
        }

        @media (max-width: 991px) {
            .candidate-card .candidate-number {
                left: 0.5rem;
                top: 0.5rem;
                width: 3rem;
                height: 3rem;
                font-size: 1.5rem;
            }

            .candidate-card .candidate-checked {
                left: unset;
                right: 0;
                top: 0;
                transform: unset;
                width: 3.5rem;
                height: 3.5rem;
            }
        }
    </style>

    <div class="container-fluid px-1 px-md-4 pt-0 pt-sm-5 pb-5 mx-auto">
        <div class="row g-0 justify-content-center mt-3 mt-lg-5">
            <div class="col-12 col-sm-12 col-lg-10 col-xl-10 col-xxl-9">
                <ul class="nav nav-pills nav-pills-style-1 mt-2 d-none d-lg-block" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="pills-form-tab nav-link-page nav-link active" id="pills-form-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-form" type="button" role="tab"
                            aria-controls="pills-form" aria-selected="true"><i class="fa fa-edit me-1"></i>
                            eVote</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-form" role="tabpanel" aria-labelledby="pills-form-tab">
                        <div class="row g-0 box-shadow-custom">
                            <div class="col-sm-auto">
                                <div class="card border-0 h-100 text-white card-custom-4"
                                    style="background-color: #f5f5f5;">
                                    <div class="card-body px-lg-4 py-lg-3">
                                        {{-- <h5 class="mt-4">Proses</h5> --}}
                                        {{-- <h5 class="mt-0" style="color: #F2C718;">Langkah:</h5> --}}

                                        <div class="card1 mt-lg-5">
                                            <ul id="progressbar" class="text-center">
                                                <li id="step-1-li" class="step0 step0-trigger active"></li>
                                                <li id="step-2-li" class="step0 step0-trigger">
                                                </li>
                                                @if (!$fGetVote)
                                                    <li id="step-3-li" class="step0 step0-trigger">
                                                    </li>
                                                @endif
                                            </ul>
                                            <h6 id="step-1-h6" class="step-text step-text-trigger"
                                                style="padding-top: .2rem;">
                                                <span class="step-title">Pilih Presiden BEM</span><br>
                                                <span class="d-none d-lg-block" style="font-size: 1rem;"><span
                                                        class="fw-normal" style="font-size: 75%;">&nbsp;</span></span>
                                            </h6>
                                            <h6 id="step-2-h6" class="step-text step-text-trigger"
                                                style="padding-top: .2rem;">
                                                <span class="step-title">Pilih HIMA</span><br>
                                                <span class="d-none d-lg-block" style="font-size: 1rem;"><span
                                                        class="fw-normal" style="font-size: 75%;">&nbsp;</span></span>
                                            </h6>
                                            @if (!$fGetVote)
                                                <h6 id="step-3-h6" class="step-text step-text-trigger"
                                                    style="padding-top: .2rem;">
                                                    <span class="step-title">Konfirmasi</span><br>
                                                    <span class="d-none d-lg-block" style="font-size: 1rem;"><span
                                                            class="fw-normal" style="font-size: 75%;">&nbsp;</span></span>
                                                </h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="card border-0 h-100 card-custom-3">
                                    <div class="card-body">
                                        <form id="formData" class="">
                                            @csrf
                                            <div class="card2 first-screen show" id="step-1-div">
                                                @if (!$fGetPresidenBem)
                                                    <p>Tidak Ada Data</p>
                                                @endif
                                                <div class="candidate-list">
                                                    @foreach ($fGetPresidenBem as $item)
                                                        <label class="candidate-card">
                                                            <input type="radio" name="id_ms_presiden_bem" required
                                                                @if ($fGetVote) {{ $fGetVote->id_ms_presiden_bem == $item->id ? 'checked' : '' }} disabled @endif
                                                                value="{{ $item->id }}">
                                                            <div class="card-content position-relative">
                                                                <div class="candidate-number">
                                                                    {{ $loop->iteration }}
                                                                </div>

                                                                <div class="candidate-checked">
                                                                    <img src="{{ asset('images/checked.png') }}"
                                                                        style="width: 100%; height: auto;">
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6 text-center">
                                                                        <p class="fw-bold mb-3">Ketua</p>

                                                                        <img src="{{ asset('images/foto/' . $item->foto_ketua) }}"
                                                                            class="candidate-img"
                                                                            alt="Ketua {{ $loop->iteration }}">

                                                                        <div class="d-flex justify-content-center mt-3"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ $item->nim_ketua }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional($item->mahasiswa_ketua)->nama ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ optional(optional($item->mahasiswa_ketua)->prodi)->namaunit ?? '' }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional(optional(optional($item->mahasiswa_ketua)->prodi)->parent)->namaunit ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-center">
                                                                        <p class="fw-bold mb-3">Wakil Ketua</p>

                                                                        <img src="{{ asset('images/foto/' . $item->foto_wakil) }}"
                                                                            class="candidate-img"
                                                                            alt="Wakil {{ $loop->iteration }}">

                                                                        <div class="d-flex justify-content-center mt-3"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ $item->nim_wakil }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional($item->mahasiswa_wakil)->nama ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ optional(optional($item->mahasiswa_wakil)->prodi)->namaunit ?? '' }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional(optional(optional($item->mahasiswa_wakil)->prodi)->parent)->namaunit ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-auto my-auto ms-auto">
                                                        <button type="button"
                                                            class="btn btn-custom-3 next-button text-center"
                                                            style="font-size: .75rem; padding: .5rem 1rem; min-width: 113.41px;">
                                                            Selanjutnya
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card2" id="step-2-div">
                                                @if (!$fGetHima)
                                                    <p>Tidak Ada Data</p>
                                                @endif
                                                <div class="candidate-list">
                                                    @foreach ($fGetHima as $item)
                                                        <label class="candidate-card">
                                                            <input type="radio" name="id_ms_hima" required
                                                                @if ($fGetVote) {{ $fGetVote->id_ms_hima == $item->id ? 'checked' : '' }} disabled @endif
                                                                value="{{ $item->id }}">
                                                            <div class="card-content position-relative">
                                                                <div class="candidate-number">
                                                                    {{ $loop->iteration }}
                                                                </div>

                                                                <div class="candidate-checked">
                                                                    <img src="{{ asset('images/checked.png') }}"
                                                                        style="width: 100%; height: auto;">
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6 text-center">
                                                                        <p class="fw-bold mb-3">Ketua</p>

                                                                        <img src="{{ asset('images/foto/' . $item->foto_ketua) }}"
                                                                            class="candidate-img"
                                                                            alt="Ketua {{ $loop->iteration }}">

                                                                        <div class="d-flex justify-content-center mt-3"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ $item->nim_ketua }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional($item->mahasiswa_ketua)->nama ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ optional(optional($item->mahasiswa_ketua)->prodi)->namaunit ?? '' }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional(optional(optional($item->mahasiswa_ketua)->prodi)->parent)->namaunit ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 text-center">
                                                                        <p class="fw-bold mb-3">Wakil Ketua</p>

                                                                        <img src="{{ asset('images/foto/' . $item->foto_wakil) }}"
                                                                            class="candidate-img"
                                                                            alt="Wakil {{ $loop->iteration }}">

                                                                        <div class="d-flex justify-content-center mt-3"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ $item->nim_wakil }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional($item->mahasiswa_wakil)->nama ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center"
                                                                            style="gap: .25rem">
                                                                            <p>
                                                                                {{ optional(optional($item->mahasiswa_wakil)->prodi)->namaunit ?? '' }}
                                                                            </p>
                                                                            <p>
                                                                                |
                                                                            </p>
                                                                            <p>
                                                                                {{ optional(optional(optional($item->mahasiswa_wakil)->prodi)->parent)->namaunit ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-auto my-auto">
                                                        <button type="button"
                                                            class="btn btn-secondary prev-button text-center"
                                                            style="font-size: .75rem; padding: .5rem 1rem; min-width: 113.41px;">
                                                            Sebelumnya
                                                        </button>
                                                    </div>
                                                    @if (!$fGetVote)
                                                        <div class="col-auto my-auto ms-auto">
                                                            <button type="button" id="step-3-selanjutnya"
                                                                class="btn btn-custom-3 next-button text-center"
                                                                style="font-size: .75rem; padding: .5rem 1rem;">
                                                                Selanjutnya
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (!$fGetVote)
                                                <div class="card2" id="step-3-div">
                                                    <div class="mt-3 pb-3">
                                                        <div class="mb-4">
                                                            <div class="d-flex">
                                                                <i class="fa fa-info me-2"></i>
                                                                <span style="color: red; font-size: 75%;">Mohon periksa
                                                                    kembali pilihan Anda dan pastikan bahwa pilihan tersebut
                                                                    sudah benar dan sesuai.</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="cb-persetujuan"
                                                                onclick="checkPersetujuan(this)">
                                                            <label class="form-check-label" for="cb-persetujuan"
                                                                style="font-size: 75%;">
                                                                Dengan ini saya menyatakan bahwa pilihan yang saya tetapkan
                                                                merupakan keputusan pribadi yang sah tanpa
                                                                paksaan pihak lain
                                                                dengan penuh kesadaran dan tidak akan berubah
                                                                setelah dikonfirmasi.
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-auto my-auto">
                                                            <button type="button"
                                                                class="btn btn-secondary prev-button text-center"
                                                                style="font-size: .75rem; padding: .5rem 1rem; min-width: 113.41px;">
                                                                Sebelumnya
                                                            </button>
                                                        </div>
                                                        <div class="col-auto my-auto ms-auto">
                                                            <button type="button" class="btn btn-custom-3 text-center"
                                                                id="btnToTrigger" onclick="doSubmitForm(this)" disabled
                                                                style="font-size: .75rem; padding: .5rem 1rem; min-width: 113.41px;">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkPersetujuan(doc) {

            if (doc.checked == true) {

                document.getElementById('btnToTrigger').disabled = false;
            } else {

                document.getElementById('btnToTrigger').disabled = true;
            }
        }

        function doSubmitForm(doc_id) {

            doc_id.disabled = true;

            Swal.fire({
                // html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            var url = "{{ route('mahasiswa.vote') }}";

            var form = document.querySelector('form#formData');
            var formData = new FormData(form);

            fetch(url, {
                    method: 'post',
                    body: formData
                })
                .then(response => response.json())
                .then((data) => {

                    if (data.msg_resp != 'error') {

                        Swal.fire({
                            icon: 'success',
                            // title: 'Data Berhasil Disimpan',
                            html: `
                                Data Berhasil Disimpan<br>
                                <br>
                                Terima Kasih atas Partisipasi Anda!
                            `,
                            customClass: {
                                confirmButton: 'btn btn-swal-confirm'
                            },
                            buttonsStyling: false
                        }).then(function(result) {

                            window.location.replace('{{ url()->full() }}#step-3');
                            window.location.reload();
                        });
                    } else {

                        Swal.close();
                        doc_id.disabled = false;
                        showToastr(data.msg_resp, data.msg_desc);

                        if (data.field_err) {
                            var fieldName = data.field_err;
                            var $field = $("[name='" + fieldName + "']");
                            if ($field.length) {
                                // cari step/card terdekat yang menampung field
                                var $step = $field.closest(".card2");
                                if ($step.length) {
                                    // sembunyikan semua step
                                    $(".card2").removeClass("show");

                                    // tampilkan step yang berisi field error
                                    $step.addClass("show");

                                    $field.focus();
                                }
                            }
                        }
                    }
                });
        }
    </script>
@endsection
