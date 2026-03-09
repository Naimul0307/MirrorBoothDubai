@extends('layouts.app')
@section('head')
    @php
        // Structured data for the video (only if a video exists)
        if (!empty($service->videos_link)) {
            $url = $service->videos_link;
            $videoId = null;

            // Extract the YouTube video ID
            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                $videoId = $matches[1];
            }

            if ($videoId) {
                $structuredData = [
                    "@context" => "http://schema.org",
                    "@type" => "VideoObject",
                    "name" => $service->name,
                    "description" => strip_tags($service->description),
                    "thumbnailUrl" => asset('uploads/services/thumb/large/'.$service->image),
                    "contentUrl" => "https://www.youtube.com/watch?v=".$videoId,
                    "embedUrl" => "https://www.youtube.com/embed/".$videoId,
                    "uploadDate" => now()->toIso8601String(),
                ];
            }
        }

        // Structured data for the primary image
        $imageStructuredData = [
            "@context" => "http://schema.org",
            "@type" => "ImageObject",
            "url" => asset('uploads/services/thumb/large/'.$service->image),
            "height" => 600, // You can adjust the height
            "width" => 800,  // You can adjust the width
            "caption" => $service->image_alt_text ?? 'Service Image', // Alt text or caption
        ];

        // Additional images structured data (for gallery images)
        $galleryStructuredData = [];
        if (!empty($gallery_images) && count($gallery_images) > 0) {
            foreach ($gallery_images as $image) {
                $galleryStructuredData[] = [
                    "@context" => "http://schema.org",
                    "@type" => "ImageObject",
                    "url" => asset('uploads/services/gallery/'.$image),
                    "height" => 600, // Adjust the height
                    "width" => 800,  // Adjust the width
                    "caption" => $service->image_alt_text ?? 'Gallery Image',
                ];
            }
        }
    @endphp

    @isset($structuredData)
        <script type="application/ld+json">
            {{ json_encode($structuredData) }}
        </script>
    @endisset

    @isset($imageStructuredData)
        <script type="application/ld+json">
            {{ json_encode($imageStructuredData) }}
        </script>
    @endisset

    @isset($galleryStructuredData)
        <script type="application/ld+json">
            {{ json_encode($galleryStructuredData) }}
        </script>
    @endisset
@endsection

@section('content')
<section class="section-3 py-5">
</section>
<section class="section-8 py-5">
    <div class="container py-2">
        <div class="row">
            <div class="container py-2">
                <div class="about-block text-center text-md-left">
                    <h1 class="title-color">{{ $service->name }}</h1>
                    <div class="divider-container">
                        <div class="divider mb-3"></div>
                    </div>
                </div>
            </div>

           <div class="col-md-6 col-12">
            @php
                $videoId = null;
        
                if (!empty($service->videos_link)) {
                    $url = $service->videos_link;
        
                    if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                        $videoId = $matches[1];
                    }
                }
            @endphp
        
            @if(!empty($videoId))
                <div class="image-red-background video-wrapper">
                    <iframe class="responsive-iframe" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                </div>
            @else
                <div class="image-red-background image-wrapper">
                    <img src="{{ asset('uploads/services/thumb/small/'.$service->image) }}" class="w-100" alt="{{ $service->image_alt_text ?? 'Service Image' }}">
                </div>
            @endif
        </div>

                    
            <div class="col-md-6 col-12 d-flex align-items-center">
                <div>
                    {!! $service->description !!}
                </div>
            </div>
{{--             
            <div class="col-md-6 col-12 d-flex align-items-center">
                <div class="about-block text-center text-md-left">
                    {!! $service->description !!}
                </div>
            </div> --}}
        </div>
    </div>
</section>

@if(!empty($additional_videos_links) && is_array($additional_videos_links) && count($additional_videos_links) > 0)
    <section class="section-6 py-5">
        <div class="container">
            <div class="cards">
                <div class="row justify-content-center">
                    @foreach ($additional_videos_links as $link)
                        @php
                            $videoUrl = $link;
                            $additionalVideoId = null;

                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches)) {
                                $additionalVideoId = $matches[1];
                            }
                        @endphp

                        @if(!empty($additionalVideoId))
                            <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
                                <div class="card border-0 p-0 w-100">
                                    <div class="video-wrapper">
                                        <iframe class="responsive-iframe" src="https://www.youtube.com/embed/{{ $additionalVideoId }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

@if(!empty($gallery_images) && count($gallery_images) > 0)
<section class="section-6 py-5">
    <div class="container">
        <div class="cards">
            @php $imageCount = count($gallery_images); @endphp

            <div class="services-slider {{ $imageCount < 4 ? 'few-items' : '' }}">
                @foreach ($gallery_images as $image)
                    <div class="slider-item">
                        @if(!empty($image))
                            <img src="{{ asset('uploads/services/gallery/'.$image) }}" class="img-fluid" alt="{{ $service->image_alt_text ?? 'Image' }}">
                        @else
                            <img src="{{ asset('uploads/placeholder.jpg') }}" class="card-img-top" alt="Image">
                        @endif
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
