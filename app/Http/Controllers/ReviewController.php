<?php

namespace App\Http\Controllers;

// use App\Models\Review;
// use Illuminate\Http\Request;

// class ReviewController extends Controller
// {
//     public function index()
//     {
//         $reviews = Review::where('status', 1)->get();
//         return view('common.review', compact('reviews'));
//     }
// }

use App\Services\GoogleReviewsService;

class ReviewController extends Controller
{
    public function __construct(protected GoogleReviewsService $googleReviews) {}

    public function index()
    {
        $data = $this->googleReviews->getReviews();

        return view('common.review', [
            'reviews'      => $data['reviews']      ?? [],
            'rating'       => $data['rating']       ?? 5.0,
            'totalReviews' => $data['totalReviews'] ?? 0,
            'businessName' => $data['businessName'] ?? 'Mirror Booth Dubai',
        ]);
    }
}
