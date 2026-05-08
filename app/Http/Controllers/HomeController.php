<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\HeroSlide;
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

        $categories = Category::select('id', 'name', 'slug')
            ->get();

        $heroSlides = HeroSlide::where('status', 1)
            ->whereNotNull('image')
            ->select('id', 'name', 'image')
            ->orderBy('id', 'desc')
            ->get();

        $data['groupedServices'] = $groupedServices;
        $data['categories'] = $categories;
        $data['heroSlides'] = $heroSlides;

        $data['meta_title'] = 'HOME | MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI';
        $data['meta_description'] = 'Award-Winning Photo Booth & Game Rentals in Dubai.A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games,providing the most comprehensive range of services in the GCC.';
        $data['meta_keywords'] = 'MIRROR BOOTH, PHOTO BOOTH, VIDEOS BOOTH, MAGAZIN BOOTH, EVENT SERVICES, MIRROR BOOTH EVENT SERVICES L.L.C, DUBAI, UAE';

        return view('home', $data);
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

        return view('services-search', [
            'services' => $services,
            'keyword' => $keyword,
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
        $data['meta_title'] = 'PROJECTS | MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI';
        $data['meta_description'] = 'Discover our portfolio of award-winning photo booth and interactive game rentals in Dubai. View real client projects, creative event setups, and unforgettable branded experiences delivered across the UAE and GCC.';
        $data['meta_keywords'] = 'photo booth projects, event rentals Dubai, corporate events UAE, interactive games Dubai, branded photo booths, wedding photo booth Dubai, Mirror Booth projects, event entertainment GCC, UAE event portfolio';

        return view('project', $data);
    }
}
