@extends('Graduation.layouts.app')

@section('content')
    <!-- Logout Button in Top Left Corner -->
    <div class="position-absolute top-0 start-0 m-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger" title="Logout">
                <i class="bi bi-box-arrow-left fs-4"></i>
            </button>
        </form>
    </div>

    <!-- Centered Countdown Content -->
    <div class="container d-flex flex-column min-vh-100 justify-content-center">
        <!-- Countdown Timer - Made slightly wider with col-md-10 -->
        <div id="countdown-container" class="text-center my-5 p-5 countdown-container border rounded col-md-10 col-lg-8 mx-auto">
            <img src="image/LambangSmk6.png" class="img-fluid mb-3" style="max-width: 120px; height: auto;">
            <h2 class="mb-4 text-white">PENGUMUMAN KELULUSAN <br> 2024/2025</h2>

            <div class="countdown-display d-flex justify-content-center gap-3">
                <div class="countdown-item">
                    <div class="display-4 countdown-number" id="days">00</div>
                    <div class="countdown-label">Hari</div>
                </div>
                <div class="countdown-item">
                    <div class="display-4 countdown-number" id="hours">00</div>
                    <div class="countdown-label">Jam</div>
                </div>
                <div class="countdown-item">
                    <div class="display-4 countdown-number" id="minutes">00</div>
                    <div class="countdown-label">Menit</div>
                </div>
                <div class="countdown-item">
                    <div class="display-4 countdown-number" id="seconds">00</div>
                    <div class="countdown-label">Detik</div>
                </div>
            </div>
            <div id="countdown-message" class="mt-3"></div>
        </div>
    </div>

    <!-- Rest of your JavaScript code remains the same -->
    <script>
        class Countdown {
            constructor() {
                this.elements = {
                    container: document.getElementById('countdown-container'),
                    days: document.getElementById('days'),
                    hours: document.getElementById('hours'),
                    minutes: document.getElementById('minutes'),
                    seconds: document.getElementById('seconds'),
                    message: document.getElementById('countdown-message')
                };
                this.targetTimestamp = 0;
                this.animationId = null;
                this.lastUpdate = 0;
                this.init();
            }

            async init() {
                await this.fetchTargetDate();
                this.startCountdown();
                setInterval(() => this.fetchTargetDate(), 30000);
            }

            async fetchTargetDate() {
                try {
                    const timestamp = new Date().getTime();
                    const response = await fetch(`/Graduation/countdown?t=${timestamp}`);
                    const data = await response.json();

                    if (data.targetDate) {
                        this.targetTimestamp = new Date(data.targetDate).getTime();
                        this.updateUI();
                        if (!this.animationId) {
                            this.startCountdown();
                        }
                    } else {
                        this.showMessage('Waktu pengumuman belum ditetapkan', 'warning');
                        this.resetCountdown();
                        this.stopCountdown();
                        this.targetTimestamp = 0;
                    }
                } catch (error) {
                    console.error('Error fetching countdown:', error);
                    this.showMessage('Gagal memuat data countdown', 'danger');
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
                    this.showMessage('Pengumuman Telah Dimulai!', 'success');
                    this.stopCountdown();
                    return;
                }

                this.calculateTimeUnits(distance);
                this.animateNumbers();
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
            }

            showMessage(text, type) {
                this.elements.message.innerHTML =
                    `<div class="alert alert-${type}">${text}</div>`;
                if (type === 'success' || type === 'warning') {
                    this.elements.container.querySelector('.countdown-display').style.display = 'none';
                }
            }

            resetCountdown() {
                Object.keys(this.elements).forEach(key => {
                    if (['days', 'hours', 'minutes', 'seconds'].includes(key)) {
                        this.elements[key].textContent = '00';
                    }
                });
                this.elements.container.querySelector('.countdown-display').style.display = 'none';
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

    <style>
        .countdown-container{
            backdrop-filter: blur(10px);
            background-color: rgba(55, 55, 55, 0.1);
        }

        .countdown-item {
            background: rgba(248, 249, 250, 0.9);
            border-radius: 12px;
            padding: 1.5rem 1.5rem; /* Increased padding */
            min-width: 140px; /* Increased min-width */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .countdown-number {
            font-weight: 700;
            color: #2c3e50;
            transition: all 0.5s ease;
            font-size: 2.8rem; /* Slightly larger font */
        }

        .countdown-number.changing {
            transform: scale(1.1);
            color: #0d6efd;
        }

        .countdown-label {
            font-size: 1rem; /* Restored original size */
            color: #6c757d;
            margin-top: 0.5rem;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }
    </style>
@endsection
