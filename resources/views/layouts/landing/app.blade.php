<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')

    <style>
        .landing-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            display: none;
            pointer-events: none;
            z-index: 1040;
        }
    </style>

</head>

<body>
    <nav class="layout-navbar shadow-none py-0">
        <div class="container">
            <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <!-- Menu logo wrapper: Start -->
                <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-6">
                    <!-- Mobile menu toggle: Start-->
                    <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icon-base ri ri-menu-fill icon-lg align-middle text-heading fw-medium"></i>
                    </button>
                    <!-- Mobile menu toggle: End-->
                    <a href="{{ route('landing') }}" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-semibold ms-2 ps-1">Aspirasi/Aduan</span>
                    </a>
                </div>
                <!-- Menu logo wrapper: End -->
                <!-- Menu wrapper: Start -->
                <div class="collapse navbar-collapse landing-nav-menu bg-white" id="navbarSupportedContent">
                    <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icon-base ri ri-close-fill"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-medium" aria-current="page" href="{{ route('landing') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="{{ route('profile') }}">Profile</a>
                        </li>
                        @if (Auth::check())
                            <li class="nav-item">
                                <a class="nav-link fw-medium" href="{{ route('home') }}">Dashboard</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                <!-- Toolbar: Start -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">

                    <!-- navbar button: Start -->
                    <li>
                        @if (Auth::check())
                            <a class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <span class="icon-base ri ri-user-line me-md-1 icon-18px"></span><span
                                    class="d-none d-md-block">Keluar</span></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4">
                                <span class="icon-base ri ri-user-line me-md-1 icon-18px"></span><span
                                    class="d-none d-md-block">Masuk</span></a>
                        @endif
                    </li>
                    <!-- navbar button: End -->
                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>

    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menu = document.getElementById('navbarSupportedContent');
            const toggles = document.querySelectorAll(
                '[data-bs-toggle="collapse"][data-bs-target="#navbarSupportedContent"]');
            const overlay = document.querySelector('.landing-menu-overlay');

            if (!menu || toggles.length === 0) return;

            const openMenu = () => {
                menu.classList.add('show'); // .collapse.show => tampil (CSS bootstrap)
                toggles.forEach(t => t.setAttribute('aria-expanded', 'true'));
                if (overlay) {
                    overlay.style.display = 'block';
                    overlay.style.pointerEvents = 'auto';
                }
                document.body.style.overflow = 'hidden'; // cegah scroll body saat menu terbuka (mobile)
            };

            const closeMenu = () => {
                menu.classList.remove('show');
                toggles.forEach(t => t.setAttribute('aria-expanded', 'false'));
                if (overlay) {
                    overlay.style.display = 'none';
                    overlay.style.pointerEvents = 'none';
                }
                document.body.style.overflow = '';
            };

            const toggleMenu = () => (menu.classList.contains('show') ? closeMenu() : openMenu());

            // tombol hamburger & tombol close di dalam menu
            toggles.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleMenu();
                });
            });

            // klik overlay untuk menutup
            overlay?.addEventListener('click', closeMenu);

            // ESC untuk menutup
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMenu();
            });

            // Jika viewport balik ke desktop, auto-close
            const mql = window.matchMedia('(min-width: 992px)');
            const onBreak = (e) => {
                if (e.matches) closeMenu();
            };
            mql.addEventListener ? mql.addEventListener('change', onBreak) : mql.addListener(onBreak);
        });
    </script>

</body>

</html>
