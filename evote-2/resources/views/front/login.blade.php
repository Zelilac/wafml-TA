@extends('front.layouts.base')

@section('content')
    <style>
        .section-login .btn-login {
            padding: 8px 12px;
            font-size: 14px;
        }

        .form-control {
            font-size: .9rem;
        }

        .fs-8 {
            font-size: .9rem;
        }

        .sitename {
            text-align: left;
        }

        @media (min-width: 992px) {
            .card-login {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }
        }
    </style>

    <section class="section-login" style="height: calc(100vh);">
        <div class="row gx-0 h-100">
            <div class="col-lg-6 order-last fs-8">
                <div class="d-flex flex-column justify-content-center me-lg-5 pe-lg-5 ps-lg-0 px-3"
                    style="padding-top: 0; padding-bottom: 0; height: 100%; width: 550px; max-width: 100%;">
                    <div class="card card-login" style="box-shadow: 0 0 1.25rem rgba(0, 0, 0, 0.175);">
                        <div class="card-body" style="padding-top: 2rem; padding-bottom: 2rem;">
                            <div class="mb-2">
                                <a href="{{ url('') }}"
                                    class="logo d-flex align-items-center justify-content-center me-auto">
                                    {{-- <img src="{{ asset('images/logo-UN.png') }}" alt="" class="me-2 me-lg-2"
                                        style="max-height: 55px;">
                                    <h2 class="sitename">
                                        eVote Universitas Narotama
                                    </h2> --}}

                                    <img src="{{ asset('images/evote.jpg') }}" alt=""
                                        style="width: 200px; max-width: 100%;">
                                </a>
                            </div>

                            {{-- <a href="{{ url('') }}" class="d-block"><i class="fa fa-arrow-left me-2"></i> Kembali ke
                            Halaman Depan</a> --}}

                            <p class="fw-bold mb-0" style="font-size: 1.25rem;">Login</p>
                            <p class="mb-0" style="font-size: .85rem;">Silahkan Gunakan Akun sesuai Sistem Informasi
                                Akademik</p>
                            <p class="mb-0" style="font-size: .85rem;">(<a href="https://akademik.narotama.ac.id"
                                    style="color: #026e9f;" target="_blank">https://akademik.narotama.ac.id</a>) untuk Login
                            </p>

                            <form class="mt-2" id="formDataLogin">
                                @csrf
                                <div class="row gx-3 gy-3 gx-lg-2 gy-lg-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label fw-bold">Username</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control triggerBtn"
                                                    style="padding: 0.5rem 0.5rem;" id="username" name="username"
                                                    maxlength="10" placeholder="Masukkan Username">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label fw-bold">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control triggerBtn"
                                                    style="padding: 0.5rem 0.5rem;" id="password" name="password"
                                                    maxlength="10" placeholder="Masukkan Password">
                                                <span class="input-group-text" style="cursor: pointer;"
                                                    onclick="togglePassword('password')"><i id="password-icon"
                                                        class="fa fa-eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="captcha" class="form-label fw-bold">Captcha</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control triggerBtn"
                                                    style="padding: 0.5rem 0.5rem;" id="captcha" name="captcha"
                                                    maxlength="6" oninput="this.value = this.value.toUpperCase()"
                                                    placeholder="Masukkan Captcha">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mt-lg-auto">
                                        <img id="captcha-img" src="{{ route('captcha.image') }}" alt="captcha"
                                            style="user-select: none; pointer-events: none; background: #f3f4f6; width: 100%; height: 40px; border-radius: 0.375rem;" />
                                    </div>
                                    <div class="col-auto mt-lg-auto">
                                        <button type="button" class="btn btn-warning text-white"
                                            style="padding: 0.43rem 1rem;" id="refresh-captcha"><i
                                                class="fa fa-sync"></i></button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-3">
                                <div class="row gx-3 gy-3 gx-lg-2 gy-lg-2">
                                    <div class="col-12">
                                        <button type="button" onclick="doSubmitForm(this)" id="btnToTrigger"
                                            class="btn btn-login w-100">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block"
                style="background: url('{{ asset('images/blob-scene-haikei.svg') }}'); background-size: cover; background-repeat: no-repeat;">
                <div class="d-flex flex-column justify-content-center mx-lg-5 px-lg-5 "
                    style="padding-top: 2rem; padding-bottom: 2rem; height: 100%;">
                    <div>
                        <div class="position-relative">
                            <div class="quote-mark-line">
                                <h4 class="mb-0" style="font-size: 3rem; color: #fff; transform: rotateY(0deg);">
                                    <i class="fa fa-quote-left"></i>
                                </h4>
                            </div>

                            <h4 class="fs-2 fs-quotes text-white" style="font-size: 2.3rem; margin-top: 2rem;">
                                Demokrasi Hanya Dapat Berjalan Jika Disertai Rasa Tanggung Jawab dari Setiap Individu.
                            </h4>

                            {{-- <h4>
                            <i class="fa fa-quote-right"></i>
                        </h4>
                        <br> --}}

                            <div class="d-flex" style="margin-top: 3rem;">
                                <div class="my-auto">
                                    <p class="mb-0 text-white">
                                        - Bung Hatta
                                    </p>
                                </div>

                                <div class="ms-auto my-auto">
                                    <img src="{{ asset('images/bunghatta.png') }}"
                                        style="border-radius: 50%; border: 2px solid #fff; width: 7rem;">
                                </div>
                            </div>

                            <div class="text-end" style="margin-top: 2rem;">
                                <h4 class="mb-0" style="font-size: 3rem; color: #fff; transform: rotateY(0deg);">
                                    <i class="fa fa-quote-right"></i>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePassword(doc_id) {

            var doc_input = document.getElementById(doc_id);
            var doc_icon = document.getElementById(doc_id + '-icon');

            if (doc_input.type === "password") {

                doc_input.type = "text";
                doc_icon.classList.remove('fa-eye');
                doc_icon.classList.add('fa-eye-slash');
            } else {

                doc_input.type = "password";
                doc_icon.classList.remove('fa-eye-slash');
                doc_icon.classList.add('fa-eye');
            }
        }
    </script>

    <script>
        document.getElementById('refresh-captcha').addEventListener('click', function() {
            const img = document.getElementById('captcha-img');
            img.src = "{{ route('captcha.image') }}" + "?t=" + Date.now();
        });
    </script>

    <script>
        $('input.triggerBtn').keypress(function(e) {
            var code = e.keyCode || e.which;
            if (code === 13) {
                e.preventDefault();
                document.getElementById("btnToTrigger").click();
            }
        });

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

            var url = "{{ route('login') }}";

            var form = document.querySelector('form#formDataLogin');
            var formDataLogin = new FormData(form);

            fetch(url, {
                    method: 'post',
                    body: formDataLogin
                })
                .then(response => response.json())
                .then((data) => {

                    if (data.msg_resp != 'error') {

                        window.location.href = data.redirect;
                        // window.location.reload();
                        // Swal.fire({
                        //     icon: 'success',
                        //     title: 'Data Berhasil Disimpan'
                        // }).then(function(result) {

                        //     window.location.reload();
                        // });
                    } else {

                        Swal.close();
                        doc_id.disabled = false;
                        showToastr(data.msg_resp, data.msg_desc);

                        // if (data.refresh_captcha) {
                        document.getElementById('refresh-captcha').click();
                        document.getElementById('captcha').value = '';
                        // }
                    }
                });
        }
    </script>
@endsection
