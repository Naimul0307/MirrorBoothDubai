<section class="section-6 py-5">
    <div class="container">
        <h3 class="title-color mb-4">Testimonials & Reviews</h3>
        <div class="divider-container">
            <div class="divider mb-3"></div>
        </div>
        <div class="text-center mb-3">
            <h4 class="display-5">5.0</h4>
            <p class="mb-0">Mirror Booth Dubai</p>
            <p><span class="text-warning">★ ★ ★ ★ ★</span> 33 Reviews</p>
        </div>
        <div class="cards">
            <div class="services-slider">
                @foreach($reviews as $review)
                    <div class="supporter-logo">
                        <img src="{{ asset('uploads/reviews/thumb/large/' . $review->image) }}" class="img-fluid" alt="{{ $review->name }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
