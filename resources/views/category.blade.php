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

            @if(!empty($category))
                @php
                    $name = $category->name;
                    if (str_contains($name, 'Dubai')) {
                        $name = str_replace('Dubai', 'Rental Dubai', $name);
                    } else {
                        $name .= ' Rental';
                    }
                @endphp
            @endif

            <div class="mt-2 mb-3 text-muted">{{ $name ?? '' }}</div>
            <div class="mt-2 mb-3 text-muted">Award-Winning Corporate Photo Booth & Game Rentals in Dubai</div>
            <p>
                A trusted and professional provider in the UAE, delivering engaging photo booths and interactive games for events.
                <br>We focus on client satisfaction and event success.
                <br>Our goal is to create memorable, branded experiences for every occasion.
            </p>
        </div>
    </div>
</section>

@if($services->isNotEmpty())
    <section class="section-3 py-5">
        <div class="container">
            <div class="cards">
                <div class="row">
                    {{-- Display the services --}}
                    @foreach ($services as $service)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card border-0 text-center">
                            @if(!empty($service->image))
                                <img src="{{ asset('uploads/services/thumb/small/'.$service->image) }}" class="card-img-top" alt="image">
                            @else
                                <img src="{{ asset('uploads/placeholder.jpg') }}" class="card-img-top" alt="image">
                            @endif
                            <div class="card-body p-3">
                                <h3 class="card-title mt-2">
                                    <a href="{{ route('service.detail', ['slug' => $service->slug]) }}">
                                        {{ $service->name }}
                                    </a>
                                </h3>
                                <div class="content pt-2">
                                    <p class="card-text">{{ $service->short_desc }}</p>
                                </div>
                                <a href="{{ route('service.detail', ['slug' => $service->slug]) }}" class="btn btn-primary mt-4">
                                    See In Action <i class="fa-solid fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

@include('common.company')

@include('common.review')

@endsection
