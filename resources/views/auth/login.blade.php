@extends('layouts.guest')

@section('content')
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-10 col-lg-8 col-xl-8 p-5 rounded-4"
                style="background-color: rgba(55, 55, 55, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">

                <div class="text-center mb-4">
                    <img src="image/LambangSmk6.png" class="img-fluid mb-3" style="max-width: 120px; height: auto;">
                    <h2 class="text-white Form-Header poppins-regular mb-4">PENGUMUMAN KELULUSAN <br> 2024/2025</h2>
                </div>

                <form method="POST" action="{{ route('login') }}" class="px-4">
                    @csrf

                    <div class="mb-4 mx-auto" style="max-width: 230px;">
                        <input type="text"
                            class="form-control poppins-regular text-white text-center @error('nisn') is-invalid @enderror"
                            id="nisn" name="nisn" placeholder="NISN" value="{{ old('nisn') }}" required autofocus
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" pattern="\d*"
                            title="Hanya angka yang diperbolehkan">
                        @error('nisn')
                            <div class="invalid-feedback text-center">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password field -->
                    <div class="mb-5 mx-auto position-relative" style="max-width: 230px;">
                        <input type="password" class="form-control poppins-regular text-white text-center" id="password"
                            name="password" placeholder="PASSWORD" required>
                        <span class="position-absolute {{ $errors->has('password') ? 'bottom-50' : 'top-50' }} end-0 translate-middle-y pe-3" style="cursor: pointer;">
                            <i class="bi bi-eye-slash text-white" id="togglePassword"></i>
                        </span>
                        @error('password')
                            <div class="text-danger text-center mt-2 poppins-regular" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-3 mx-auto" style="max-width: 320px;">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 px-5 poppins-regular">
                            Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .Form-Header {
            letter-spacing: 2px;
        }

        .form-control {
            background-color: rgba(73, 72, 72, 0.7) !important;
            letter-spacing: 2px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 30px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.15);
            outline: none;
        }

        .btn-primary {
            background-color: rgba(10, 94, 176);
            letter-spacing: 2px;
            border: none;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: rgba(13, 110, 253, 1);
        }

        /* Tambahan untuk smooth transition icon mata */
        .position-absolute {
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.getElementById('nisn').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the icon
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
@endsection
