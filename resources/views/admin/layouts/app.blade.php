<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

    <style>
        body {
            background-color: rgb(49, 54, 63);
        }

        .sidebar {
            background-color: rgb(24, 28, 20);
            min-height: 100vh;
            /* Ensure sidebar takes full height */
        }

        .sidebar-header-image {
            width: 40px;
            height: auto;
        }

        .nav-link {
            transition: all 0.3s ease;
            width: fit-content;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3) !important;
            font-weight: bold;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }

        .sidebar-header {
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-content {
            background-color: rgb(49, 54, 63);
            /* Match body background */
            padding: 20px;
            /* Add padding for better spacing */
        }
    </style>
    @stack('styles') <!-- Optional: for page-specific styles -->
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="sidebar-header text-white p-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ asset('image/LambangSmk6.png') }}" class="sidebar-header-image me-3">
                        <h4 class="m-0">SMKN 6</h4>
                    </div>
                </div>
                <div class="position-sticky pt-2 d-flex flex-column justify-content-center"
                    style="height: calc(100vh - 80px);">
                    <ul class="nav flex-column text-center">
                        <li class="nav-item mx-auto mb-3">
                            <a class="nav-link {{ request()->routeIs('admin.overview') ? 'active' : '' }} text-white py-2 px-4 rounded-pill"
                                href="{{ route('admin.overview') }}">
                                <i class="fas fa-chart-pie me-2"></i> OVERVIEW
                            </a>
                        </li>
                        <li class="nav-item mx-auto mt-3">
                            <a class="nav-link {{ request()->routeIs('admin.time-setting') ? 'active' : '' }} text-white py-2 px-4 rounded-pill"
                                href="{{ route('admin.time-setting') }}">
                                <i class="fas fa-clock me-2"></i> SET TIME
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @yield('admin-content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    @stack('scripts') <!-- Optional: for page-specific scripts -->
</body>

</html>
