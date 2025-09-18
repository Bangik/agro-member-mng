<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Document</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
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
                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 p-2"
                        type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icon-base ri ri-close-fill"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-medium" aria-current="page" href="{{ route('landing') }}">Home</a>
                        </li>
                    </ul>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                <!-- Toolbar: Start -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">

                    <!-- navbar button: Start -->
                    <li>
                        <a href="{{ route('login') }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4"
                            target="_blank">
                            <span class="icon-base ri ri-user-line me-md-1 icon-18px"></span><span
                                class="d-none d-md-block">Login</span></a>
                    </li>
                    <!-- navbar button: End -->
                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>

    <!-- Sections:Start -->
    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="landingHero" class="section-py landing-hero position-relative">
            <img src="{{ asset('assets/img/illustrations/hero-bg-light.png') }}" alt="hero background"
                class="position-absolute top-0 start-0 w-100 h-100 z-n1" data-speed="1"
                data-app-light-img="front-pages/backgrounds/hero-bg-light.png"
                data-app-dark-img="front-pages/backgrounds/hero-bg-dark.png" />
            <div class="container">
                <div class="hero-text-box text-center">
                    <h2 class="text-primary hero-title mb-4">
                        Satu pintu untuk aspirasi & aduan staff
                    </h2>
                    <h2 class="h6 mb-8 lh-md">
                        Mudahkan komunikasi dan tingkatkan transparansi.<br />
                        Sampaikan ide, keluhan, atau masukan kapan saja, tanpa ribet.
                    </h2>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Mulai sampaikan aspirasi</a>
                </div>

                <div class="position-relative hero-animation-img">
                    <div class="hero-dashboard-img text-center">
                        <img src="{{ asset('assets/img/illustrations/sitting-girl-with-laptop.png') }}"
                            alt="hero dashboard" class="animation-img" data-speed="2"
                            data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                            data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero: End -->
    </div>
    <!-- / Sections:End -->
</body>

</html>
