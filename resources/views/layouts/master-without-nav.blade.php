<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark"
    data-sidebar-size="sm-hover" data-preloader="disable" card-layout="" data-bs-theme="light">

<head>

    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }} - Admin & Dashboard Template </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="1112-Project Admin & Dashboard Template" name="description" />
    <meta content="1112-Project" name="author" />
    <!-- App favicon -->

    @php
        $favicon = get_setting('favicon', 'default-favicon.ico');
    @endphp
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    {{-- <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon"> --}}

    @include('layouts.head-css')
</head>

@yield('body')

@yield('content')

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

@include('layouts.vendor-scripts')
<script>
    $(document).ready(function() {
        // Menampilkan toast secara otomatis jika ada session
        $('.toast').toast('show');
    });
</script>
</body>

</html>
