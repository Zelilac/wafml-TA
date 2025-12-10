<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>eVote Universitas Narotama</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    @yield('meta_og')

    {{-- Favicons --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/logo_kabupaten_gresik.png') }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{-- Vendor CSS Files --}}
    <link rel="stylesheet"
        href="{{ asset('vendor/themewagon-quickstart/assets/vendor/bootstrap/css/bootstrap.min.css') }}">

    {{-- FontAwesome5 --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome5/css/all.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/toastr/toastr.min.css') }}">

    {{-- Jquery --}}
    <script type="text/javascript" src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jquery/jquery-ui.js?v=0.0003') }}"></script>

    {{-- Vendor JS Files --}}
    <script type="text/javascript"
        src="{{ asset('vendor/themewagon-quickstart/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script type="text/javascript" src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Toastr -->
    <script type="text/javascript" src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

    <style>
        body {
            font-family: "Mulish", sans-serif;
            color: #3d4348;
        }

        .section-login .btn-login {
            background: #3950a2;
            color: #fff;
            font-weight: 500;
            font-size: 16px;
            letter-spacing: 1px;
            padding: 12px 24px;
            border-radius: 5px;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .section-login .btn-login:hover,
        .section-login .btn-login:focus:hover {
            color: #fff;
            background: color-mix(in srgb, #2b3a74, transparent 15%);
        }

        a {
            text-decoration: none;
            transition: 0.3s;
        }

        .sitename {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0;
            text-align: center;
            color: #3e5055;
        }

        @media (min-width: 992px) {
            .sitename {
                font-size: 1.05rem;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>

<body class="index-page">

    <main class="main position-relative" style="">

        @yield('content')

        <div class="position-relative">
            <div class="gradient-content"></div>
        </div>

    </main>

    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });

        function showToastr(msg_resp, msg_desc) {

            switch (msg_resp) {
                case 'error':

                    toastr.error(msg_desc);
                    break;
                case 'success':

                    toastr.success(msg_desc);
                    break;
                default:
                    toastr.error(msg_desc);
                    break;
            }
        }
    </script>

</body>

</html>
