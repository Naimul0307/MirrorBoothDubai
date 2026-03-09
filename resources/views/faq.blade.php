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
            <div class="mt-2 mb-3 text-muted">FAQ</div>
            <div class="text-muted">Award-Winning Photo Booth & Game Rentals in Dubai</div>
            <p> A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games, 
            <br>providing the most comprehensive range of services in the GCC.
            <br>Renowned for our professionalism and reliability, we are dedicated to client satisfaction and event success. 
            <br>Our goal is to craft memorable, branded experiences that elevate every occasion.</p>
           </div>
    </div>
</section>

@if (!empty($faq))
    <section class="section-4 py-5">
        <div class="container py-2">
                <div class="row">
                     <div class="col-md-12 py-4">
                        <div class="accordion" id="accordionFlushExample">
            
                    @foreach ($faq as $key => $faqRow)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-{{ $key }}" aria-expanded="false" aria-controls="flush-{{ $key }}">
                                {{ $faqRow->question }}
                            </button>
                        </h2>
                        <div id="flush-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">

                                {!! $faqRow->answer !!}

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
                </div>
        </div>
    </section>
@endif

@include('common.review')

@include('common.company')

@endsection


