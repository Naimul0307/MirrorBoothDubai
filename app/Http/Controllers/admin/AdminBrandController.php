<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;

class AdminBrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::orderBy('created_at','ASC');

        if (!empty($request->keyword)) {
            $query->where('name','like','%'.$request->keyword.'%');
        }

        $brands = $query->paginate(20);
        return view('admin.branding.list', compact('brands'));
    }

    public function create() {
        return view('admin.branding.create');
    }

    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands',
            'price' => 'required|numeric|min:0',
            // no need to validate slug, Sluggable will generate it
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()]);
        }

        try {
            $brand = new Brand;
            $brand->name = $request->name;
            $brand->price = $request->price;
            $brand->status = $request->status ?? 1;
            $brand->save();

            return response()->json(['status' => 200, 'message' => 'Brand Created Successfully']);
        } catch (\Throwable $e) {
            Log::error('Brand Create Error: ' . $e->getMessage());
            return response()->json(['status' => 0, 'message' => 'Something went wrong.', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            $request->session()->flash('error','Record not found');
            return redirect()->route('BrandingList');
        }
        return view('admin.branding.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['status' => 0, 'message' => 'Record not found']);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands,name,' . $brand->id,
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) return response()->json(['status'=>0, 'errors'=>$validator->errors()]);

        try {
            $brand->name = $request->name;
            $brand->price = $request->price;
            $brand->status = $request->status ?? 1;
            $brand->save();

            return response()->json(['status'=>200, 'message'=>'Brand Updated Successfully']);
        } catch (\Throwable $e) {
            Log::error('Brand Update Error: '.$e->getMessage());
            return response()->json(['status'=>0,'message'=>'Something went wrong.','error'=>$e->getMessage()],500);
        }
    }

    public function delete($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['status'=>0,'message'=>'Record not found']);
        
        $brand->delete();
        return response()->json(['status'=>1,'message'=>'Brand deleted successfully']);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(Brand::class, 'slug', $request->name);
        return response()->json(['status'=>true,'slug'=>$slug]);
    }
}
