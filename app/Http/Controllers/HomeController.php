<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Review;
use App\Models\Service;
use App\Models\WorkingCompany;
use Illuminate\Http\Request;


class HomeController extends Controller
{
public function index() {
        $services = Service::where('status', 1)->orderBy('id', 'asc')->get(); // Get all active services

        // Group services by category
        $groupedServices = $services->groupBy('category_id');

        // Fetch all categories from the database
        $categories = Category::all();
        $companies = WorkingCompany::where('status', 1)->get();
        $reviews = Review::where('status', 1)->get();

        $data['groupedServices'] = $groupedServices; // Pass grouped services to the view
        $data['categories'] = $categories; // Pass categories to the view
        $data['companies'] = $companies; // Pass companies to the view
        $data['reviews'] = $reviews; // Pass reviews to the view
        $data['meta_title'] = 'HOME | MIRROR BOOTH EVENT SERVICES L.L.C.-DUBAI';
        $data['meta_description'] = 'Award-Winning Photo Booth & Game Rentals in Dubai.A trusted name in the UAE, we offer over 80+ premium photo booths and interactive games,providing the most comprehensive range of services in the GCC.';
        $data['meta_keywords'] = 'MIRROR BOOTH, PHOTO BOOTH, VIDEOS BOOTH, MAGAZIN BOOTH, EVENT SERVICES, MIRROR BOOTH EVENT SERVICES L.L.C, DUBAI, UAE';

        return view('home', $data); // Adjust this to your view file
    }

   public function searchServices(Request $request)
    {
        $keyword = $request->get('keyword');

        // Get companies and reviews
        $companies = WorkingCompany::where('status', 1)->get();
        $reviews = Review::where('status', 1)->latest()->take(5)->get();

        $services = collect(); // default empty collection

        if ($keyword && strlen($keyword) >= 2) {
            // Only get services where name matches the keyword
            $services = Service::where('status', 1)
                ->where('name', 'like', '%' . $keyword . '%')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('services-search', [
            'services' => $services,
            'companies' => $companies,
            'reviews' => $reviews,
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
