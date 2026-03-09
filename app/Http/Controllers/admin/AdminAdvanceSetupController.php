<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\AdvanceSetup;

class AdminAdvanceSetupController extends Controller
{
   public function index(Request $request)
    {
        $advanceSetup = AdvanceSetup::orderBy('created_at','ASC');

        if (!empty($request->keyword)) {
            $advanceSetup = $advanceSetup->where('name','like','%'.$request->keyword.'%');
        }

        $advanceSetup = $advanceSetup->paginate(20);

        $data['advanceSetup'] = $advanceSetup;

        return view('admin.advance-setup.list',$data);
    }

    public function create() {
        return view('admin.advance-setup.create');
    }

    // This method will save a advanceSetup in DB
    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:advance_setup,name',
            'price' => 'required|numeric|min:0',
            'slug'  => 'required|unique:advance_setup,slug',
        ]);
        if($validator->passes()) {
            // Form validated successfully

            $advanceSetup = new AdvanceSetup;
            $advanceSetup->name = $request->name;
            $advanceSetup->price = $request->price;
            $advanceSetup->status = $request->status;
            $advanceSetup->save();


            $request->session()->flash('success','Advance Setup Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Advance Setup Created Successfully'
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
        $advanceSetup = AdvanceSetup::where('id',$id)->first();
        if(empty($advanceSetup)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('advanceSetupList');
        }
        $data['advanceSetup'] = $advanceSetup;
        return view('admin.advance-setup.edit',$data);
    }

    public function update($id, Request $request) {
        try {
            $advanceSetup = AdvanceSetup::find($id);
            if (!$advanceSetup) {
                $request->session()->flash('error', 'Record not found');
                return response()->json(['status' => 0]);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:advance_setup,name,' . $advanceSetup->id,
                'price' => 'required|numeric|min:0',
                'slug' => 'required|unique:advance_setup,slug,' . $advanceSetup->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }

            $advanceSetup->name = $request->name;
            $advanceSetup->price = $request->price;
            $advanceSetup->slug = $request->slug;
            $advanceSetup->status = $request->status;
            $advanceSetup->save();

            $request->session()->flash('success', 'Advance Setup updated Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Advance Setup updated Successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Advance Setup Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong. Please check logs.',
                'error' => $e->getMessage() // Only return this in local/dev
            ], 500);
        }
    }

    public function delete($id, Request $request) {
        $advanceSetup = AdvanceSetup::find($id);
        if (!$advanceSetup) {
            $request->session()->flash('error','Record not found');
            return response(['status' => 0]);
        }

        $advanceSetup->delete();

        $request->session()->flash('success','Advance Setup deleted successfully.');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request){
        $slug = SlugService::createSlug(AdvanceSetup::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}
