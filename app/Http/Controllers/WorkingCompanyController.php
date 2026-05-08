<?php

namespace App\Http\Controllers;


use App\Models\WorkingCompany;
use Illuminate\Http\Request;

class WorkingCompanyController extends Controller
{
    public function index()
    {
        $companies = WorkingCompany::where('status', 1)->get();
        return view('common.company', compact('companies'));
    }
}
