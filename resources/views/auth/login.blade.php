@extends('layouts.guest')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-10 col-lg-8 col-xl-8 p-5 rounded-4" style="background-color: rgba(55, 55, 55, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">

            <div class="text-center mb-4">
                <img src="image/LambangSmk6.png" class="img-fluid mb-3" style="max-width: 120px; height: auto;">
                <h2 class="text-white Form-Header poppins-regular mb-4">PENGUMUMAN KELULUSAN <br> 2024/2025</h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="px-4">
                @csrf

                <div class="mb-5 mx-auto mt-5" style="max-width: 230px;"> <!-- Changed from 400px to 250px -->
                    <input type="text"
                           class="form-control poppins-regular text-white text-center @error('nis') is-invalid @enderror"
                           id="nis"
                           name="nis"
                           placeholder="NIS"
                           value="{{ old('nis') }}"
                           required
                           autofocus>
                    @error('nis')
                        <div class="invalid-feedback text-center">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid gap-3 mx-auto" style="max-width: 320px;"> <!-- Reduced from 300px to 200px -->
                    <button type="submit" class="btn btn-primary rounded-pill py-3 px-5 poppins-regular">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .Form-Header{
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
</style>
@endsection
