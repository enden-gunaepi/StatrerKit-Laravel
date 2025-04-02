<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        @php
            $logoDarkSm = get_setting('logo_dark_sm');
            $logoDarkLg = get_setting('logo_dark_lg');
            $logoLightSm = get_setting('logo_light_sm');
            $logoLightLg = get_setting('logo_light_lg');
        @endphp

        <a href="#" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ $logoDarkSm && Storage::disk('public')->exists($logoDarkSm) ? asset('storage/' . $logoDarkSm) : asset('build/images/logoipsum-282.svg') }}"
                    alt="Logo Dark SM" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $logoDarkLg && Storage::disk('public')->exists($logoDarkLg) ? asset('storage/' . $logoDarkLg) : asset('build/images/logoipsum-253.svg') }}"
                    alt="Logo Dark LG" height="30">
            </span>
        </a>

        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ $logoLightSm && Storage::disk('public')->exists($logoLightSm) ? asset('storage/' . $logoLightSm) : asset('build/images/logoipsum-282.svg') }}"
                    alt="Logo Light SM" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $logoLightLg && Storage::disk('public')->exists($logoLightLg) ? asset('storage/' . $logoLightLg) : asset('build/images/logoipsum-253.svg') }}"
                    alt="Logo Light LG" height="30">
            </span>
        </a>


        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover shadow-none"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                @if (!empty($menus) && is_array($menus))
                    @foreach ($menus as $menuGroup)
                        @php
                            $hasVisibleMenu = collect($menuGroup['menus'])->contains(
                                fn($menu) => !isset($menu['permission']) ||
                                    Gate::allows('has-permission', $menu['permission']),
                            );
                        @endphp

                        @if ($hasVisibleMenu)
                            @if (isset($menuGroup['title']) && !empty($menuGroup['title']))
                                <li class="menu-title">
                                    <span data-key="t-{{ Str::slug($menuGroup['title']) }}">
                                        {{ Lang::has('translation.' . Str::slug($menuGroup['title']))
                                            ? __('translation.' . Str::slug($menuGroup['title']))
                                            : $menuGroup['title'] }}
                                    </span>
                                </li>
                            @endif

                            @foreach ($menuGroup['menus'] as $menu)
                                @if (!isset($menu['permission']) || Gate::allows('has-permission', $menu['permission']))
                                    <li class="nav-item">
                                        @if (isset($menu['submenus']))
                                            <a class="nav-link menu-link collapsed"
                                                href="#menu-{{ Str::slug($menu['title']) }}" data-bs-toggle="collapse"
                                                role="button" aria-expanded="false"
                                                aria-controls="menu-{{ Str::slug($menu['title']) }}">
                                                <i class="{{ $menu['icon'] }}"></i>
                                                <span data-key="t-{{ $menu['data_key'] }}">
                                                    {{ Lang::has('translation.' . $menu['data_key']) ? __('translation.' . $menu['data_key']) : $menu['title'] }}
                                                </span>
                                                @if (isset($menu['badge']))
                                                    <span
                                                        class="badge badge-pill {{ $menu['badge']['class'] }} me-auto"
                                                        data-key="t-{{ $menu['badge']['data_key'] }}">
                                                        {{ Lang::has('translation.' . $menu['badge']['data_key'])
                                                            ? __('translation.' . $menu['badge']['data_key'])
                                                            : $menu['badge']['data_key'] }}
                                                    </span>
                                                @endif
                                            </a>
                                            <div class="collapse menu-dropdown"
                                                id="menu-{{ Str::slug($menu['title']) }}">
                                                <ul class="nav nav-sm flex-column">
                                                    @foreach ($menu['submenus'] as $child)
                                                        @if (!isset($child['permission']) || Gate::allows('has-permission', $child['permission']))
                                                            <li class="nav-item">
                                                                @if (isset($child['submenus']))
                                                                    <a href="#submenu-{{ Str::slug($child['title']) }}"
                                                                        class="nav-link collapsed"
                                                                        data-bs-toggle="collapse" role="button"
                                                                        aria-expanded="false"
                                                                        aria-controls="submenu-{{ Str::slug($child['title']) }}">
                                                                        {!! Lang::has('translation.' . $child['data_key']) ? __('translation.' . $child['data_key']) : $child['title'] !!}
                                                                    </a>
                                                                    <div class="collapse menu-dropdown"
                                                                        id="submenu-{{ Str::slug($child['title']) }}">
                                                                        <ul class="nav nav-sm flex-column">
                                                                            @foreach ($child['submenus'] as $subchild)
                                                                                <li class="nav-item">
                                                                                    @if (isset($subchild['submenus']))
                                                                                        <a href="#subsubmenu-{{ Str::slug($subchild['title']) }}"
                                                                                            class="nav-link collapsed"
                                                                                            data-bs-toggle="collapse"
                                                                                            role="button"
                                                                                            aria-expanded="false"
                                                                                            aria-controls="subsubmenu-{{ Str::slug($subchild['title']) }}">
                                                                                            {!! Lang::has('translation.' . $subchild['data_key'])
                                                                                                ? __('translation.' . $subchild['data_key'])
                                                                                                : $subchild['title'] !!}
                                                                                        </a>
                                                                                        <div class="collapse menu-dropdown"
                                                                                            id="subsubmenu-{{ Str::slug($subchild['title']) }}">
                                                                                            <ul
                                                                                                class="nav nav-sm flex-column">
                                                                                                @foreach ($subchild['submenus'] as $subsubchild)
                                                                                                    <li
                                                                                                        class="nav-item">
                                                                                                        <a href="{{ url($subsubchild['href']) }}"
                                                                                                            class="nav-link"
                                                                                                            data-key="t-{{ $subsubchild['data_key'] }}">
                                                                                                            {{-- @lang('translation.' . $subsubchild['data_key']) --}}
                                                                                                            {!! Lang::has('translation.' . $subsubchild['data_key'])
                                                                                                                ? __('translation.' . $subsubchild['data_key'])
                                                                                                                : $subsubchild['title'] !!}
                                                                                                        </a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @else
                                                                                        <a href="{{ url($subchild['href']) }}"
                                                                                            class="nav-link"
                                                                                            data-key="t-{{ $subchild['data_key'] }}">
                                                                                            {{-- @lang('translation.' . $subchild['data_key']) --}}
                                                                                            {!! Lang::has('translation.' . $subchild['data_key'])
                                                                                                ? __('translation.' . $subchild['data_key'])
                                                                                                : $subchild['title'] !!}
                                                                                        </a>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @else
                                                                    <a href="{{ url($child['href']) }}"
                                                                        class="nav-link"
                                                                        data-key="t-{{ $child['data_key'] }}">
                                                                        {{-- @lang('translation.' . $child['data_key']) --}}
                                                                        {!! Lang::has('translation.' . $child['data_key']) ? __('translation.' . $child['data_key']) : $child['title'] !!}
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <a class="nav-link menu-link" href="{{ url($menu['href']) }}">
                                                <i class="{{ $menu['icon'] }}"></i>
                                                <span data-key="t-{{ $menu['data_key'] }}">
                                                    {!! Lang::has('translation.' . $menu['data_key']) ? __('translation.' . $menu['data_key']) : $menu['title'] !!}
                                                </span>
                                            </a>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <li class="nav-item">
                        <a href="#" class="nav-link menu-link">
                            <span class="text-danger">No menu available</span>
                        </a>
                    </li>
                @endif
            </ul>

        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
