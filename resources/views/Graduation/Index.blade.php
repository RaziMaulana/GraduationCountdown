@extends('Graduation.layouts.app')

@section('content')
    <div class="position-absolute z-3 top-0 start-0 m-3">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger" title="Logout">
                <i class="bi bi-box-arrow-left fs-4"></i>
            </button>
        </form>
    </div>

    <div class="d-flex flex-column mt-4 justify-content-center align-items-center" style="min-height: calc(100vh - 180px);">
        <div id="countdown-container" class="text-center my-3 p-3 p-md-5 border rounded-5 mx-auto"
            style="max-width: 800px; width: 100%; backdrop-filter: blur(10px); background-color: rgba(55, 55, 55, 0.1);">
            <img src="/image/LambangSmk6.png" class="img-fluid mb-3" style="max-width: 120px; height: auto;">
            <h2 class="mb-4 text-white poppins-regular">PENGUMUMAN KELULUSAN <br class="graduation-year"> <span
                    id="graduationYear">2024/2025</span></h2>

            <div class="countdown-display d-flex flex-wrap justify-content-center gap-2 gap-md-3 poppins-regular mb-4">
                <div class="countdown-item bg-light rounded-3 p-5 text-center" style="min-width: 80px; max-width: 140px;">
                    <div class="display-4 countdown-number fw-bold text-dark" id="days">00</div>
                    <div class="countdown-label text-muted">Hari</div>
                </div>
                <div class="countdown-item bg-light rounded-3 p-5 text-center" style="min-width: 80px; max-width: 140px;">
                    <div class="display-4 countdown-number fw-bold text-dark" id="hours">00</div>
                    <div class="countdown-label text-muted">Jam</div>
                </div>
                <div class="countdown-item bg-light rounded-3 p-5 text-center" style="min-width: 80px; max-width: 140px;">
                    <div class="display-4 countdown-number fw-bold text-dark" id="minutes">00</div>
                    <div class="countdown-label text-muted">Menit</div>
                </div>
                <div class="countdown-item bg-light rounded-3 p-5 text-center" style="min-width: 80px; max-width: 140px;">
                    <div class="display-4 countdown-number fw-bold text-dark" id="seconds">00</div>
                    <div class="countdown-label text-muted">Detik</div>
                </div>
            </div>
            <div id="countdown-end-time" class="mt-3 countdown-end-time text-white poppins-regular">
                Countdown berakhir: <br>
                <span id="countdown-end-date"></span>
            </div>
            <div id="countdown-message" class="mt-3"></div>
        </div>
    </div>

    <style>
        .countdown-item {
            transition: all 0.3s ease;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08);
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .countdown-number {
            transition: all 0.5s ease;
            font-size: 2rem;
        }

        .countdown-number.changing {
            transform: scale(1.1);
            color: #0d6efd !important;
        }

        .countdown-label {
            font-size: 0.8rem;
        }

        .countdown-end-time {
            letter-spacing: 1px;
        }

        @media (min-width: 768px) {
            .countdown-number {
                font-size: 2.8rem;
            }

            .countdown-label {
                font-size: 1rem;
            }

            .countdown-item {
                min-width: 140px;
                padding: 1.5rem !important;
            }
        }
    </style>

    <script>
        document.getElementById('logout-form').addEventListener('submit', function() {
            this.classList.add('processing');
        });

        class Countdown {
            constructor() {
                this.elements = {
                    container: document.getElementById('countdown-container'),
                    days: document.getElementById('days'),
                    hours: document.getElementById('hours'),
                    minutes: document.getElementById('minutes'),
                    seconds: document.getElementById('seconds'),
                    message: document.getElementById('countdown-message'),
                    endTime: document.getElementById('countdown-end-time'),
                    endDate: document.getElementById('countdown-end-date')
                };
                this.targetTimestamp = 0;
                this.animationId = null;
                this.lastUpdate = 0;
                this.init();
            }

            async init() {
                const savedTimestamp = localStorage.getItem('countdownTarget');
                if (savedTimestamp) {
                    this.targetTimestamp = parseInt(savedTimestamp);
                    this.updateUI();
                    this.startCountdown();
                }

                await this.fetchTargetDate();

                setInterval(() => {
                    if (!document.getElementById('logout-form').classList.contains('processing')) {
                        this.fetchTargetDate();
                    }
                }, 30000);
            }

            async fetchTargetDate() {
                try {
                    const timestamp = new Date().getTime();
                    const response = await fetch(`/kelulusan/countdown?t=${timestamp}`);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.targetDate) {
                        this.targetTimestamp = data.targetDate;
                        localStorage.setItem('countdownTarget', this.targetTimestamp);
                        this.updateUI();
                        if (!this.animationId) {
                            this.startCountdown();
                        }

                        // Update graduation year
                        if (data.graduationYear) {
                            document.getElementById('graduationYear').textContent = data.graduationYear;
                        }
                    } else {
                        this.showMessage('Waktu pengumuman belum ditetapkan', 'warning');
                        this.resetCountdown();
                        this.stopCountdown();
                        this.targetTimestamp = 0;
                        localStorage.removeItem('countdownTarget');
                    }
                } catch (error) {
                    console.error('Error fetching countdown:', error);
                    this.showMessage('Gagal memuat data countdown', 'danger');
                    const savedTimestamp = localStorage.getItem('countdownTarget');
                    if (savedTimestamp && !this.animationId) {
                        this.targetTimestamp = parseInt(savedTimestamp);
                        this.updateUI();
                        this.startCountdown();
                    }
                }
            }

            startCountdown() {
                if (this.animationId) return;

                const animate = (timestamp) => {
                    if (!this.lastUpdate || timestamp - this.lastUpdate >= 1000) {
                        this.lastUpdate = timestamp;
                        this.updateCountdown();
                    }
                    this.animationId = requestAnimationFrame(animate);
                };
                this.animationId = requestAnimationFrame(animate);
            }

            updateCountdown() {
                if (!this.targetTimestamp) {
                    this.resetCountdown();
                    return;
                }

                const now = Date.now();
                const distance = this.targetTimestamp - now;

                if (distance <= 0) {
                    this.showMessage('Pengumuman Telah Dimulai! Mengalihkan ke halaman hasil...', 'success');
                    this.stopCountdown();

                    localStorage.removeItem('countdownTarget');

                    setTimeout(() => {
                        const userStatus = "{{ Auth::user()->status ?? 'Tidak Lulus' }}";
                        window.location.href = "{{ route('kelulusan.hasil') }}/" + encodeURIComponent(
                            userStatus);
                    }, 1000);

                    return;
                }

                this.calculateTimeUnits(distance);
                this.animateNumbers();
                this.updateEndTime();
            }

            calculateTimeUnits(distance) {
                this.timeUnits = {
                    days: Math.floor(distance / (1000 * 60 * 60 * 24)),
                    hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds: Math.floor((distance % (1000 * 60)) / 1000)
                };
            }

            animateNumbers() {
                Object.keys(this.timeUnits).forEach(unit => {
                    const element = this.elements[unit];
                    const newValue = this.timeUnits[unit].toString().padStart(2, '0');

                    if (element.textContent !== newValue) {
                        element.classList.add('changing');
                        element.textContent = newValue;
                        setTimeout(() => element.classList.remove('changing'), 500);
                    }
                });
            }

            updateUI() {
                this.elements.message.textContent = '';
                this.elements.container.style.display = 'block';
                this.elements.container.querySelector('.countdown-display').style.display = 'flex';
                this.updateEndTime();
            }

            updateEndTime() {
                if (this.targetTimestamp) {
                    const endDate = new Date(this.targetTimestamp);
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        timeZone: 'Asia/Makassar', // WITA timezone
                        hour12: false // Format 24 jam
                    };
                    const formattedDate = endDate.toLocaleDateString('id-ID', options);
                    this.elements.endDate.textContent = `${formattedDate} WITA`;
                } else {
                    this.elements.endDate.textContent = '';
                }
            }

            showMessage(text, type) {
                this.elements.message.innerHTML =
                    `<div class="alert alert-${type}">${text}</div>`;
                if (type === 'success' || type === 'warning') {
                    this.elements.container.querySelector('.countdown-display').style.display = 'none';
                }
            }

            resetCountdown() {
                ['days', 'hours', 'minutes', 'seconds'].forEach(unit => {
                    this.elements[unit].textContent = '00';
                });
                this.elements.container.querySelector('.countdown-display').style.display = 'none';
                this.elements.endDate.textContent = '';
            }

            stopCountdown() {
                if (this.animationId) {
                    cancelAnimationFrame(this.animationId);
                    this.animationId = null;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new Countdown();
        });
    </script>
@endsection
