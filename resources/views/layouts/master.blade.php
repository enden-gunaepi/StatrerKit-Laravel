<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark"
    data-sidebar-size="sm-hover" data-preloader="disable" card-layout="" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }} - Admin & Dashboard Template </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="1112-Project" name="author" />
    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}"> --}}

    <!-- Dynamic Favicon -->
    @php
        $timezone = get_setting('timezone', 'Asia/Jakarta'); // Ambil zona waktu yang tersimpan

        $favicon = get_setting('favicon', 'default-favicon.ico');
    @endphp
    @if ($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}" type="image/x-icon">
    @endif

    @include('layouts.head-css')
</head>

{{-- @section('body') --}}

<body>
    {{-- @show --}}
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Menampilkan Tanggal dan Jam -->
                    <div id="current-time" class="text-end"></div>
                    <div class="toast custom-toast text-bg-danger border-0" id="deleteToast" role="alert"
                        aria-live="assertive" aria-atomic="true" style="display: none;">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong id="toastTitle">Konfirmasi</strong>
                                <p id="toastMessage">Apakah Anda yakin?</p>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-footer d-flex justify-content-between">
                            <button class="btn btn-danger" id="confirmBtn" onclick="confirmAction()">Ya</button>
                            <button class="btn btn-secondary" onclick="cancelAction()">Tidak</button>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div id="pjax-container">
                        @yield('content')
                    </div>
                    <?php $start = microtime(true); ?>

                    <?php $end = microtime(true); ?>
                    <p>Halaman dimuat dalam {{ number_format(($end - $start) * 1000, 2) }} ms</p>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
        @if (session('success'))
            <div class="toast text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>Success:</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @include('layouts.customizer')


    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
    <script>
        // Mengambil zona waktu yang dikirim dari Laravel ke JavaScript
        const timezone = @json($timezone);

        function updateTime() {
            // Mendapatkan waktu saat ini dengan zona waktu yang ditentukan
            const now = new Date().toLocaleString("en-US", {
                timeZone: timezone
            });

            // Membuat objek Date berdasarkan string waktu
            const date = new Date(now);

            // Mendapatkan hari, bulan, tahun, jam, menit, dan detik
            const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            const monthsOfYear = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ];

            let dayOfWeek = daysOfWeek[date.getDay()];
            let month = monthsOfYear[date.getMonth()];
            let day = date.getDate();
            let year = date.getFullYear();
            let hours = date.getHours();
            let minutes = date.getMinutes();
            let seconds = date.getSeconds();

            // Menambahkan angka 0 di depan jika jam, menit, atau detik kurang dari 10
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            // Format waktu dalam bentuk "Day, Month Day, Year - HH:MM:SS"
            const timeString = `${dayOfWeek}, ${month} ${day}, ${year} - ${hours}:${minutes}:${seconds}`;

            // Menampilkan waktu pada elemen dengan id 'current-time'
            document.getElementById('current-time').textContent = timeString;
        }
        updateTime();

        // Memanggil fungsi updateTime setiap 1000 ms (1 detik)
        setInterval(updateTime, 1000);
    </script>
    <script>
        $(document).ready(function() {
            $('.toast').toast({
                delay: 2000
            }).toast('show');

            $('.toast').on('hidden.bs.toast', function() {
                $(this).remove();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).pjax('a', '#pjax-container', {
                fragment: '#pjax-container',
                timeout: 5000
            });
        });
    </script>
</body>

</html>
