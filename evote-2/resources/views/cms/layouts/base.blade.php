<!DOCTYPE html>
<html lang="en">

<head>
    @if (empty(request()->get('is_modal')) || !empty(request()->get('is_lookup')))
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
        <title>eVote Universitas Narotama</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}">
        {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Ubuntu:wght@400;500;700&family=Roboto+Mono&family=Caprasimo&family=Lato:wght@300;900&family=Roboto+Slab&family=Raleway&family=Libre+Franklin:wght@300;400;600&display=swap"
            rel="stylesheet">

        <!-- FontAwesome5 -->
        <link rel="stylesheet" href="{{ asset('assets/fontawesome5/css/all.min.css') }}">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

        <!-- TomSelect CSS -->
        {{-- <link rel="stylesheet" href="{{ asset('assets/tomselect/tom-select.css') }}"> --}}

        <!-- Jquery UI CSS -->
        <link rel="stylesheet" href="{{ asset('assets/jquery/jquery-ui.css') }}">

        <!-- Nifty CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/nifty.min.css') }}">

        <!-- ThemifyIcon -->
        <link rel="stylesheet" href="{{ asset('assets/themifyicons/themify-icons.css') }}">

        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('assets/toastr/toastr.min.css') }}">

        <!-- Loader CSS -->
        <link rel="stylesheet" href="{{ asset('assets/loadercss/loader.css') }}">

        <!-- Jquery -->
        <script type="text/javascript" src="{{ asset('assets/jquery/jquery-3.6.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.js') }}"></script>

        <!-- TomSelect JS -->
        {{-- <script type="text/javascript" src="{{ asset('assets/tomselect/tom-select.complete.min.js') }}"></script> --}}

        <!-- SweetAlert2 -->
        <script type="text/javascript" src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>

        <!-- Toastr -->
        <script type="text/javascript" src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

        <!-- Litepicker -->
        <script type="text/javascript" src="{{ asset('assets/litepicker/litepicker.js?v=0.001') }}"></script>

        <!-- InputMask -->
        <script type="text/javascript" src="{{ asset('assets/inputmask/jquery.inputmask.js') }}"></script>

        <!-- Chart JS -->
        <script type="text/javascript" src="{{ asset('vendor/chart-js/chart.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/chart-js/chartjs-plugin-datalabels.min.js') }}"></script>

        @auth
            {{-- <!-- CKEditor -->
                <script type="text/javascript" src="{{ asset('vendor/ckeditor/ckeditor.js?v=0.002') }}"></script> --}}
        @endauth

        <style>
            .brand-wrap:hover {
                transition: transform .5s ease;
                transform: scale(1);
            }

            :root {
                /* --litepicker-day-width: 28px; */
                --litepicker-day-width: calc(100% / 8);
            }

            /* .modal-backdrop:nth-child(2n-1) {
        opacity: 0;
    } */

            /* .modal.show:nth-of-type(2) {
        background-color: red;
    } */

            /* .modal-backdrop:not(:first-child) {
        opacity: 0;
    } */

            .header__inner {
                height: 3rem;
            }

            .content__wrap {
                padding-top: 0;
                padding-bottom: 1.5rem;
            }

            .form-control {
                padding: .35rem .65rem;
                min-height: 37.6px;
            }

            .ts-control {
                padding: 8px .65rem;
            }

            .ts-dropdown,
            .ts-control,
            .ts-control input {
                font-size: .75rem;
            }

            .form-control-sm .ts-control {
                font-size: .65625rem;
            }

            /* .form-control-sm {
            padding: .35rem .65rem;
            min-height: calc(1.5em + 0.9rem + 2px);
        } */

            @media (min-width: 576px) {
                .col-sm-21 {
                    flex: 0 0 auto;
                    width: calc(17.66667%);
                }
            }

            @media (min-width: 992px) {
                .litepicker .container__footer {
                    min-width: 200px;
                }

                /* Offset */
                .col-lg-2-offset-5 {
                    flex: 0 0 auto;
                    width: calc(16.66667% + 14.5%);
                }
            }

            @media (min-width: 1200px) {
                .col-xl-15 {
                    flex: 0 0 auto;
                    width: 14%;
                }

                .col-xl-25 {
                    flex: 0 0 auto;
                    width: 20%;
                }

                .col-xl-27 {
                    flex: 0 0 auto;
                    width: 22%;
                }

                /* Offset */
                .col-xl-15-offset-5 {
                    flex: 0 0 auto;
                    width: calc(14% + 14.5%);
                }

                .col-xl-25-offset-5 {
                    flex: 0 0 auto;
                    width: calc(20% + 14.5%);
                }

                .col-xl-27-offset-5 {
                    flex: 0 0 auto;
                    width: calc(22% + 15.75%);
                }
            }

            @media (min-width: 1400px) {
                .col-xxl-15 {
                    flex: 0 0 auto;
                    width: 14%;
                }

                /* Offset */
                .col-xxl-15-offset-5 {
                    flex: 0 0 auto;
                    width: calc(14% + 14.5%);
                }
            }

            .table-hover>tbody>tr:hover {
                /* --bs-table-accent-bg: rgba(255, 193, 7, 0.2); */
                --bs-table-accent-bg: #FFFDAF;
                color: var(--bs-table-hover-color);
            }

            .form-check-input[readonly] {
                pointer-events: none;
                filter: none;
                opacity: .5;
            }

            .ts-dropdown {
                z-index: 1400;
            }

            .ts-control {
                color: #75868f;
            }

            .locked .ts-control {
                background-color: #f3f5f9 !important;
                border-color: #edf1f6;
                opacity: 1;
                cursor: default !important;
            }

            .locked input {
                display: none !important;
            }

            /* .litepicker {
        max-width: 600px !important;
    } */

            .litepicker .container__days>div,
            .litepicker .container__days>a {
                padding: 12px 0;
            }

            .litepicker .container__footer .button-cancel,
            .litepicker .container__footer .button-apply {
                padding: 6px 10px 7px;
            }

            .nav-link {
                padding: 0.5rem 0.75rem;
                display: flex;
            }

            .root .mainnav__inner .nav-link.active~.nav .active .nav-label {
                color: #25476a;
            }

            @media(max-width: 575px) {

                .litepicker {
                    width: calc(100% - 10px) !important;
                    top: 0 !important;
                    left: 5px !important;
                    right: 5px !important;
                    /* display: flex !important; */
                    flex-direction: column !important;
                    justify-content: center !important;
                    min-height: calc(100vh - 1rem) !important;
                }
            }

            @media (min-width: 576px) {
                .modal-dialog-centered {
                    min-height: calc(100%);
                }
            }

            @media (min-width: 992px) {
                .root:not(.mn--min) .mainnav__menu>.nav-item.has-sub>.mininav-content>.nav-item>.nav-link {
                    padding-inline-start: 0.75rem;
                }

                .root:not(.mn--min) .mainnav__menu>.nav-item.has-sub .has-sub>.mininav-content {
                    /* border-inline-start: none; */
                    /* margin-inline-start: 0; */
                    margin-inline-start: calc(.25em + .75rem);
                    padding-inline-start: 0;
                }

                .root:not(.mn--min) .mainnav__menu>.nav-item.has-sub .has-sub>.mininav-content>.nav-item>.nav-link {
                    padding-inline-start: 0.75rem;
                }

                .mn--min .mainnav__menu>.has-sub .has-sub>.nav-link {
                    gap: 0;
                }
            }

            .tooltip-custom-1 .tooltip-inner {
                min-width: 250px;
            }

            .loader-wrapper {
                display: flex;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                background-color: #fff;
            }

            .g-2-5,
            .gx-2-5 {
                --bs-gutter-x: 0.75rem;
            }

            .g-2-5,
            .gy-2-5 {
                --bs-gutter-y: 0.75rem;
            }

            .ui-autocomplete {
                min-width: 50vw;
            }

            @media (min-width: 992px) {

                .ui-autocomplete {
                    min-width: 20vw;
                }
            }

            .input-search-menu {
                border: 0;
                border-bottom: 3px solid rgba(0, 0, 0, .07);
                /* border-radius: 0; */
            }

            .input-search-menu .ts-control {
                border: 0;
                /* border-bottom: 1px solid #d0d0d0; */
            }

            .input-search-menu .ts-control input {
                font-size: var(--bs-body-font-size);
            }

            .notif-hover {
                transition-duration: .4s;
                cursor: default;
            }

            .notif-hover:hover {
                background-color: #f3f5f9;
            }

            .rounded-top-0 {
                border-top-left-radius: 0 !important;
                border-top-right-radius: 0 !important;
            }

            .rounded-bottom-0 {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }
        </style>

        <style>
            .btn-check:checked+.btn-primary {
                background-color: #25476a;
            }

            .btn-check+.btn-primary {
                background-color: #cccccc;
                border-color: #c2c1c1;
            }

            .hover-menu-parent {
                cursor: pointer;
            }

            .hover-menu-parent:hover {
                background-color: #ffffcc;
            }

            /* .no-style, */
            .no-style * {
                all: unset;
            }

            #cke_deskripsi_list .cke_bottom {
                display: none !important;
            }

            /* .table:not(.table-borderless):not(.table-bordered)>tfoot:not(:last-child)> :last-child>* { */
            .table:not(.table-borderless):not(.table-bordered)>tfoot> :last-child>* {
                border-bottom: 2px solid currentColor !important;
            }
        </style>

        <style>
            /* .header,
            .hd--expanded .content__header,
            .hd--expanded.mn--max .content__header:before,
            .hd--expanded.mn--min .content__header:before,
            .mainnav__inner .mainnav__menu>.nav-item>.nav-link.active,
            .bg-primary,
            .btn-primary {
                background-color: #f39c12 !important;
            }

            .root .mainnav__inner .nav-link.active~.nav .active .nav-label {
                color: #f39c12 !important;
            }

            .btn-primary {
                border-color: #f39c12 !important;
                box-shadow: 0 0 0 #f39c12, 0 0 0 rgba(55, 60, 67, .25) !important;
            }

            .btn-primary:active:focus,
            .btn-primary:focus,
            .btn-primary:hover {
                box-shadow: 0 0 0 #c7800e, 0 0 0 rgba(55, 60, 67, .25) !important;
                background-color: #c7800e !important;
                border-color: #c7800e !important;
            }

            .header__btn:focus,
            .header__btn:hover {
                background-color: #c7800e !important;
            } */
        </style>

        <style>
            .hide {
                display: none;
            }

            .ui-front {
                z-index: 10000;
            }
        </style>
    @endif
</head>

<body class="jumping">
    @if (empty(request()->get('is_modal')) && empty(request()->get('no-menu')))
        <div id="root" class="root mn--max hd--expanded">
    @endif

    @yield('content')

    @if (empty(request()->get('is_modal')) && empty(request()->get('no-menu')))
        <header class="header">
            <div class="header__inner">
                <div class="header__brand">
                    <div class="brand-wrap">
                        <a href="{{ url('') }}" class="brand-img stretched-link">

                        </a>

                        <div class="brand-title fs-5">Evote Narotama</div>

                    </div>
                </div>

                <div class="header__content">
                    <div class="header__content-start">
                        <button type="button" class="nav-toggler header__btn btn btn-icon btn-sm"
                            aria-label="Nav Toggler">
                            <i class="demo-psi-view-list"></i>
                        </button>
                    </div>

                    <div class="header__content-end">
                        <div class="dropdown">
                            <button class="header__btn btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown"
                                aria-label="User dropdown" aria-expanded="false">
                                <i class="demo-psi-male"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end w-md-250px">
                                <div class="d-flex align-items-center border-bottom px-3 py-2">
                                    <div class="flex-shrink-0">
                                        <img class="img-sm rounded-circle"
                                            src="{{ asset('assets/img/user-icon.png') }}" alt="Profile Picture"
                                            loading="lazy">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ $authenticatedUser->username }}</h5>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="list-group list-group-borderless h-100 py-3">
                                            {{-- <a href="{{ url('profile') }}"
                                                class="list-group-item list-group-item-action">
                                                <i class="demo-pli-male fs-5 me-2"></i> Profil
                                            </a> --}}
                                            <a href="{{ url('logout') }}"
                                                class="list-group-item list-group-item-action">
                                                <i class="demo-pli-unlock fs-5 me-2"></i> Keluar
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <nav id="mainnav-container" class="mainnav">
            <div class="mainnav__inner">
                <div class="mainnav__top-content scrollable-content pb-5">

                    <div class="mainnav__categoriy py-3">

                        <h6 class="mainnav__caption mt-0 px-3 fw-bold">Menu Utama</h6>

                        <ul class="mainnav__menu nav flex-column">
                            @if (in_array($idrole, [$GLO_TIPEUSER_ADMIN]))
                                <li class="nav-item">
                                    <a href="{{ url('') }}"
                                        class="nav-link mininav-toggle {{ in_array($currentRouteName, ['dashboard']) ? 'active' : '' }}"><i
                                            class="demo-pli-bar-chart fs-5 me-2"></i>
                                        <span class="nav-label mininav-content ms-1">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('tahun-periode') }}"
                                        class="nav-link mininav-toggle {{ in_array($currentRouteName, ['tahun-periode']) ? 'active' : '' }}"><i
                                            class="demo-pli-calendar-4 fs-5 me-2"></i>
                                        <span class="nav-label mininav-content ms-1">Periode</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('master-presiden-bem') }}"
                                        class="nav-link mininav-toggle {{ in_array($currentRouteName, ['master-presiden-bem']) ? 'active' : '' }}"><i
                                            class="demo-pli-find-user fs-5 me-2"></i>
                                        <span class="nav-label mininav-content ms-1">Presiden BEM</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('master-hima') }}"
                                        class="nav-link mininav-toggle {{ in_array($currentRouteName, ['master-hima']) ? 'active' : '' }}"><i
                                            class="demo-pli-find-user fs-5 me-2"></i>
                                        <span class="nav-label mininav-content ms-1">HIMA</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="mainnav__bottom-content border-top pb-2">
                    <ul id="mainnav" class="mainnav__menu nav flex-column">
                        <li class="nav-item">
                            <a href="{{ url('logout') }}" class="nav-link" aria-expanded="false">
                                <i class="demo-pli-unlock fs-5 me-2"></i>
                                <span class="nav-label ms-1">Keluar</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    @endif
    @if (empty(request()->get('is_modal')) && empty(request()->get('no-menu')))
        </div>
    @endif

    <div class="modal fade" id="modalLookup">
        <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
            <div class="modal-content" style="background-color: #eee;">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="modalTitleLookup"></h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body" id="modalBodyLookup" style="margin: 1rem; padding: 0;">
                    <iframe allowtransparency="true" id="frameModalLookup"
                        style="width: 100%; min-height: 50rem; border: 0px;"></iframe>
                </div>
            </div>
        </div>
    </div>

    @if (empty(request()->get('is_modal')) || !empty(request()->get('is_lookup')))
        <!-- Bootstrap JS -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

        <!-- Nifty JS -->
        <script src="{{ asset('assets/js/nifty.min.js') }}" defer></script>

        {{-- @if (empty(request()->get('is_modal')) || empty(request()->get('is_lookup')))
            @if (in_array($idrole, [$GLO_TIPEUSER_ADMIN]))
                <script>
                    var search_menu_autocomplete_tomselect = new TomSelect('#search_menu_autocomplete', {
                        valueField: 'href',
                        searchField: 'name',
                        maxOptions: 10,
                        dropdownParent: 'body',
                        load: function(query, callback) {

                            var url = '{{ url('') }}/sidebar/search-menu?by=name&q=' + encodeURIComponent(
                                query);
                            fetch(url)
                                .then(response => response.json())
                                .then(json => {
                                    callback(json.items);
                                }).catch(() => {
                                    callback();
                                });
                        },
                        render: {
                            option: function(data, escape) {
                                return '<div>' + escape(data.name) + '</div>';
                            },
                            item: function(data, escape) {
                                return '<div>' + escape(data.name) + '</div>';
                            },
                        },
                        onItemAdd: function(value, item) {

                            window.location.href = '{{ url('') }}/' + encodeURIComponent(value);
                        }
                    });
                </script>
            @endif
        @endif --}}

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

            $(".format-currency").inputmask({
                'alias': 'numeric',
                rightAlign: false,
                'groupSeparator': '․',
                'autoGroup': true
            });

            function angkaKeRupiah(angka, prefix = '0', suffix = '0') {
                var number_string = angka.toString().replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return (prefix == '1' ? 'Rp. ' : '') + rupiah + (suffix == '1' ? ',-' : '');
            }

            function rupiahKeAngka(angka) {
                var angkaFormatted = angka.replace(/[^,\d]/g, "").toString();
                return angkaFormatted;
            }

            function tgl_indo_to_sql(date) {

                ex = date.split("/");
                hasil = ex[2] + "-" + ex[1] + "-" + ex[0];

                return hasil;
            }

            function tgl_sql_to_indo(date) {

                ex = date.split("-");
                hasil = ex[2] + "/" + ex[1] + "/" + ex[0];

                return hasil;
            }
        </script>
    @endif

    <script>
        $(".format-usia").inputmask({
            mask: "9",
            repeat: 3,
            greedy: false,
            placeholder: ""
        });

        $(".format-jml-bersaudara").inputmask({
            mask: "9",
            repeat: 2,
            greedy: false,
            placeholder: ""
        });

        $(".format-tahun").inputmask({
            mask: "9",
            repeat: 4,
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
        function generatePassword() {
            const lower = "abcdefghijklmnopqrstuvwxyz";
            const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const number = "0123456789";
            const symbol = "!@#$%^&*()_+[]{}<>?";

            // wajib masing-masing 1
            let password = [
                lower[Math.floor(Math.random() * lower.length)],
                upper[Math.floor(Math.random() * upper.length)],
                number[Math.floor(Math.random() * number.length)],
                symbol[Math.floor(Math.random() * symbol.length)],
            ];

            // tambah 4 karakter random bebas
            const all = lower + upper + number + symbol;
            for (let i = 0; i < 4; i++) {
                password.push(all[Math.floor(Math.random() * all.length)]);
            }

            // acak urutannya biar nggak selalu sama
            password = password.sort(() => Math.random() - 0.5).join('');

            return password;
        }
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
</body>

</html>
