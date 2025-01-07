<!-- PAGE -->
<div class="page">
    <div class="page-main">
        <!-- app-Header -->
        <div class="app-header header sticky fixed-header visible-title stickyClass" style="margin-bottom: -74px;">
            <div class="container-fluid main-container">

                <div class="d-flex align-items-center">

                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0);"></a>
                    <div class="responsive-logo">
                        <a href="/" class="header-logo">
                            <img src="{{ asset('assets/images/brand/logo3.png') }}" class="mobile-logo logo-1" alt="logo">
                            <img src="{{ asset('assets/images/brand/logo3.png') }}" class="mobile-logo dark-logo-1" alt="logo">
                        </a>
                    </div>
                    <!-- sidebar-toggle-->
                    <a class="logo-horizontal" href="/">
                        <img src="{{ asset('assets/images/brand/logo3.png') }}" class="header-brand-img desktop-logo" alt="logo">
                        <img src="{{ asset('assets/images/brand/logo3.png') }}" class="header-brand-img light-logo1" alt="logo">
                    </a>
                    <!-- LOGO -->
                    <div class="d-flex order-lg-2 ms-auto header-right-icons">
                        <div class="navbar navbar-collapse responsive-navbar p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2">
                                    <!-- Theme-Layout -->


                                    <div class="dropdown d-md-flex">
                                        <a class="nav-link icon full-screen-link nav-link-bg">
                                            <i class="fe fe-minimize fullscreen-button"></i>
                                        </a>
                                    </div>
                                    <div class="dropdown d-md-flex profile-1">
                                        <a href="javascript:void(0);" data-bs-toggle="dropdown" class="nav-link leading-none d-flex px-1">
                                            <span>
                                                <img src="{{ auth()->user()->profile_picture }}" alt="profile-user" class="avatar profile-user brround cover-image">
                                            </span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading">
                                                <div class="text-center">
                                                    <h5 class="text-dark mb-0">{{ auth()->user()->name }}</h5>
                                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a class="dropdown-item" href="{{ route('user-profile.show', Auth::id()) }}"><i class="dropdown-icon fe fe-user"></i> Profile </a>
                                            <a class="dropdown-item" href="{{ route('change-password') }}"><i class="dropdown-icon fe fe-lock"></i> Change Password </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <a class="dropdown-item"
                                                    href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                                    <i class="dropdown-icon fe fe-alert-circle"></i>
                                                    Sign out
                                                </a>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- SIDE-MENU -->
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="ms-auto"><h1 class="page-title">@yield('page-title')</h1></div>

                </div>

            </div>
        </div>
        <!-- /app-Header -->
        <!--APP-SIDEBAR-->
        <div class="sticky stickyClass" style="margin-bottom: -74px;">
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            <aside class="app-sidebar ps ps--active-y sidemenu-scroll">
                <div class="side-header">
                    <a class="header-brand1" href="/">
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo" />
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img toggle-logo" alt="logo" />
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img light-logo" alt="logo" />
                        <img src="{{ asset('assets/images/brand/logo3.png') }}" class="header-brand-img light-logo1" alt="logo" style="width:90px; height:50px" />
                    </a>
                    <!-- LOGO -->
                </div>
                <div class="main-sidemenu is-expanded">
                    <div class="slide-left disabled active" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                        </svg>
                    </div>

                    <ul class="side-menu open" style="margin-right: 0px;">
                        <li class="sub-category">
                            <h3>Menu</h3>
                        </li>
                        <li class="slide">
                            {{-- <a class="side-menu__item {{ str_before('dashboard', '/') == Request::segment(1) ? 'active' : '' }}" data-bs-toggle="slide" href="{{ route('dashboard') }}"> --}}
                            <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard') }}">
                                <i class="side-menu__icon fe fe-home"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        @foreach ($menus as $menuKey => $menu)
                        <li class="slide">
                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="side-menu__icon fa {{ $menu->icon }}"></i>
                                <span class="side-menu__label">{{ $menu->name }}</span>
                                <i class="angle fa fa-angle-right"></i>
                            </a>
                            <ul class="slide-menu">
                                @foreach ($menu->systemSubMenus as $systemSubMenu)
                                <li>
                                    <a href="{{ url($systemSubMenu->route) }}" class="slide-item">{{ $systemSubMenu->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="side-menu__item"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    <i class="side-menu__icon fa fa-sign-out"></i>
                                    <span class="side-menu__label">Logout</span>
                                </a>
                            </form>

                        </li>
                    </ul>
                    <div class="slide-right" id="slide-right">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ps__rail-x" style="left: 0px; bottom: -380px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 380px; height: 492px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 164px; height: 212px;"></div>
                </div>
            </aside>
        </div>
        <!--/APP-SIDEBAR-->
