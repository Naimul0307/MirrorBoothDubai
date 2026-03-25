<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageTimes;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminPackageTimesController extends Controller
{
    public function index(Request $request)
        {
            $packageTimes = PackageTimes::orderBy('created_at','ASC');

            if (!empty($request->keyword)) {
                $packageTimes = $packageTimes->where('name','like','%'.$request->keyword.'%');
            }

            $packageTimes = $packageTimes->paginate(20);

            $data['packageTimes'] = $packageTimes;

            return view('admin.packageTimes.list', $data);
        }

        public function create() {
            return view('admin.packageTimes.create');
        }

        // This method will save a addon in DB
        public function save(Request $request) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:package_times,name',
                'timer' => 'required|numeric|min:0',
                'slug' => 'required|unique:package_times,slug'
            ]);

            if($validator->passes()) {
                // Form validated successfully

                $packageTimes = new PackageTimes;
                $packageTimes->name = $request->name;
                $packageTimes->timer = $request->timer;
                $packageTimes->status = $request->status;
                $packageTimes->save();


                $request->session()->flash('success','Package Times Created Successfully');

                return response()->json([
                    'status' => 200,
                    'message' => 'Package Times Created Successfully'
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
            $packageTimes = PackageTimes::where('id',$id)->first();
            if(empty($packageTimes)){
                $request->session()->flash('error','Record not found');
                return redirect()->route('packageTimesList');
            }
            $data['packageTimes'] = $packageTimes;
            return view('admin.packageTimes.edit',$data);
        }

        public function update($id, Request $request) {
            try {
                $packageTimes = PackageTimes::find($id);
                if (!$packageTimes) {
                    $request->session()->flash('error', 'Record not found');
                    return response()->json(['status' => 0]);
                }

                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:package_times,name,' . $packageTimes->id,   
                    'timer' => 'required|numeric|min:0',
                    'slug' => 'required|unique:package_times,slug,' . $packageTimes->id,
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 0,
                        'errors' => $validator->errors()
                    ]);
                }

                $packageTimes->name = $request->name;
                $packageTimes->timer = $request->timer;
                $packageTimes->slug = $request->slug;
                $packageTimes->status = $request->status;
                $packageTimes->save();

                $request->session()->flash('success', 'Package Times updated Successfully');

                return response()->json([
                    'status' => 200,
                    'message' => 'Package Times updated Successfully'
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
            $packageTimes = PackageTimes::find($id);
            if (!$packageTimes) {
                $request->session()->flash('error','Record not found');
                return response(['status' => 0]);
            }

            $packageTimes->delete();

            $request->session()->flash('success','Package Times deleted successfully.');

            return response(['status' => 1]);
        }

        public function getSlug(Request $request){
            $slug = SlugService::createSlug(PackageTimes::class, 'slug', $request->name);
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        }
}
