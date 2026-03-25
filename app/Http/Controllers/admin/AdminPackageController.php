<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Package;
use App\Models\Brand;
use App\Models\ExtraHour;
use App\Models\PackageCategory;

class AdminPackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::with(['category', 'brands', 'hours'])
            ->orderBy('created_at', 'ASC');

        if ($request->filled('keyword')) {
            $packages->where('name', 'like', '%' . $request->keyword . '%');
        }

        $packages = $packages->paginate(20);

        return view('admin.package.list', compact('packages'));
    }

    public function create()
    {
        $categories = PackageCategory::where('status', 1)->orderBy('name', 'ASC')->get();
        $brands = Brand::where('status', 1)->get();
        $hours = ExtraHour::where('status', 1)->get();

        return view('admin.package.create', compact('categories', 'brands', 'hours'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:packages,name',
            'price'       => 'required|numeric|min:0',
            'slug'        => 'required|unique:packages,slug',
            'category_id' => 'nullable|exists:package_categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'hour_id'     => 'nullable|exists:extra_hours,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()]);
        }

        try {
            $package = new Package();
            $package->name = $request->name;
            $package->price = $request->price;
            $package->description = $request->description;
            $package->slug = $request->slug;
            $package->category_id = $request->category_id;
            $package->status = $request->status ?? 1;
            $package->save();

            if ($request->brand_id) {
                $package->brands()->sync([$request->brand_id]);
            }

            if ($request->hour_id) {
                $package->hours()->sync([$request->hour_id]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Package Created Successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Package Create Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 0,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $package = Package::with(['category', 'brands', 'hours'])->find($id);

        if (!$package) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('packageList');
        }

        $categories = PackageCategory::where('status', 1)->orderBy('name', 'ASC')->get();
        $brands = Brand::where('status', 1)->get();
        $hours = ExtraHour::where('status', 1)->get();

        return view('admin.package.edit', compact('package', 'categories', 'brands', 'hours'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => 0,
                'message' => 'Record not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:packages,name,' . $package->id,
            'price'       => 'required|numeric|min:0',
            'slug'        => 'required|unique:packages,slug,' . $package->id,
            'category_id' => 'nullable|exists:package_categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'hour_id'     => 'nullable|exists:extra_hours,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $package->name = $request->name;
            $package->price = $request->price;
            $package->description = $request->description;
            $package->slug = $request->slug;
            $package->category_id = $request->category_id;
            $package->status = $request->status ?? 1;
            $package->save();

            if ($request->brand_id) {
                $package->brands()->sync([$request->brand_id]);
            } else {
                $package->brands()->sync([]);
            }

            if ($request->hour_id) {
                $package->hours()->sync([$request->hour_id]);
            } else {
                $package->hours()->sync([]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Package Updated Successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Package Update Error: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => 0,
                'message' => 'Record not found'
            ]);
        }

        $package->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Package deleted successfully'
        ]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(Package::class, 'slug', $request->name);

        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}