<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Addon;

class AdminAddonController extends Controller
{
 public function index(Request $request)
    {
        $addons = Addon::orderBy('created_at','ASC');

        if (!empty($request->keyword)) {
            $addons = $addons->where('name','like','%'.$request->keyword.'%');
        }

        $addons = $addons->paginate(20);

        $data['addons'] = $addons;

        return view('admin.addon.list',$data);
    }

    public function create() {
        return view('admin.addon.create');
    }

    // This method will save a addon in DB
    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:addons',
            'price' => 'required|numeric|min:0',
            'slug' => 'required|unique:addons'
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $addon = new Addon;
            $addon->name = $request->name;
            $addon->price = $request->price;
            $addon->status = $request->status;
            $addon->save();


            $request->session()->flash('success','Addon Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Addon Created Successfully'
            ]);

        } else {
            // return errors
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function edit(Request $request,$id)
    {
        $addon = Addon::where('id',$id)->first();
        if(empty($addon)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('addonList');
        }
        $data['addon'] = $addon;
        return view('admin.addon.edit',$data);
    }

    public function update($id, Request $request) {
        try {
            $addon = Addon::find($id);
            if (!$addon) {
                $request->session()->flash('error', 'Record not found');
                return response()->json(['status' => 0]);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:addons,name,' . $addon->id,
                'price' => 'required|numeric|min:0',
                'slug' => 'required|unique:addons,slug,' . $addon->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }

            $addon->name = $request->name;
            $addon->price = $request->price;
            $addon->slug = $request->slug;
            $addon->status = $request->status;
            $addon->save();

            $request->session()->flash('success', 'Addon updated Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Addon updated Successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Addon Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong. Please check logs.',
                'error' => $e->getMessage() // Only return this in local/dev
            ], 500);
        }
    }

    public function delete($id, Request $request) {
        $addon = Addon::find($id);
        if (!$addon) {
            $request->session()->flash('error','Record not found');
            return response(['status' => 0]);
        }

        $addon->delete();

        $request->session()->flash('success','Addon deleted successfully.');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request){
        $slug = SlugService::createSlug(Addon::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }

}
