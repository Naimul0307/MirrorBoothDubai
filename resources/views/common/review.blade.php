<section class="section-6 py-5">
    <div class="container">

        <h3 class="title-color mb-4">Testimonials & Reviews</h3>

        <div class="divider-container">
            <div class="divider mb-3"></div>
        </div>

        <div class="text-center mb-3">
            <h4 class="display-5">{{ number_format($rating, 1) }}</h4>
            <p class="mb-0">{{ $businessName }}</p>
            <p>
                <span class="text-warning">★ ★ ★ ★ ★</span>
                {{ $totalReviews }} Reviews
            </p>
        </div>

        <div class="reviews-slider">
            @forelse($reviews as $review)
                @php
                    $name       = $review['authorAttribution']['displayName'] ?? '';
                    $photo      = $review['authorAttribution']['photoUri']    ?? '';
                    $initial    = strtoupper(substr($name ?: 'A', 0, 1));
                    $reviewText = $review['text']['text'] ?? $review['originalText']['text'] ?? '';
                @endphp

                <div class="px-2">
                    <div style="
                        background: #fff;
                        border-radius: 12px;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                        padding: 20px;
                        min-height: 200px;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                    ">

                        {{-- Header --}}
                        <div style="display:flex; align-items:center; margin-bottom:12px;">

                            {{-- Avatar --}}
                            @if($photo)
                                <img src="{{ $photo }}"
                                     alt="{{ $name }}"
                                     style="width:46px;height:46px;border-radius:50%;object-fit:cover;flex-shrink:0;margin-right:12px;"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="width:46px;height:46px;border-radius:50%;background:#6c757d;color:#fff;font-size:18px;font-weight:bold;display:none;align-items:center;justify-content:center;flex-shrink:0;margin-right:12px;">
                                    {{ $initial }}
                                </div>
                            @else
                                <div style="width:46px;height:46px;border-radius:50%;background:#6c757d;color:#fff;font-size:18px;font-weight:bold;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:12px;">
                                    {{ $initial }}
                                </div>
                            @endif

                            {{-- Name + stars + date --}}
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $name ?: 'Anonymous' }}
                                </div>
                                <div style="color:#f5a623;font-size:13px;line-height:1;">★★★★★</div>
                                @if(!empty($review['relativePublishTimeDescription']))
                                    <div style="font-size:11px;color:#999;">
                                        {{ $review['relativePublishTimeDescription'] }}
                                    </div>
                                @endif
                            </div>

                            {{-- Google icon --}}
                            <img src="https://www.gstatic.com/images/branding/product/1x/googleg_16dp.png"
                                 alt="Google" style="width:20px;height:20px;flex-shrink:0;margin-left:8px;">
                        </div>

                        {{-- Review text --}}
                        <p style="font-size:13px;color:#555;line-height:1.6;margin:0;flex:1;">
                            @if($reviewText)
                                {{ Str::limit($reviewText, 180) }}
                            @else
                                <em>Rated 5 stars on Google</em>
                            @endif
                        </p>

                    </div>
                </div>

            @empty
                <p class="text-center text-muted">No reviews found.</p>
            @endforelse
        </div>

    </div>
</section>
