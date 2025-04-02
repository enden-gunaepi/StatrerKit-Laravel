@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">

            <h1>Welcome, {{ Auth::user()->name ?? 'your name' }}</h1>

            <p>Welcome to your dashboard.</p>

            <ul>
                <li>Email: {{ Auth::user()->email ?? 'your email' }}</li>
                <li>Role: {{ ucfirst(Auth::user()->roles->pluck('name')->first()) ?? 'your role' }}</li>
                <!-- Menampilkan semua role yang dimiliki pengguna -->
            </ul>
        </div>
    </div>
@endsection

@section('script')
    <!-- apexcharts -->
    {{-- <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
