@extends('layouts.master')
@section('title', 'Setting') <!-- Menambahkan title untuk halaman ini -->
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="mb-4">Pengaturan</h2>

            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h5>Pengaturan Perusahaan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}

                        <div class="row">
                            <!-- Company Info -->
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control" name="company_name" value="{{ $companyName }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label">Nama Aplikasi</label>
                                <input type="text" class="form-control" name="app_name" value="{{ $appName }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <input type="text" class="form-control" name="timezone" value="{{ $timezone }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Negara</label>
                                <input type="text" class="form-control" name="country" value="{{ $country }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address_company" class="form-label">Alamat Perusahaan</label>
                                <textarea name="address_company" class="form-control">{{ $addressCompany }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="slogan" class="form-label">Slogan</label>
                                <input type="text" class="form-control" name="slogan" value="{{ $slogan }}">
                            </div>

                            <!-- Logo -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Umum</label>
                                <input type="file" class="form-control" name="logo">
                                @if ($logo)
                                    <img src="{{ asset('storage/' . $logo) }}" class="mt-2" width="150">
                                @endif
                            </div>

                            <!-- Favicon -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Favicon</label>
                                <input type="file" class="form-control" name="favicon">
                                @if ($favicon)
                                    <img src="{{ asset('storage/' . $favicon) }}" class="mt-2" width="50">
                                @endif
                            </div>

                            <!-- Logo Variants -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Dark (SM)</label>
                                <input type="file" class="form-control" name="logo_dark_sm">
                                @if ($logoDarkSm)
                                    <img src="{{ asset('storage/' . $logoDarkSm) }}" class="mt-2" width="100">
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Dark (LG)</label>
                                <input type="file" class="form-control" name="logo_dark_lg">
                                @if ($logoDarkLg)
                                    <img src="{{ asset('storage/' . $logoDarkLg) }}" class="mt-2" width="150">
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Light (SM)</label>
                                <input type="file" class="form-control" name="logo_light_sm">
                                @if ($logoLightSm)
                                    <img src="{{ asset('storage/' . $logoLightSm) }}" class="mt-2" width="100">
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Light (LG)</label>
                                <input type="file" class="form-control" name="logo_light_lg">
                                @if ($logoLightLg)
                                    <img src="{{ asset('storage/' . $logoLightLg) }}" class="mt-2" width="150">
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
