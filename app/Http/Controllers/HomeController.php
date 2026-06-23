<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\HeroSlide;
use App\Models\Review;
use App\Models\WorkingCompany;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 1)
            ->select('id', 'category_id', 'name', 'slug', 'image', 'image_alt_text', 'short_desc')
            ->orderBy('id', 'asc')
            ->get();

        $groupedServices = $services->groupBy('category_id');

        $categories = Category::select('id', 'name', 'slug')->get();

        $heroSlides = HeroSlide::where('status', 1)
            ->whereNotNull('image')
            ->select('id', 'name', 'image')
            ->orderBy('id', 'desc')
            ->get();

        $reviews = Review::where('status', 1)->get();
        $companies = WorkingCompany::where('status', 1)->get();

        return view('home', [
            'groupedServices' => $groupedServices,
            'categories' => $categories,
            'heroSlides' => $heroSlides,
            'reviews' => $reviews,
            'companies' => $companies,

            'meta_title' => 'HOME | MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI',
            'meta_description' => 'Award-Winning Photo Booth & Game Rentals in Dubai.A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games,providing the most comprehensive range of services in the GCC.',
            'meta_keywords' => 'MIRROR BOOTH, PHOTO BOOTH, VIDEOS BOOTH, MAGAZIN BOOTH, EVENT SERVICES, MIRROR BOOTH EVENT SERVICES L.L.C, DUBAI, UAE',
        ]);
    }

    public function searchServices(Request $request)
    {
        $keyword = $request->get('keyword');

        $services = collect();

        if ($keyword && strlen($keyword) >= 2) {
            $services = Service::where('status', 1)
                ->where('name', 'like', '%' . $keyword . '%')
                ->select('id', 'name', 'slug', 'image', 'image_alt_text', 'short_desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        $reviews = Review::where('status', 1)->get();
        $companies = WorkingCompany::where('status', 1)->get();

        return view('services-search', [
            'services' => $services,
            'keyword' => $keyword,
            'reviews' => $reviews,
            'companies' => $companies,

            'meta_title' => $keyword
                ? 'Search Results for "' . $keyword . '" | MIRROR BOOTH DUBAI'
                : 'All Services | MIRROR BOOTH DUBAI',

            'meta_description' => $keyword
                ? 'Browse photo booth and event services matching: ' . $keyword
                : 'Browse all photo booth and event services in Dubai.',

            'meta_keywords' => $keyword ?? 'photo booth, event services, Dubai',
        ]);
    }

    public function project()
    {
        return view('project', [
            'meta_title' => 'PROJECTS | MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI',
            'meta_description' => 'Discover our portfolio of award-winning photo booth and interactive game rentals in Dubai. View real client projects, creative event setups, and unforgettable branded experiences delivered across the UAE and GCC.',
            'meta_keywords' => 'photo booth projects, event rentals Dubai, corporate events UAE, interactive games Dubai, branded photo booths, wedding photo booth Dubai, Mirror Booth projects, event entertainment GCC, UAE event portfolio',
        ]);
    }
}
