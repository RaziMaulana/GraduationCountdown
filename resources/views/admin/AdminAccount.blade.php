@extends('admin.layouts.app')

@section('title', 'Pengaturan Countdown')

@section('admin-content')

    <!-- Add this at the top of your content section -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toast" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg" style="width: 900px;">
            <div class="card-header bg-dark text-center text-white">
                <i class="fas fa-circle-user me-2 display-6"></i>
                <h3 class="card-title mb-0">AKUN ADMIN</h3>
            </div>
            <div class="card-body bg-secondary text-white">
                @foreach ($adminUsers as $user)
                    <div class="row mb-4 border-bottom pb-3">
                        <!-- Left Column - Data -->
                        <div class="col-md-8 d-flex align-items-center">
                            <form class="edit-form w-100" data-user-id="{{ $user->id }}"
                                action="{{ route('admin.accounts.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mt-2">
                                    <div class="col-md-3 fw-bold">Name:</div>
                                    <div class="col-md-9">
                                        <span class="view-mode">{{ $user->name }}</span>
                                        <input type="text" name="name" value="{{ $user->name }}"
                                            class="form-control form-control-sm edit-mode d-none">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-3 fw-bold">Password:</div>
                                    <div class="col-md-9">
                                        <span class="view-mode">{{ $user->password_plain }}</span>
                                        <input type="text" name="password_plain" value="{{ $user->password_plain }}"
                                            class="form-control form-control-sm edit-mode d-none">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-3 fw-bold">NISN:</div>
                                    <div class="col-md-9">
                                        <span class="view-mode">{{ $user->nisn }}</span>
                                        <input type="text" name="nisn" value="{{ $user->nisn }}"
                                            class="form-control form-control-sm edit-mode d-none">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-9 offset-md-3">
                                        <button type="button" class="btn btn-sm btn-outline-warning edit-btn">Edit</button>
                                        <button type="submit" class="btn btn-sm btn-success save-btn d-none">Save</button>
                                        <button type="button"
                                            class="btn btn-sm btn-danger cancel-btn d-none">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Right Column - Photo -->
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                            <div class="profile-photo-container mb-2">
                                <div class="photo-frame"
                                    style="width: 200px; height: 260px; border: 5px solid #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.3); overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                    @if ($user->foto_diri)
                                        <img src="{{ asset('storage/' . $user->foto_diri) }}" class="img-fluid"
                                            style="width: 250px; height: 300px; object-fit: cover;" alt="Profile Photo">
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center"
                                            style="width: 250px; height: 300px; background-color: #e9ecef;">
                                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                            <p class="text-secondary mt-2">No Photo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('admin.accounts.upload-photo', $user->id) }}" method="POST"
                                enctype="multipart/form-data" class="photo-upload-form">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="photo" id="photo-input-{{ $user->id }}"
                                        class="form-control form-control-sm" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Upload Photo</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize toast
            const toastEl = document.getElementById('toast');
            const toast = new bootstrap.Toast(toastEl);

            // Show toast if there's a success message
            @if (session('success'))
                $('.toast-body').text("{{ session('success') }}");
                toast.show();
            @endif

            // Edit button click handler
            $('.edit-btn').click(function() {
                const form = $(this).closest('.edit-form');
                form.find('.view-mode').addClass('d-none');
                form.find('.edit-mode').removeClass('d-none');
                form.find('.edit-btn').addClass('d-none');
                form.find('.save-btn, .cancel-btn').removeClass('d-none');
            });

            // Cancel button click handler
            $('.cancel-btn').click(function() {
                const form = $(this).closest('.edit-form');
                form.find('.edit-mode').addClass('d-none');
                form.find('.view-mode').removeClass('d-none');
                form.find('.save-btn, .cancel-btn').addClass('d-none');
                form.find('.edit-btn').removeClass('d-none');
            });

            // Form submission handler
            $('.edit-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Update view mode values
                        form.find('.view-mode').each(function() {
                            const inputName = $(this).next('.edit-mode').attr('name');
                            $(this).text(form.find(`[name="${inputName}"]`).val());
                        });

                        // Switch back to view mode
                        form.find('.edit-mode').addClass('d-none');
                        form.find('.view-mode').removeClass('d-none');
                        form.find('.save-btn, .cancel-btn').addClass('d-none');
                        form.find('.edit-btn').removeClass('d-none');

                        // Show success toast
                        $('.toast-body').text('Data berhasil di Update 😎');
                        toast.show();
                    },
                    error: function(xhr) {
                        $('.toast').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-body').text('Error updating data. Please try again.');
                        toast.show();
                    }
                });
            });

            // Photo upload form submission
            $('.photo-upload-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.toast-body').text('Foto telah berhasil di Update 😁');
                        toast.show();
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        $('.toast').removeClass('bg-success').addClass('bg-danger');
                        const errors = xhr.responseJSON?.errors;
                        let errorMsg = errors?.photo ? errors.photo[0] :
                            'Error uploading photo. Please try again.';
                        $('.toast-body').text(errorMsg);
                        toast.show();
                    }
                });
            });
        });
    </script>
@endpush
