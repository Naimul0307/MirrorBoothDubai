@extends('layouts.app')

@section('content')
<section class="hero">
    <div id="carouselExampleControls"
         class="carousel slide carousel-fade"
         data-bs-ride="carousel"
         data-bs-interval="4000">

        <div class="carousel-inner">
            @foreach($heroSlides as $key => $slide)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">

                    <img
                        src="{{ asset('uploads/hero_slides/thumb/large/'.$slide->image) }}"
                        class="hero-slide-img"
                        alt="{{ $slide->name ?? 'Mirror Booth Dubai' }}"
                        loading="lazy">

                    <div class="hero-background-overlay"></div>

                    <div class="hero-content">
                        <div class="container h-100">
                            <div class="row align-items-center justify-content-center h-100">
                                <div class="col-md-8 col-10 text-center">
                                    <h1>PHOTOBOOTH INTERACTIVE ENTERTAINMENT EXPERIENCES</h1>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
<section class="section-2 py-5">
    <div class="container py-2">
        <div class="about-block">
            <h2 class="title-color">MIRROR BOOTH DUBAI</h2>
            <div class="divider-container">
                <div class="divider mb-3"></div>
            </div>
            <div class="text-muted">Award-Winning Photo Booth & Game Rentals in Dubai</div>
            <p> A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games,
            <br>providing the most comprehensive range of services in the GCC.
            <br>Renowned for our professionalism and reliability, we are dedicated to client satisfaction and event success.
            <br>Our goal is to craft memorable, branded experiences that elevate every occasion.</p>
        </div>
    </div>
</section>


@include('common.services')

@include('common.review', [
    'reviews'      => [],
    'rating'       => 5.0,
    'totalReviews' => 0,
    'businessName' => 'Mirror Booth Dubai',
])

@include('common.company')

@endsection

