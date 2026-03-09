<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Location;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\AdvanceSetup;

class QuoteController extends Controller
{
    public function index()
    {
        $packages = Package::with(['brands', 'hours'])
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        $locations = Location::where('status', 1)->orderBy('id', 'asc')->get();
        $addons = Addon::where('status', 1)->orderBy('id', 'asc')->get();
        $advanceSetups = AdvanceSetup::where('status', 1)->orderBy('id', 'asc')->get();
        $brands = Brand::where('status',1)->orderBy('id','asc')->get();


        $packagesData = $packages->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'included_hours' => (float) ($p->included_hours ?? 4),
                'desc' => $p->description ?? '',
                'brands' => $p->brands->pluck('id')->toArray(),
                'hours' => $p->hours->map(function($h) {
                    return [
                        'id' => $h->id,
                        'name' => $h->name,
                        'price' => (float) $h->price,
                    ];
                })->values()->toArray(),
            ];
        })->toArray();

        $locationsData = $locations->map(function($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
                'surcharge' => $l->surcharge,
            ];
        })->toArray();

        $addonsData = $addons->map(function($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
                'price' => $a->price,
            ];
        })->toArray();

        $advanceSetupsData = $advanceSetups->map(function($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
                'price' => $a->price,
            ];
        })->toArray();

        $brandingData = $brands->map(function($b){
            return [
                'id' => $b->id,
                'name' => $b->name,
                'price' => $b->price,
            ];
        })->toArray();

        return view('quote-calculator', compact(
            'packagesData',
            'locationsData',
            'addonsData',
            'advanceSetupsData',
            'brandingData'
        ));
    }
}