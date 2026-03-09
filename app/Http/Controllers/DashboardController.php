<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard', [
            'meta_title' => 'DASHBOARD | MIRROR BOOTH EVENT SERVICES L.L.C',
            'meta_description' => 'MANAGE YOUR ACCOUNT AND BOOKINGS AT MIRROR BOOTH EVENT SERVICES L.L.C',
            'meta_keywords' => 'DASHBOARD, MIRROR BOOTH, BOOKING, EVENTS, MIRROR BOOTH EVENT SERVICES L.L.C'
        ]);
    }
}
