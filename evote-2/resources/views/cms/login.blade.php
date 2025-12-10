@extends('cms.layouts.base-login')

@section('content')
    <style>
        .content-login {
            min-width: auto;
        }

        @media (min-width: 992px) {

            .content-login {
                min-width: 400px;
            }
        }
    </style>

    <section id="content" class="content">
        <div class="content__boxed w-100 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <div class="content__wrap p-0 content-login">
                <div class="d-flex justify-content-center mb-4">
                    {{-- <h2 class="ms-2 my-auto">SIBIMAGres</h2> --}}
                    <div>
                        <a href="{{ url('') }}" class="logo d-flex align-items-center me-auto"
                            style="text-decoration: none;">
                            <img src="{{ asset('assets/img/logo_kabupaten_gresik.png') }}" alt="" class="me-2 me-lg-2"
                                style="height: 4rem;">
                            <h2 class="sitename mb-0" style="font-size: 1.15rem;">
                                Sistem Informasi Seleksi<br class="d-block d-lg-none">
                                Beasiswa Mahasiswa<br>
                                Pemerintah Kabupaten Gresik
                            </h2>
                        </a>
                    </div>
                </div>
                <div class="card shadow-lg" style="max-width: 100%;">
                    <div class="card-body">

                        <div class="text-center">
                            <h1 class="h3">Login</h1>
                        </div>

                        <form class="mt-4" id="formDataLogin">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control triggerBtn"
                                        maxlength="10" placeholder="Masukkan Username" autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password"
                                            class="form-control triggerBtn" maxlength="10" placeholder="Masukkan Password">
                                        <span class="input-group-text" style="cursor: pointer;"
                                            onclick="togglePassword('password')"><i id="password-icon"
                                                class="fa fa-eye"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="captcha">Captcha</label>
                                    <div class="row">
                                        <div class="col-lg my-lg-auto">
                                            <input type="text" id="captcha" name="captcha"
                                                class="form-control triggerBtn" maxlength="6"
                                                oninput="this.value = this.value.toUpperCase()"
                                                placeholder="Masukkan Captcha">
                                        </div>
                                        <div class="col my-lg-auto">
                                            <img id="captcha-img" src="{{ route('captcha.image') }}" alt="captcha"
                                                style="user-select: none; pointer-events: none; background: #f3f4f6; width: 100%; height: 50px;" />
                                        </div>
                                        <div class="col-auto my-lg-auto">
                                            <button type="button" class="btn btn-warning text-white"
                                                style="padding: 0.75rem 1.1rem;" id="refresh-captcha"><i
                                                    class="fa fa-sync"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-5">
                                <button class="btn btn-primary btn-lg" type="button" id="btnToTrigger"
                                    onclick="doSubmitForm(this)">Sign In</button>
                            </div>
                        </form>
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

            var url = "{{ route('admin-login.login') }}";

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
