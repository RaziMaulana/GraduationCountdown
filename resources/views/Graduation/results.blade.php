@extends('Graduation.layouts.app')

@section('content')
    <div class="position-absolute z-3 top-0 start-0 m-3 d-flex align-items-center gap-2">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-danger" title="Logout">
                <i class="bi bi-box-arrow-left fs-4"></i>
            </button>
        </form>
        @role('admin')
            <a href="{{ route('admin.manajemen-data') }}" class="btn btn-primary" title="Admin Page">
                <i class="bi bi-gear fs-4"></i>
            </a>
        @endrole
    </div>

    <div class="d-flex flex-column mt-5 justify-content-center align-items-center" style="min-height: calc(100vh - 180px);">
        <div id="result-container" class="my-5 border rounded-5 col-12 col-lg-10 mx-auto"
            style="@if ($user->status == 'Lulus') background-color: rgba(150, 195, 130, 0.5); @else background-color: rgba(163, 29, 29, 0.5); @endif">

            <div class="result-header px-4 py-3 rounded-top d-flex justify-content-center align-items-center"
                style="backdrop-filter: blur(5px);
            @if ($user->status == 'Lulus') background-color: rgba(119, 178, 84);
            @else
                background-color: rgba(229, 32, 32); @endif">
                <div class="text-center">
                    <h3 class="mb-0 text-white poppins-bold keterangan">
                        @if ($user->status == 'Lulus')
                            SELAMAT! <br> ANDA DINYATAKAN
                        @else
                            MAAF! <br> ANDA DINYATAKAN
                        @endif
                    </h3>
                </div>
            </div>

            <div class="result-content py-4 rounded-bottom">

                <div class="graduation-results text-center text-white mt-3 mb-5">
                    <h1 class="poppins-bold fw-bold display-4 text-uppercase">
                        {{ strtoupper($user->status) }}
                    </h1>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="content-left px-5 h-100 d-flex flex-column justify-content-center">
                            <div>
                                <h5 class="text-white user-nisn poppins-light">NISN {{ $user->nisn }}</h5>
                            </div>
                            <div class="mb-2">
                                <h1 class="text-white fw-bold text-uppercase poppins-bold user-name">{{ $user->name }}
                                </h1>
                            </div>
                            <div class="mb-2">
                                @php
                                    $jurusanMapping = [
                                        'RPL' => [
                                            'nama' => 'Rekayasa Perangkat Lunak',
                                            'logo' => 'Logo Jurusan RPL.png',
                                        ],
                                        'DKV' => [
                                            'nama' => 'Desain Komunikasi Visual',
                                            'logo' => 'Logo Jurusan MM.png',
                                        ],
                                        'TKJ' => [
                                            'nama' => 'Teknik Komputer Jaringan',
                                            'logo' => 'Logo Jurusan TKJ.png',
                                        ],
                                        'TITL' => [
                                            'nama' => 'Teknik Instalasi Tenaga Listrik',
                                            'logo' => 'Logo Jurusan TITL.png',
                                        ],
                                        'TBSM' => [
                                            'nama' => 'Teknik dan Bisnis Sepeda Motor',
                                            'logo' => 'Logo Jurusan TBSM.png',
                                        ],
                                        'TKR' => [
                                            'nama' => 'Teknik Kendaraan Ringan',
                                            'logo' => 'Logo Jurusan TKR.png',
                                        ],
                                        'TP' => ['nama' => 'Teknik Pemesinan', 'logo' => 'Logo Jurusan TP.png'],
                                        'TPL' => ['nama' => 'Teknik Pengelasan', 'logo' => 'Logo Jurusan TPL.png'],
                                    ];
                                    $jurusan = $jurusanMapping[$user->jurusan] ?? [
                                        'nama' => $user->jurusan,
                                        'logo' => 'Logo Jurusan Default.png',
                                    ];
                                @endphp
                                <h3 class="text-white fw-bold major poppins-bold text-uppercase">{{ $jurusan['nama'] }}</h3>
                            </div>
                            <div class="mb-2">
                                <h3 class="text-white fw-bold text-uppercase poppins-bold user-name">RATA - RATA :
                                    {{ $user->rata_rata }}
                                </h3>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="/image/Major/{{ $jurusan['logo'] }}" width="90px" class="major-image me-4"
                                    alt="Logo Jurusan">
                                <img src="/image/LambangSmk6.png" width="90px" class="school-image" alt="Logo Sekolah">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="content-right pe-3 h-100 d-flex flex-column align-items-center justify-content-center">
                            <div class="student-photo rounded-3 overflow-hidden" style="width: 250px; height: 300px;">
                                @if ($user->foto_diri)
                                    <img src="{{ $user->foto_diri }}" alt="Foto Siswa"
                                        class="img-fluid h-110 w-100 object-fit-cover"
                                        onerror="this.src='/image/student-placeholder.jpg'">
                                @else
                                    <img src="/image/Default User Photo.png" alt="Foto Siswa Default"
                                        class="img-fluid h-110 w-100 object-fit-cover">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #result-container {
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 1600px;
        }

        .keterangan {
            letter-spacing: 2px;
        }

        .result-content {
            min-height: 350px;
        }

        .graduation-results {
            letter-spacing: 7px;
        }

        .content-left {
            height: 100%;
        }

        .user-nisn {
            letter-spacing: 2px;
        }

        .user-name {
            letter-spacing: 3px;
        }

        .major {
            letter-spacing: 2px;
        }

        .content-right {
            border-radius: 10px;
            height: 100%;
        }

        .student-photo {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .student-photo:hover {
            transform: scale(1.05);
        }

        h1.text-white {
            font-size: 2.5rem;
            margin-top: 0.5rem;
        }

        h5.text-white {
            font-size: 1.2rem;
            opacity: 0.9;
        }
    </style>
@endsection
