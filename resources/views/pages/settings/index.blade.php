@extends('layouts.master')

@section('title', 'Settings')

@section('content')
    <section class="glass-card p-5">
        <h2 class="text-lg font-semibold text-slate-900">Application Settings</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola identitas aplikasi dan aset brand.</p>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-5">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mac-label">Company Name</label>
                    <input type="text" name="company_name" value="{{ $companyName }}" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">App Name</label>
                    <input type="text" name="app_name" value="{{ $appName }}" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">Timezone</label>
                    <input type="text" name="timezone" value="{{ $timezone }}" class="mac-input" required>
                </div>
                <div>
                    <label class="mac-label">Country</label>
                    <input type="text" name="country" value="{{ $country }}" class="mac-input" required>
                </div>
                <div class="md:col-span-2">
                    <label class="mac-label">Address</label>
                    <textarea name="address_company" rows="3" class="mac-input">{{ $addressCompany }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="mac-label">Slogan</label>
                    <input type="text" name="slogan" value="{{ $slogan }}" class="mac-input">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @php
                    $logos = [
                        'logo' => ['label' => 'Main Logo', 'value' => $logo],
                        'favicon' => ['label' => 'Favicon', 'value' => $favicon],
                        'logo_dark_sm' => ['label' => 'Logo Dark SM', 'value' => $logoDarkSm],
                        'logo_dark_lg' => ['label' => 'Logo Dark LG', 'value' => $logoDarkLg],
                        'logo_light_sm' => ['label' => 'Logo Light SM', 'value' => $logoLightSm],
                        'logo_light_lg' => ['label' => 'Logo Light LG', 'value' => $logoLightLg],
                    ];
                @endphp

                @foreach ($logos as $key => $logoData)
                    <article class="rounded-2xl border border-slate-200 bg-white p-4">
                        <label class="mac-label">{{ $logoData['label'] }}</label>
                        <input type="file" name="{{ $key }}" class="mac-input">
                        @if ($logoData['value'])
                            <img src="{{ asset('storage/' . $logoData['value']) }}" alt="{{ $logoData['label'] }}" class="mt-3 h-12 rounded-lg border border-slate-200 bg-white p-1 object-contain">
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="flex justify-end">
                <button type="submit" class="mac-btn-primary">Save Settings</button>
            </div>
        </form>
    </section>
@endsection
