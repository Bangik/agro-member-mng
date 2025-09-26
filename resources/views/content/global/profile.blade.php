@extends('layouts.landing.app')

@section('title', 'Aspirasi & Aduan Anggota')

@section('content')
    <section id="landingHero" class="section-py landing-hero position-relative">
        <div class="container">
            <div class="hero-text-box text-center">
                <h2 class="text-primary hero-title mb-4">
                    Struktur Organisasi
                </h2>
            </div>

            <div class="position-relative hero-animation-img">
                <div class="hero-dashboard-img text-center">
                    <img src="{{ asset('assets/img/illustrations/struktur-org.jpg') }}" alt="hero dashboard"
                        class="animation-img" data-speed="2"
                        data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                        data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                </div>
            </div>
        </div>
    </section>
    <!-- Hero: End -->
@endsection
