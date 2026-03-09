<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Review;
use App\Models\WorkingCompany;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index() {
        
        $faq = Faq::orderBy('created_at','DESC')->where('status',1)->get();  
        $companies = WorkingCompany::where('status', 1)->get();   
        $reviews = Review::where('status', 1)->get();

        return view('faq',[
            'faq' => $faq,
            'companies' => $companies,
            'reviews' => $reviews,
            'meta_title' => 'CONTACT US | MIRROR BOOTH EVENT SERVICES L.L.C. - DUBAI',
            'meta_description' => 'Get in touch with Mirror Booth Event Services L.L.C. for bookings or inquiries. Contact us today to make your event unforgettable with our photo booth services.',
            'meta_keywords' => 'FAQ, MIRROR BOOTH, DUBAI, BOOKING,UAE, MIRROR BOOTH EVENt SERVICES L.L.C'
        ]);
    }
}
