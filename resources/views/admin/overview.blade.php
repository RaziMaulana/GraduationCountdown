@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('modals')

    <!-- Add Import Modal -->
    <div class="modal fade poppins-regular" id="importUserModal" tabindex="-1" aria-labelledby="importUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="importUserModalLabel"><i class="fas fa-file-import me-2"></i>Import Data Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk Import:</strong>
                        <ul class="mt-2 mb-0">
                            <li>Download template Excel untuk memastikan format yang benar</li>
                            <li>File harus berformat .xlsx atau .csv</li>
                        </ul>
                    </div>

                    <div class="text-center mb-4">
                        <a href="{{ asset('templates/Student_Excel_Template.xlsx') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-excel me-2"></i>Template Excel
                        </a>
                    </div>

                    <form id="importUserForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel</label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".xlsx,.xls,.csv" required>
                            <div class="form-text text-white-50">
                                Format file: .xlsx, .xls, atau .csv (Maks. 2MB)
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="importUser">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade poppins-regular" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="addUserModalLabel"><i class="fas fa-user-plus me-2"></i>Tambah Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="\d*"
                                title="Hanya angka yang diperbolehkan">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleAddPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text text-white-50">
                                <small>Password akan digunakan untuk login siswa</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <select class="form-select" id="jurusan" name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="RPL">RPL</option>
                                <option value="DKV">DKV</option>
                                <option value="TKJ">TKJ</option>
                                <option value="TITL">TITL</option>
                                <option value="TBSM">TBSM</option>
                                <option value="TKR">TKR</option>
                                <option value="TP">TP</option>
                                <option value="TPL">TPL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rata_rata" class="form-label">Rata-rata Nilai</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control"
                                id="rata_rata" name="rata_rata">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Lulus">Lulus</option>
                                <option value="Tidak Lulus">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="foto_diri" class="form-label">Foto Diri</label>
                            <input type="file" class="form-control" id="foto_diri" name="foto_diri">
                        </div>
                        <div class="form-text text-white">
                            <small>
                                <ul class="mb-0 ps-3">
                                    <li>Foto tidak boleh berukuran lebih dari 2MB</li>
                                    <li>Pastikan foto yang diupload ialah foto pas foto 3x4</li>
                                </ul>
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveUser">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade poppins-regular" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-edit me-2"></i>Edit Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNisn" class="form-label">NISN</label>
                            <input type="text" class="form-control" id="editNisn" name="nisn" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="\d*"
                                title="Hanya angka yang diperbolehkan">
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPassword" name="password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text text-white-50">
                                <small>Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editJurusan" class="form-label">Jurusan</label>
                            <select class="form-select" id="editJurusan" name="jurusan" required>
                                <option value="RPL">RPL</option>
                                <option value="DKV">DKV</option>
                                <option value="TKJ">TKJ</option>
                                <option value="TITL">TITL</option>
                                <option value="TBSM">TBSM</option>
                                <option value="TKR">TKR</option>
                                <option value="TP">TP</option>
                                <option value="TPL">TPL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editRataRata" class="form-label">Rata-rata Nilai</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control"
                                id="editRataRata" name="rata_rata">
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="Lulus">Lulus</option>
                                <option value="Tidak Lulus">Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editFotoDiri" class="form-label">Foto Diri</label>
                            <input type="file" class="form-control" id="editFotoDiri" name="foto_diri">
                        </div>
                        <div class="form-text text-white">
                            <small>
                                <ul class="mb-0 ps-3">
                                    <li>Foto tidak boleh berukuran lebih dari 2MB</li>
                                    <li>Pastikan foto yang diupload ialah foto pas foto 3x4</li>
                                </ul>
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="updateUser">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade poppins-regular" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="viewUserModalLabel"><i class="fas fa-eye me-2"></i>Detail Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="viewFotoDiri" src="" class="rounded-square img-fluid mb-3"
                                style="width: 200px; height: 200px;" alt="Foto Diri">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6>Nama</h6>
                                <p id="viewName" class="form-control-static"></p>
                            </div>
                            <div class="mb-3">
                                <h6>NISN</h6>
                                <p id="viewNisn" class="form-control-static"></p>
                            </div>
                            <div class="mb-3">
                                <h6>Password</h6>
                                <p id="viewPassword" class="form-control-static"></p>
                            </div>
                            <div class="mb-3">
                                <h6>Jurusan</h6>
                                <p id="viewJurusan" class="form-control-static"></p>
                            </div>
                            <div class="mb-3">
                                <h6>Rata-rata Nilai</h6>
                                <p id="viewRataRata" class="form-control-static"></p>
                            </div>
                            <div class="mb-3">
                                <h6>Status</h6>
                                <p id="viewStatus" class="form-control-static"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade poppins-regular" id="deleteConfirmationModal" tabindex="-1"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel"><i
                            class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus user ini?
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Confirmation Modal -->
    <div class="modal fade poppins-regular" id="deleteAllConfirmationModal" tabindex="-1"
        aria-labelledby="deleteAllConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="deleteAllConfirmationModalLabel"><i
                            class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan Massal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus SEMUA data siswa?</p>
                    <p class="text-danger"><strong>Perhatian:</strong> Aksi ini tidak dapat dibatalkan dan akan menghapus
                        semua data siswa beserta foto mereka!</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Data yang akan dihapus:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Semua data siswa</li>
                            <li>Semua foto profil siswa</li>
                            <li>Data tidak bisa dikembalikan</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-dark border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAll">
                        <i class="fas fa-trash-alt me-2"></i>Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('admin-content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark text-white">
                    <div
                        class="card-header poppins-regular d-flex justify-content-between align-items-center bg-dark text-white">
                        <h2><i class="fas fa-users-cog me-2"></i> Student Management</h2>
                        <div class="d-flex gap-2"> <!-- Tambahkan div wrapper dengan flex dan gap -->
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-2"></i>Tambah Siswa
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#importUserModal">
                                <i class="fas fa-file-import me-2"></i>Import Data
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="deleteAllBtn"
                                {{ $studentCount == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash-alt me-2"></i>Hapus Semua
                            </button>
                        </div>
                    </div>
                    <div class="card-body poppins-regular">
                        <table class="table table-bordered data-table table-dark w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>NISN</th>
                                    <th>Password</th>
                                    <th>Jurusan</th>
                                    <th>Rata-rata</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .card {
            letter-spacing: 2px;
        }

        .rounded-square {
            width: 50px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table-dark {
            --bs-table-bg: #343a40;
            --bs-table-striped-bg: #3d4348;
            --bs-table-hover-bg: #454d55;
        }

        .modal-content.bg-dark {
            background: #2b3035;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        #deleteAllBtn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .modal-body .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function updateDeleteAllButtonState() {
            $.ajax({
                url: "{{ route('admin.manajemen-data.count') }}",
                type: 'GET',
                success: (response) => {
                    $('#deleteAllBtn').prop('disabled', response.count === 0);
                },
                error: (xhr) => {
                    console.error('Error checking student count:', xhr);
                }
            });
        }

        $(document).ready(function() {
            const table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.manajemen-data') }}",
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nisn',
                        name: 'nisn',
                        className: 'text-center'
                    },
                    {
                        data: 'password',
                        name: 'password',
                        className: 'text-center'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'rata_rata',
                        name: 'rata_rata',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                responsive: true,
                autoWidth: false,
                drawCallback: function(settings) {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            let deleteUserId;

            // Import User
            $('#importUser').click(function() {
                const formData = new FormData($('#importUserForm')[0]);

                $.ajax({
                    url: "{{ route('admin.manajemen-data.import') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#importUser').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> Memproses...');
                    },
                    success: (response) => {
                        $('#importUserForm')[0].reset();
                        table.ajax.reload();
                        showToast(response.message || 'Data berhasil diimport 😊', 'success');
                        updateDeleteAllButtonState();
                    },
                    error: (xhr) => {
                        let errorMessage = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat mengimpor data.';
                        showToast(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#importUser').prop('disabled', false).html('Import');
                    }
                });
            });

            // Add User
            $('#saveUser').click(function() {
                const fotoDiri = $('#foto_diri')[0].files[0];

                if (fotoDiri && fotoDiri.size > 2 * 1024 * 1024) {
                    showToast('Ukuran foto tidak boleh lebih dari 2MB', 'error');
                    return;
                }

                const formData = new FormData($('#addUserForm')[0]);

                $.ajax({
                    url: "{{ route('admin.manajemen-data.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (response) => {
                        $('#addUserForm')[0].reset();
                        table.ajax.reload();
                        showToast('User berhasil ditambahkan 😊', 'success');
                        updateDeleteAllButtonState();
                    },
                    error: (xhr) => {
                        handleErrors(xhr);
                    }
                });
            });

            // View User
            $(document).on('click', '.view-btn', function() {
                const userId = $(this).data('id');

                $.ajax({
                    url: `/admin/manajemen-data/${userId}`,
                    type: 'GET',
                    success: (response) => {
                        $('#viewName').text(response.name);
                        $('#viewNisn').text(response.nisn);
                        $('#viewJurusan').text(response.jurusan || '-');
                        $('#viewStatus').text(response.status || '-');
                        $('#viewPassword').text(response.password_plain || '-');
                        $('#viewRataRata').text(response.rata_rata || '-');

                        if (response.foto_diri) {
                            $('#viewFotoDiri').attr('src', `/storage/${response.foto_diri}`);
                        } else {
                            $('#viewFotoDiri').attr('src',
                                `https://ui-avatars.com/api/?name=${encodeURIComponent(response.name)}&background=random`
                            );
                        }

                        $('#viewUserModal').modal('show');
                    },
                    error: (xhr) => {
                        handleErrors(xhr);
                    }
                });
            });

            // Edit User - Show Modal
            $(document).on('click', '.edit-btn', function() {
                const userId = $(this).data('id');

                $.ajax({
                    url: `/admin/manajemen-data/${userId}/edit`,
                    type: 'GET',
                    success: (response) => {
                        $('#editUserId').val(response.id);
                        $('#editName').val(response.name);
                        $('#editNisn').val(response.nisn);
                        $('#editJurusan').val(response.jurusan || '');
                        $('#editRataRata').val(response.rata_rata || '');
                        $('#editStatus').val(response.status || 'Lulus');
                        $('#editUserModal').modal('show');
                    },
                    error: (xhr) => {
                        handleErrors(xhr);
                    }
                });
            });

            // Update User
            $('#updateUser').click(function() {
                const fotoDiri = $('#editFotoDiri')[0].files[0];

                if (fotoDiri && fotoDiri.size > 2 * 1024 * 1024) {
                    showToast('Ukuran foto tidak boleh lebih dari 2MB', 'error');
                    return;
                }

                const userId = $('#editUserId').val();
                const formData = new FormData($('#editUserForm')[0]);

                $.ajax({
                    url: `/admin/manajemen-data/${userId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: (response) => {
                        $('#editUserModal').modal('hide');
                        table.ajax.reload();
                        showToast('Data Berhasil Diedit 😎', 'success');
                    },
                    error: (xhr) => {
                        handleErrors(xhr);
                    }
                });
            });

            // Delete User Confirmation
            $(document).on('click', '.delete-btn', function() {
                deleteUserId = $(this).data('id');
                $('#deleteConfirmationModal').modal('show');
            });

            // Confirm Delete User
            $('#confirmDelete').click(function() {
                $.ajax({
                    url: `/admin/manajemen-data/${deleteUserId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: (response) => {
                        $('#deleteConfirmationModal').modal('hide');
                        table.ajax.reload();
                        showToast('Data Berhasil Dihapus 🥲', 'success');
                        updateDeleteAllButtonState();
                    },
                    error: (xhr) => {
                        handleErrors(xhr);
                    }
                });
            });

            // Delete All Confirmation
            $('#deleteAllBtn').click(function() {
                $('#deleteAllConfirmationModal').modal('show');
            });

            // Confirm Delete All
            $('#confirmDeleteAll').click(function() {
                const button = $(this);
                button.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...');

                $.ajax({
                    url: "{{ route('admin.manajemen-data.destroy-all') }}",
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: (response) => {
                        $('#deleteAllConfirmationModal').modal('hide');
                        table.ajax.reload();
                        showToast(`Berhasil menghapus ${response.deleted_count} data siswa`,
                            'success');

                        // Disable tombol hapus semua jika tidak ada data lagi
                        $('#deleteAllBtn').prop('disabled', true);
                    },
                    error: (xhr) => {
                        showToast(xhr.responseJSON?.message || 'Gagal menghapus semua data',
                            'error');
                    },
                    complete: function() {
                        button.prop('disabled', false).html(
                            '<i class="fas fa-trash-alt me-2"></i>Ya, Hapus Semua');
                    }
                });
            });

            // Toggle password visibility for add modal
            $('#toggleAddPassword').click(function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Toggle password visibility for edit modal
            $('#toggleEditPassword').click(function() {
                const passwordField = $('#editPassword');
                const icon = $(this).find('i');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // NISN validation
            $('#nisn, #editNisn').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Toast Notification
            function showToast(message, type = 'success') {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: type === 'success' ? "#28a745" : "#dc3545",
                        borderRadius: "6px",
                    },
                }).showToast();
            }

            // Error Handling
            function handleErrors(xhr) {
                let errorMessage = 'Terjadi kesalahan!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                }
                showToast(errorMessage, 'error');
            }
        });
    </script>
@endpush
