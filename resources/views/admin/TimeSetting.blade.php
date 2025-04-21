@extends('admin.layouts.app')

@section('title', 'Pengaturan Countdown')

@section('admin-content')
    <div class="container-fluid py-4 min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="rounded-4 p-5 shadow-lg Main-container border">
                        <!-- Countdown Display Section -->
                        <div class="text-center mb-4">
                            <div id="countdownDisplay">
                                <div class="d-flex justify-content-center text-dark flex-wrap gap-3 mb-3">
                                    <!-- Days -->
                                    <div class="countdown-item bg-gradient-light rounded-3 p-3 shadow">
                                        <div class="countdown-number display-3 fw-bolder" id="days">00</div>
                                        <div class="countdown-label text-muted text-uppercase small mt-2">Hari</div>
                                    </div>

                                    <!-- Hours -->
                                    <div class="countdown-item bg-gradient-light rounded-3 p-3 shadow">
                                        <div class="countdown-number display-3 fw-bolder" id="hours">00</div>
                                        <div class="countdown-label text-muted text-uppercase small mt-2">Jam</div>
                                    </div>

                                    <!-- Minutes -->
                                    <div class="countdown-item bg-gradient-light rounded-3 p-3 shadow">
                                        <div class="countdown-number display-3 fw-bolder" id="minutes">00</div>
                                        <div class="countdown-label text-muted text-uppercase small mt-2">Menit</div>
                                    </div>

                                    <!-- Seconds -->
                                    <div class="countdown-item bg-gradient-light rounded-3 p-3 shadow">
                                        <div class="countdown-number display-3 fw-bolder" id="seconds">00</div>
                                        <div class="countdown-label text-muted text-uppercase small mt-2">Detik</div>
                                    </div>
                                </div>
                            </div>
                            <div id="countdownMessage" class="mt-3"></div>
                        </div>

                        <div class="pt-4 text-white">
                            <form id="countdownForm" class="needs-validation" novalidate>
                                <div class="mb-4">
                                    <label for="targetDate" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt me-2"></i>Tanggal & Waktu Pengumuman
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-clock text-primary"></i>
                                        </span>
                                        <input type="datetime-local" class="form-control form-control-lg" id="targetDate"
                                            required>
                                    </div>
                                    <div class="form-text text-white">
                                        Pilih tanggal dan waktu target pengumuman kelulusan
                                    </div>
                                    <div class="invalid-feedback text-white">
                                        Harap pilih tanggal dan waktu yang valid
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill">
                                        <i class="fas fa-save me-2"></i> Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification - Updated with rounded corners -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="notificationToast" class="toast rounded-pill overflow-hidden" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="toast-body d-flex justify-content-between align-items-center bg-success text-white py-3 px-4">
                <span class="fw-medium">Waktu Berhasil Diset ⌚</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        class CountdownAdmin {
            constructor() {
                this.form = document.getElementById('countdownForm');
                this.targetDateInput = document.getElementById('targetDate');
                this.toast = new bootstrap.Toast(document.getElementById('notificationToast'));
                this.countdownInterval = null;
                this.init();
            }

            async init() {
                await this.loadCurrentSettings();
                this.setupFormValidation();
                this.setupFormSubmit();
                this.startCountdown();
            }

            setupFormValidation() {
                this.form.addEventListener('submit', (event) => {
                    if (!this.form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    this.form.classList.add('was-validated');
                }, false);
            }

            async loadCurrentSettings() {
                try {
                    const response = await fetch('/admin/atur-waktu/countdown');

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.targetDate) {
                        const date = new Date(data.targetDate);
                        const localDateTime = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
                            .toISOString()
                            .slice(0, 16);
                        this.targetDateInput.value = localDateTime;
                        this.startCountdown();
                    } else {
                        this.updateCountdownDisplay(null);
                    }
                } catch (error) {
                    console.error('Error loading settings:', error);
                    this.showToast('Gagal memuat pengaturan saat ini', 'error');
                    this.targetDateInput.value = '';
                    this.updateCountdownDisplay(null);
                }
            }

            setupFormSubmit() {
                this.form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    const submitButton = this.form.querySelector('button[type="submit"]');
                    const originalContent = submitButton.innerHTML;

                    try {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Menyimpan...
        `;

                        const timestamp = Math.floor(new Date(this.targetDateInput.value).getTime() / 1000);

                        const response = await fetch('/admin/atur-waktu/countdown', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                targetDate: new Date(this.targetDateInput.value)
                                    .toISOString()
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showToast('Waktu berhasil Diset', 'success');
                            this.startCountdown();
                        } else {
                            throw new Error(result.message || 'Gagal menyimpan pengaturan');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showToast(error.message || 'Terjadi kesalahan saat menyimpan', 'error');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalContent;
                    }
                });
            }

            startCountdown() {
                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval);
                }

                if (!this.targetDateInput.value) {
                    this.updateCountdownDisplay(null);
                    return;
                }

                const targetDate = new Date(this.targetDateInput.value);
                this.updateCountdown(targetDate);

                this.countdownInterval = setInterval(() => {
                    this.updateCountdown(targetDate);
                }, 1000);
            }

            updateCountdown(targetDate) {
                const now = new Date();
                const diff = targetDate - now;

                if (diff <= 0) {
                    clearInterval(this.countdownInterval);
                    this.updateCountdownDisplay(null, true);
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                this.updateCountdownDisplay({
                    days,
                    hours,
                    minutes,
                    seconds
                });
            }

            updateCountdownDisplay(time, isExpired = false) {
                const countdownDisplay = document.getElementById('countdownDisplay');
                const countdownMessage = document.getElementById('countdownMessage');

                if (!countdownDisplay || !countdownMessage) {
                    console.error('Countdown elements not found');
                    return;
                }

                if (!time) {
                    countdownDisplay.style.display = 'none';
                    countdownMessage.innerHTML =
                        '<span class="badge bg-danger">Belum ada waktu pengumuman yang diatur</span>';
                    return;
                }

                if (isExpired) {
                    countdownDisplay.style.display = 'none';
                    countdownMessage.innerHTML = '<span class="badge bg-success">Waktu pengumuman telah tiba!</span>';
                    return;
                }

                countdownDisplay.style.display = 'block';
                countdownMessage.innerHTML = '';

                const updateElement = (id, value) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = String(value).padStart(2, '0');
                };

                updateElement('days', time.days);
                updateElement('hours', time.hours);
                updateElement('minutes', time.minutes);
                updateElement('seconds', time.seconds);
            }

            showToast(message, type) {
                const toastElement = document.getElementById('notificationToast');
                if (!toastElement) return;

                if (type === 'success') {
                    const toastBody = toastElement.querySelector('.toast-body');
                    toastBody.innerHTML = `
                        <span class="fw-medium">${message} ⌚</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    `;
                    toastBody.className =
                        'd-flex justify-content-between align-items-center bg-success text-white py-3 px-4 toast-body';
                } else {
                    const toastBody = toastElement.querySelector('.toast-body');
                    toastBody.innerHTML = `
                        <span class="fw-medium">${message}</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    `;
                    toastBody.className =
                        'd-flex justify-content-between align-items-center bg-danger text-white py-3 px-4 toast-body';
                }

                toastElement.className = 'toast rounded-3 overflow-hidden';

                this.toast.show();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new CountdownAdmin();
        });
    </script>

    <style>
        .Main-container {
            backdrop-filter: blur(10px);
            background-color: rgba(55, 55, 55, 0.1);
        }

        #countdownForm {
            max-width: 600px;
            margin: 0 auto;
        }

        .bg-gradient-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .text-gradient {
            background: linear-gradient(45deg, #4e73df, #224abe);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .countdown-item {
            min-width: 120px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        @media (max-width: 768px) {
            .countdown-number {
                font-size: 2.5rem;
            }

            .countdown-item {
                min-width: 90px;
                padding: 1rem;
            }
        }
    </style>
@endsection
