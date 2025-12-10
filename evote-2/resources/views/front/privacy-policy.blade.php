@extends('front.layouts.base')

@section('content')
    <div class="blog-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="content-container">
                        <div class="mb-3">
                            <h2>Kebijakan Privasi</h2>
                        </div>
                        <p>Terakhir diperbarui pada {{ Helper::tglFormatId('2024-02-03') }}</p>
                    </div>

                    <div class="content-container">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-auto my-auto">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="col my-auto">
                                    <h2>Informasi yang Kami Kumpulkan</h2>
                                </div>
                            </div>
                        </div>
                        <p>
                            Saat Anda mengunjungi situs web kami, kami secara otomatis dapat mengumpulkan informasi tertentu
                            tentang perangkat Anda, termasuk alamat IP, jenis perangkat, browser yang digunakan, serta data
                            penggunaan seperti halaman yang dikunjungi dan waktu yang dihabiskan di situs kami. Kami juga
                            dapat menggunakan teknologi seperti cookies untuk mengumpulkan informasi tambahan tentang
                            interaksi Anda dengan situs kami.
                        </p>
                    </div>

                    <div class="content-container">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-auto my-auto">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="col my-auto">
                                    <h2>Penggunaan Informasi</h2>
                                </div>
                            </div>
                        </div>
                        <p>
                            Informasi yang kami kumpulkan digunakan untuk mengoptimalkan pengalaman pengguna di situs kami,
                            memahami preferensi pengguna, serta menyediakan konten dan layanan yang sesuai dengan kebutuhan
                            Anda. Kami tidak akan membagikan informasi pribadi Anda kepada pihak ketiga tanpa izin Anda,
                            kecuali jika diperlukan oleh hukum.
                        </p>
                    </div>

                    <div class="content-container">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-auto my-auto">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="col my-auto">
                                    <h2>Perubahan pada Kebijakan Privasi</h2>
                                </div>
                            </div>
                        </div>
                        <p>
                            Kebijakan privasi ini dapat diperbarui dari waktu ke waktu. Perubahan akan diposting di halaman
                            ini, dan kami mendorong Anda untuk memeriksanya secara berkala.
                        </p>
                    </div>

                    <div class="content-container">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-auto my-auto">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="col my-auto">
                                    <h2>Kontak</h2>
                                </div>
                            </div>
                        </div>
                        <p>
                            Jika Anda memiliki pertanyaan atau kekhawatiran tentang kebijakan privasi kami, Anda dapat
                            menghubungi kami melalui <a href="mailto:support@narotama.ac.id">support@narotama.ac.id</a>.
                        </p>
                    </div>

                    <div class="content-container border-bottom-0">
                        <p>
                            Dengan menggunakan situs web kami, Anda menyetujui pengumpulan dan penggunaan informasi sesuai
                            dengan kebijakan privasi ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
