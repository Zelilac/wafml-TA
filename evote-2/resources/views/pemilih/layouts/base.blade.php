<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>eVote Universitas Narotama</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    {{-- Favicons --}}
    {{-- <link rel="shortcut icon" href="{{ asset('') }}"> --}}

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">

    {{-- FontAwesome5 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome5/css/all.min.css') }}">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/toastr/toastr.min.css') }}">

    {{-- Toastify --}}
    <link rel="stylesheet" href="{{ asset('vendor/toastify/toastify.min.css') }}">

    {{-- Main --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Bootstrap --}}
    <script type="text/javascript" src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    {{-- Jquery --}}
    <script type="text/javascript" src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jquery/jquery-ui.js?v=0.0003') }}"></script>

    {{-- Toastify --}}
    <script type="text/javascript" src="{{ asset('vendor/toastify/toastify-js.js') }}"></script>

    {{-- SweetAlert2 --}}
    <script type="text/javascript" src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Toastr -->
    <script type="text/javascript" src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

    <!-- InputMask -->
    <script type="text/javascript" src="{{ asset('assets/inputmask/jquery.inputmask.js') }}"></script>

    <!-- Litepicker -->
    <script type="text/javascript" src="{{ asset('assets/litepicker/litepicker.js') }}"></script>

    {{-- Main --}}
    <script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>

    <style>
        .logo img {
            max-height: 80px;
        }

        @media (max-width: 991px) {
            .logo img {
                max-height: 50px;
                max-width: 100%;
            }
        }

        .sitename {
            font-size: 1rem;
            color: #000;
        }

        @media (max-width: 991px) {
            .sitename {
                font-size: .65rem;
            }
        }

        .overlay-blue::before {
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            content: "";
            background: url("{{ asset('assets/img/blob-scene-haikei-4.svg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            /* opacity: 0.8; */
            opacity: 1;
        }

        @media (max-width: 991px) {
            .overlay-blue::before {
                /* background: url("{{ asset('assets/img/blob-scene-haikei-5.svg') }}"); */
                background: none;
            }
        }

        @media (min-width: 576px) {
            .carousel-image {
                height: 20.5rem;
            }
        }

        .nav-pills-style-1 .nav-item .nav-link.active {
            background-color: #fff;
            color: #000;
            font-weight: bold;
        }

        .form-relative-2 {
            position: relative;
            margin-top: -.25rem;
        }

        .form-relative-2 .mh {
            min-height: calc(1.5em + .5rem + 2px);
            display: flex;
            align-items: center;
        }

        .form-relative-2 .form-control-placeholder {
            position: relative;
            padding: 0;
            font-size: 75%;
            opacity: 1;
        }

        .form-relative-2 .input-group {
            border: 0;
            font-size: 75%;
        }

        .input-group .form-check {
            min-height: 0;
            padding-left: 1.75em;
        }

        .input-group .form-check .form-check-input {
            margin-left: -1.5em;
            font-size: 14px;
        }

        .input-group .form-check-inline label {
            margin-top: .075rem;
        }

        .step-text {
            color: #000;
        }

        #progressbar {
            margin-top: .3rem;
        }

        #progressbar li {
            margin-bottom: 59px;
        }

        #progressbar li:before {
            background: #000;
        }

        #progressbar li.active:before {
            border: 2px solid #fff;
            color: #fff;
        }

        #progressbar li::after {
            height: 105px;
        }

        #progressbar li:nth-child(3):after {
            top: 90px;
        }

        #progressbar li:nth-child(4):after {
            top: 173px;
        }

        #progressbar li.active:after {
            background: #fff;
        }

        #progressbar li:hover:before {
            cursor: pointer;
            border: 2px solid #fff;
            color: #fff;
        }

        .btn-custom-1 {
            background-color: #db383b;
            border-color: #db383b;
            font-size: 1rem;
            padding: .5rem 1.5rem;
            text-transform: uppercase;
            color: #fff;
        }

        .btn-custom-1:hover,
        .btn-custom-1:focus {
            background-color: #b62d2f;
            border-color: #b62d2f;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #db383b;
            color: #fff;
        }

        .btn-custom-2 {
            background-color: #f39c12;
            border-color: #f39c12;
            font-size: 1rem;
            padding: .5rem 1.5rem;
            text-transform: uppercase;
            color: #fff;
        }

        .btn-custom-2:hover,
        .btn-custom-2:focus {
            background-color: #db8e11;
            border-color: #db8e11;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #f39c12;
            color: #fff;
        }

        .form-control.readonly {
            background-color: #e9ecef;
            pointer-events: none;
            user-select: none;
        }

        .input-asterisk {
            border-top-right-radius: 50rem;
            border-bottom-right-radius: 50rem;
        }

        .input-asterisk i {
            font-size: .55rem;
        }

        .form-control+.form-control-placeholder,
        .form-select+.form-control-placeholder,
        .form-select:disabled+.form-control-placeholder {
            font-size: 75%;
            top: -5px;
            /* left: 25px; */
            transform: translate3d(0, -100%, 0);
            padding: 0;
            opacity: 1;
            background-color: transparent;
        }

        .input-group:has(.is-invalid) {
            border-color: #dc3545;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: #fff;
        }

        .step-title {
            font-size: 1.15em;
        }

        .form-control-placeholder-2 {
            font-size: 75%;
            opacity: 1;
            position: relative;
            top: 0px;
            margin-bottom: 4px;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                flex-direction: row;
                gap: .5rem;
            }

            .navbar-dark .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23000' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }

            .navbar-toggler-icon {
                width: 1.25em;
                height: 1.25em;
            }

            .navbar-dark .navbar-nav .nav-link:focus,
            .navbar-dark .navbar-nav .nav-link:hover,
            .navbar-dark .navbar-nav .nav-link,
            .navbar-dark .navbar-nav .nav-link.active {
                color: #000;
            }

            .card1 {
                margin-left: 0;
                display: flex;
                flex-wrap: wrap;
            }

            #progressbar {
                display: flex;
                flex-wrap: wrap;
                flex-direction: row;
                position: relative;
                left: 0;
                flex: 0 0 auto;
                width: 100%;
                margin-bottom: .5rem;
            }

            #progressbar li {
                margin-bottom: 0;
                position: relative;
                flex: 0 0 auto;
                width: 33.33%;
            }

            #progressbar .step0:before {
                top: 0;
            }

            #progressbar li:hover:before {
                cursor: pointer;
                border: 2px solid #4f6581;
                color: #4f6581;
            }

            #progressbar li::after {
                height: 2px;
                width: 100%;
                left: 0;
                top: 50% !important;
                transform: translateY(50%) !important;
            }

            .step-text {
                margin-bottom: 0;
                flex: 0 0 auto;
                width: 33.33%;
                text-align: center
            }

            .step-title {
                font-size: .75em;
            }

            .order-custom-1 {
                order: -1 !important;
            }

            .dropdown-toggle {
                white-space: normal;
            }
        }

        .litepicker {
            flex-direction: column;
        }

        select.month-item-name,
        select.month-item-year {
            /* all: unset; */
            appearance: auto;
            padding: initial;
            background: initial;
            font: inherit;
            width: auto;
            margin-bottom: 0;
            color: initial;
            letter-spacing: initial;
            height: initial;
        }

        .btn-custom-3,
        .btn-swal-confirm {
            background-color: #3950a2;
            border-color: #3950a2;
            font-size: 1rem;
            padding: .5rem 1.5rem;
            text-transform: uppercase;
            color: #fff;
            min-width: 113.41px;
        }

        .btn-custom-3:hover,
        .btn-swal-confirm:hover,
        .btn-custom-3:focus,
        .btn-swal-confirm:focus {
            background-color: #2b3a74;
            border-color: #2b3a74;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #3950a2;
            color: #fff;
        }
    </style>
    <style>
        .btn {
            line-height: 1;
        }

        /* .carousel-image {
            object-fit: contain;
        }

        @media (min-width: 576px) {
            .carousel-image {
                margin-top: -3rem;
                height: 23rem;
            }
        } */
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-sm navbar-dark bg-light py-0" style="background-color: transparent !important;">
        <div class="ms-1 ms-sm-2 pt-0 pt-sm-2">
            <div>
                <a href="{{ url('') }}" class="logo d-flex align-items-center me-auto"
                    style="text-decoration: none;">
                    <img src="{{ asset('images/evote2.jpg') }}" alt="" class="me-2 me-lg-3">
                    {{-- <h2 class="sitename mb-0">
                    </h2> --}}
                </a>
            </div>
        </div>

        <button class="navbar-toggler ms-auto mt-lg-2 me-1 me-sm-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-2 mt-sm-0" id="navbarNavDropdown">
            <ul class="navbar-nav ms-sm-auto mx-2 mx-sm-0">
                <li class="nav-item dropdown">
                    <a class="nav-link notif-badge-container pe-0 relative z-index-3" href="#">
                        <i class="fas fa-user-circle me-1"
                            style="font-size: 2rem; color: #F2C718; background-color: #ffffff; border-radius: 50%;"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link relative z-index-3 dropdown-toggle" href="#" id="navDropdownUser"
                        role="button" style="padding-top: .65rem;" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-block d-lg-none" style="font-size: .65rem; line-height: 1;">Pemilih
                        </div>
                        {{ $authenticatedUser->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 13rem;"
                        aria-labelledby="navDropdownUser">
                        <li><a class="dropdown-item" href="{{ route('mahasiswa.index') }}"><i
                                    class="fa fa-home me-1"></i> Beranda</a></li>

                        <li><a class="dropdown-item" href="{{ route('mahasiswa.logout') }}"><i
                                    class="fa fa-sign-out-alt me-1"></i>
                                Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="bg-carousel overlay-blue z-index-minus-1">

        <div class="navbar-svg-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1"
                    d="m -1 272 l 134 0 c 106 0 459 0 693 0 c 158 13 279 -58 333 -114 c 86 -92 142 -140 286 -160 c -965 2 -1205 2 -1325 2 l -120 0 z">
                </path>
            </svg>
        </div>

        <div style="position: absolute; bottom: -1px; width: 100%; z-index: 2;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160">
                <path fill="#ffffff" fill-opacity="1"
                    d="M 1 163 L 122 163 L 720 163 L 1126 162 L 1447 163 C 1382 144 1376 82 1318 49 C 1282 28 1266 11 1105 0 L 720 0 C 480 0 240 0 120 0 L 0 0 Z">
                </path>
            </svg>
        </div>

        <div id="carouselHeader" class="carousel slide carousel-fade d-none d-lg-block" data-bs-ride="carousel">
            <div class="carousel-inner d-flex w-50 mx-auto" style="position: relative; z-index: -1; opacity: 1;">
                <div class="carousel-item active">
                    <img src="{{ asset('images/bg-header-1.svg') }}" class="d-block w-100 carousel-image">
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <script>
        $(".format-usia").inputmask({
            mask: "9",
            repeat: 3,
            greedy: false,
            placeholder: ""
        });

        $(".format-angka").inputmask({
            mask: "9",
            repeat: 16,
            greedy: false,
            placeholder: ""
        });

        $(".format-currency").inputmask({
            'alias': 'numeric',
            rightAlign: false,
            'groupSeparator': '․',
            'autoGroup': true
        });

        $(".format-nomor").inputmask({
            mask: "62 999-9999-9999[-9999]",
            postValidation: function(buffer, pos, currentResult, opts) {
                // Periksa apakah angka pertama setelah "62" adalah 0
                if (buffer[3] === '0') {
                    return false; // Tidak valid jika angka pertama setelah "62" adalah 0
                }
                return true; // Jika valid
            }
        });
    </script>

    <script>
        $(".datepicker").each(function() {
            new Litepicker({
                element: this,
                autoApply: false,
                singleMode: true,
                numberOfColumns: 1,
                numberOfMonths: 1,
                showWeekNumbers: true,
                format: "DD/MM/YYYY",
                dropdowns: {
                    minYear: 1910,
                    maxYear: null,
                    months: true,
                    years: true
                },
                buttonText: {
                    apply: 'Pilih',
                    cancel: 'Batal'
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
    </script>

</body>

</html>
