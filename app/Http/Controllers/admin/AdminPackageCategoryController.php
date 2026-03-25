<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageCategory;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;

class AdminPackageCategoryController extends Controller
{
    public function index(Request $request)
    {
        $packageCategories = PackageCategory::orderBy('created_at', 'ASC');

        if (!empty($request->keyword)) {
            $packageCategories = $packageCategories->where('name', 'like', '%' . $request->keyword . '%');
        }

        $packageCategories = $packageCategories->paginate(20);

        $data['packageCategories'] = $packageCategories;

        return view('admin.package-category.list', $data);
    }

    public function create()
    {
        return view('admin.package-category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:package_categories,name',
            'slug' => 'required|unique:package_categories,slug',
        ]);

        if ($validator->passes()) {
            $packageCategories = new PackageCategory();
            $packageCategories->name = $request->name;
            $packageCategories->slug = $request->slug;
            $packageCategories->status = $request->status;
            $packageCategories->save();

            $request->session()->flash('success', 'Package Category Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Package Category Created Successfully'
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
        $packageCategories = PackageCategory::where('id', $id)->first();

        if (empty($packageCategories)) {
            $request->session()->flash('error', 'Record not found in DB');
            return redirect()->route('packageCategoryList');
        }

        $data['packageCategories'] = $packageCategories;

        return view('admin.package-category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $packageCategories = PackageCategory::find($id);

        if (empty($packageCategories)) {
            $request->session()->flash('error', 'Record not found in DB');
            return response()->json([
                'status' => 0,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:package_categories,name,' . $packageCategories->id,
            'slug' => 'required|unique:package_categories,slug,' . $packageCategories->id,
        ]);

        if ($validator->passes()) {
            $packageCategories->name = $request->name;
            $packageCategories->slug = $request->slug;
            $packageCategories->status = $request->status;
            $packageCategories->save();

            $request->session()->flash('success', 'Package Category Updated Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Package Category Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($id, Request $request)
    {
        $packageCategories = PackageCategory::where('id', $id)->first();

        if (empty($packageCategories)) {
            $request->session()->flash('error', 'Record not found');

            return response([
                'status' => 0
            ]);
        }

        $packageCategories->delete();

        $request->session()->flash('success', 'Package Category deleted successfully.');

        return response([
            'status' => 1
        ]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(PackageCategory::class, 'slug', $request->name);

        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}