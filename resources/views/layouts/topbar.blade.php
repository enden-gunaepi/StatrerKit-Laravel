<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    @php
                        $logoDarkSm = get_setting('logo_dark_sm');
                        $logoDarkLg = get_setting('logo_dark_lg');
                        $logoLightSm = get_setting('logo_light_sm');
                        $logoLightLg = get_setting('logo_light_lg');
                    @endphp

                    <a href="#" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ $logoDarkSm && Storage::disk('public')->exists($logoDarkSm)
                                ? asset('storage/' . $logoDarkSm)
                                : asset('build/images/logoipsum-282.svg') }}"
                                alt="Logo Dark SM" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ $logoDarkLg && Storage::disk('public')->exists($logoDarkLg)
                                ? asset('storage/' . $logoDarkLg)
                                : asset('build/images/logoipsum-253.svg') }}"
                                alt="Logo Dark LG" height="30">
                        </span>
                    </a>

                    <a href="#" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ $logoLightSm && Storage::disk('public')->exists($logoLightSm)
                                ? asset('storage/' . $logoLightSm)
                                : asset('build/images/logoipsum-282.svg') }}"
                                alt="Logo Light SM" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ $logoLightLg && Storage::disk('public')->exists($logoLightLg)
                                ? asset('storage/' . $logoLightLg)
                                : asset('build/images/logoipsum-253.svg') }}"
                                alt="Logo Light LG" height="30">
                        </span>
                    </a>

                </div>

                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none"
                    id="topnav-hamburger-icon" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    data-bs-trigger="hover" title="Toogle Sidebar">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text"
                        data-toggle="fullscreen">
                        <i class='ti ti-arrows-maximize fs-3xl'></i>
                    </button>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text mode-layout"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-sun align-middle fs-3xl"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        <a href="#!" class="dropdown-item" data-mode="light"><i
                                class="bi bi-sun align-middle me-2"></i> Light</a>
                        <a href="#!" class="dropdown-item" data-mode="dark"><i
                                class="bi bi-moon align-middle me-2"></i> Dark</a>
                        <a href="#!" class="dropdown-item" data-mode="auto"><i
                                class="bi bi-moon-stars align-middle me-2"></i> System Default</a>
                    </div>
                </div>

                <div class="dropdown ms-sm-3 topbar-head-dropdown dropdown-hover-end header-item ">
                    <button type="button" class="btn shadow-none btn-icon" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">

                            <img class="rounded-circle header-profile-user"
                                @if (auth()->user()->profile_picture && Storage::disk('public')->exists(auth()->user()->profile_picture)) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                alt="Header Avatar" class="bi bi-person-circle profile-icon me-2"> @endif
                                <span class="text-start ms-xl-2">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        {{-- <h6 class="dropdown-header">Welcome Alexandra!</h6> --}}
                        <a class="dropdown-item fs-sm" href="#!"><i
                                class="bi bi-person-circle text-muted align-middle me-1"></i> <span
                                class="align-middle">{{ Auth::user()->name ?? 'Username' }}</span></a>
                        <a class="dropdown-item fs-sm" href="#!"><i
                                class="bi bi-envelope-at text-muted align-middle me-1"></i> <span
                                class="align-middle">{{ Auth::user()->email ?? 'Email' }}</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item fs-sm" href="{{ route('users.profile') }}"><i
                                class="bi bi-gear text-muted align-middle me-1"></i> <span
                                class="align-middle">@lang('translation.setting')</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item fs-sm">
                                <i class="bi bi-box-arrow-right text-muted align-middle me-1"></i>
                                <span class="align-middle">@lang('translation.logout')</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="wrapper"></div>
