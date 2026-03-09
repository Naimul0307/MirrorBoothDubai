<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class AdminLocationController extends Controller
{
  public function index(Request $request)
    {
        $locations = Location::orderBy('created_at', 'ASC');

        if (!empty($request->keyword)) {
            $locations = $locations->where('name', 'like', '%' . $request->keyword . '%');
        }

        $locations = $locations->paginate(20);
        $data['locations'] = $locations;

        return view('admin.location.list', $data);
    }

    public function create()
    {
        return view('admin.location.create');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:locations',
            'surcharge' => 'required|numeric|min:0',
            'slug' => 'required|unique:locations'
        ]);

        if ($validator->passes()) {
            $location = new Location;
            $location->name = $request->name;
            $location->surcharge = $request->surcharge;
            $location->slug = $request->slug;
            $location->status = $request->status ?? 1;
            $location->save();

            $request->session()->flash('success', 'Location Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Location Created Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $location = Location::find($id);
        if (empty($location)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('locationList');
        }
        $data['location'] = $location;
        return view('admin.location.edit', $data);
    }

    public function update($id, Request $request)
    {
        try {
            $location = Location::find($id);
            if (!$location) {
                $request->session()->flash('error', 'Record not found');
                return response()->json(['status' => 0]);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:locations,name,' . $location->id,
                'surcharge' => 'required|numeric|min:0',
                'slug' => 'required|unique:locations,slug,' . $location->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }

            $location->name = $request->name;
            $location->surcharge = $request->surcharge;
            $location->slug = $request->slug;
            $location->status = $request->status ?? 1;
            $location->save();

            $request->session()->flash('success', 'Location updated Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Location updated Successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Location Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong. Please check logs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id, Request $request)
    {
        $location = Location::find($id);
        if (!$location) {
            $request->session()->flash('error', 'Record not found');
            return response(['status' => 0]);
        }

        $location->delete();

        $request->session()->flash('success', 'Location deleted successfully.');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(Location::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}
