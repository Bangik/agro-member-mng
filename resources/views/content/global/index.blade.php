@extends('layouts.landing.app')

@section('title', 'Aspirasi & Aduan Anggota')

@section('content')
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
                        Satu pintu untuk aspirasi & aduan Anggota
                    </h2>
                    <h2 class="h6 mb-8 lh-md">
                        Mudahkan komunikasi dan tingkatkan transparansi.<br />
                        Sampaikan ide, keluhan, atau masukan kapan saja, tanpa ribet.
                    </h2>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Mulai sampaikan aspirasi</a>
                </div>

                <div class="position-relative hero-animation-img">
                    <div class="hero-dashboard-img text-center">
                        <img src="{{ asset('assets/img/illustrations/sitting-girl-with-laptop.png') }}" alt="hero dashboard"
                            class="animation-img" data-speed="2"
                            data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                            data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero: End -->
    </div>
    <!-- / Sections:End -->
@endsection
