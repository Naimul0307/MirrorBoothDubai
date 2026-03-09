@foreach ($categories as $category)
    <section class="section-3 py-5">
        <div class="container">
            <h2 class="title-color mb-4 h1 text-center">{{ $category->name }}</h2>
            <div class="divider-container text-center">
                <div class="divider mb-3"></div>
            </div>
            <div class="cards">
                <div class="row">
                    <div class="services-slider">
                        @if(!empty($groupedServices[$category->id]))
                            @foreach ($groupedServices[$category->id] as $service)
                                <div class="col-md-3 col-sm-4 mb-4">
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
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
             <a href="{{ route('categories.index', ['slug' => $category->slug]) }}" class="btn btn-primary">SEE MORE {{ strtoupper($category->name) }}</a>
            </div>
        </div>
    </section>
@endforeach
