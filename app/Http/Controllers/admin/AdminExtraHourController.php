<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\ExtraHour;

class AdminExtraHourController extends Controller
{
    // List Extra Hours
    public function index(Request $request)
    {
        $query = ExtraHour::orderBy('created_at', 'ASC');

        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $extraHours = $query->paginate(20);
        return view('admin.extra_hours.list', compact('extraHours'));
    }

    // Show create form
    public function create()
    {
        return view('admin.extra_hours.create');
    }

    // Save new Extra Hour
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:extra_hours',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()]);
        }

        try {
            $hour = new ExtraHour();
            $hour->name = $request->name;
            $hour->price = $request->price;
            $hour->status = $request->status ?? 1;
            $hour->save();

            return response()->json(['status' => 200, 'message' => 'Extra Hour Created Successfully']);
        } catch (\Throwable $e) {
            Log::error('Extra Hour Create Error: ' . $e->getMessage());
            return response()->json(['status' => 0, 'message' => 'Something went wrong.', 'error' => $e->getMessage()], 500);
        }
    }

    // Show edit form
    public function edit(Request $request, $id)
    {
        $hour = ExtraHour::find($id);
        if (!$hour) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('hoursList');
        }
        return view('admin.extra_hours.edit', compact('hour'));
    }

    // Update Extra Hour
    public function update(Request $request, $id)
    {
        $hour = ExtraHour::find($id);
        if (!$hour) return response()->json(['status' => 0, 'message' => 'Record not found']);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:extra_hours,name,' . $hour->id,
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) return response()->json(['status' => 0, 'errors' => $validator->errors()]);

        try {
            $hour->name = $request->name;
            $hour->price = $request->price;
            $hour->status = $request->status ?? 1;
            $hour->save();

            return response()->json(['status' => 200, 'message' => 'Extra Hour Updated Successfully']);
        } catch (\Throwable $e) {
            Log::error('Extra Hour Update Error: ' . $e->getMessage());
            return response()->json(['status' => 0, 'message' => 'Something went wrong.', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete Extra Hour
    public function delete($id)
    {
        $hour = ExtraHour::find($id);
        if (!$hour) return response()->json(['status' => 0, 'message' => 'Record not found']);

        $hour->delete();
        return response()->json(['status' => 1, 'message' => 'Extra Hour deleted successfully']);
    }

    // Generate slug
    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(ExtraHour::class, 'slug', $request->name);
        return response()->json(['status' => true, 'slug' => $slug]);
    }
}
