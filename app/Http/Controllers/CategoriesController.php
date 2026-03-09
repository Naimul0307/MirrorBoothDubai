<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Review;
use App\Models\Service;
use App\Models\WorkingCompany;
use Illuminate\Http\Request;


class CategoriesController extends Controller
{
 public function index($slug)  {
        $categorySelected = '';

        // Order categories by ID and retrieve active ones with their services
        $categories = Category::where('status', 1)
                               ->orderBy('id', 'asc')
                               ->with('services')
                               ->get();

        // Find the category by slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Get services in the selected category and paginate results
        $services = Service::where('status', 1)
                            ->where('category_id', $category->id)
                            ->paginate(100);

        $companies = WorkingCompany::where('status', 1)->get();
        $reviews = Review::where('status', 1)->get();

        $data['category'] = $category;
        $data['services'] = $services;
        $data['categorySelected'] = $category->id;
        $data['companies'] = $companies;
        $data['reviews'] = $reviews;

        $data['meta_title'] = $category->meta_title ?? $category->title;
        $data['meta_description'] = $category->meta_description ?? Str::limit(strip_tags($category->description), 150);
        $data['meta_keywords'] = $category->meta_keywords ?? 'MIRROR BOOTH, PHOTO BOOTH, VIDEOS BOOTH, MAGAZIN BOOTH, EVENT SERVICES, DUBAI, UAE';


        return view('category', $data);
    }
}
