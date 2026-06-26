<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GoogleReviewsService
{
    protected string $apiKey;
    protected string $placeId;

    public function __construct()
    {
        $this->apiKey  = config('services.google.places_api_key');
        $this->placeId = config('services.google.place_id');
    }

    public function getReviews(): array
    {
        $response = Http::withHeaders([
            'X-Goog-Api-Key'   => $this->apiKey,
            'X-Goog-FieldMask' => 'reviews,rating,userRatingCount,displayName',
        ])->get("https://places.googleapis.com/v1/places/{$this->placeId}");

        if ($response->failed()) {
            return [
                'reviews'      => [],
                'rating'       => 5.0,
                'totalReviews' => 0,
                'businessName' => 'Mirror Booth Dubai',
            ];
        }

        $data = $response->json();

        return [
            'reviews'      => $data['reviews']              ?? [],
            'rating'       => $data['rating']               ?? 5.0,
            'totalReviews' => $data['userRatingCount']       ?? 0,
            'businessName' => $data['displayName']['text']  ?? 'Mirror Booth Dubai',
        ];
    }
}
