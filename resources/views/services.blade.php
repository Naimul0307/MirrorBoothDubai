@extends('layouts.app')

@section('content')
<section class="section-3 py-5">
</section>

<section class="section-2 py-5">
    <div class="container py-2">
        <div class="about-block">
            <h1 class="title-color text-center">MIRROR BOOTH DUBAI</h1>
            <div class="divider-container text-center">
                <div class="divider mb-3"></div>
            </div>
            <div class="mt-2 mb-3 text-muted">ALL SERVICES RENTAL DUBAI</div>
            <div class="text-muted">Award-Winning Photo Booth & Game Rentals in Dubai</div>
            <p> A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games, 
            <br>providing the most comprehensive range of services in the GCC.
            <br>Renowned for our professionalism and reliability, we are dedicated to client satisfaction and event success. 
            <br>Our goal is to craft memorable, branded experiences that elevate every occasion.</p>
           </div>
    </div>
</section>

@if($services->isNotEmpty())
    <section class="section-3 py-5">
        <div class="container">
            <div class="cards">
                <div class="row">
                    {{-- Display the first half of services --}}
                    @foreach ($services as $service)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card border-0 text-center">
                            @if(!empty($service->image))
                            <img src="{{ asset('uploads/services/thumb/small/'.$service->image) }}" class="card-img-top" alt="{{ $service->image_alt_text ?? 'Image' }}">
                            @else
                            <img src="{{ asset('uploads/placeholder.jpg') }}" class="card-img-top" alt="Image">
                            @endif
                            <div class="card-body p-3">
                                <h4 class="card-title mt-2"><a href="{{ route('service.detail', ['slug' => $service->slug]) }}">{{ $service->name }}</a></h4>
                                <div class="content pt-2">
                                    <p class="card-text">{{ $service->short_desc }}</p>
                                </div>
                                <a href="{{ route('service.detail', ['slug' => $service->slug]) }}" class="btn btn-primary mt-4">See In Action <i class="fa-solid fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

@include('common.review')

@include('common.company')

@endsection

