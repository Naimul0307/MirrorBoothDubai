<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  public function index(Request $request)
    {
        $categories = Category::orderBy('created_at','ASC');

        if (!empty($request->keyword)) {
            $categories = $categories->where('name','like','%'.$request->keyword.'%');
        }

        $categories = $categories->paginate(20);

        $data['categories'] = $categories;
        return view('admin.category.list',$data);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:categories',
            'slug' => 'required|unique:categories',
        ]);

        if($validator->passes()) {
            $category = new Category;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->meta_title = $request->meta_title ?: $request->name . '| MIRROR BOOTH EVENT SERVICES L.L.C';
            $category->meta_description = $request->meta_description ?: 'EXPLORE OUR ' . $request->name . ' SERVICES OFFERED BY MIRROR BOOTH EVENT SERVICES L.L.C IN DUBAI.';
            $category->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI,UAE';
            $category->status = $request->status;

            $category->save();
            $request->session()->flash('success','Category Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Category Created Successfully'
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
        $category = Category::where('id',$id)->first();

        if(empty($category)){
            $request->session()->flash('error','Record not found in DB');
            return redirect()->route('categoryList');
        }

        $data['category'] = $category;

        return view('admin.category.edit',$data);
    }

    public function update(Request $request,$id)
    {
        $category = Category::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $category->id . ',id',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if($validator->passes()) {
            // Form validated successfully
            if(empty($category)){
                $request->session()->flash('error','Record not found in DB');
                return response()->json([
                    'status' => 0,
                ]);
            }

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->meta_title = $request->meta_title ?: $request->name . '| MIRROR BOOTH EVENT SERVICES L.L.C';
            $category->meta_description = $request->meta_description ?: 'EXPLORE OUR ' . $request->name . ' SERVICES OFFERED BY MIRROR BOOTH EVENT SERVICES L.L.C IN DUBAI.';
            $category->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI,UAE';
            $category->status = $request->status;
            $category->save();

            $request->session()->flash('success','Category Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Category Created Successfully'
            ]);

        } else {
            // return errors
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($id, Request $request) {

        $category = Category::where('id',$id)->first();

        if (empty($category)) {

            $request->session()->flash('error','Record not found');

            return response([
                'status' => 0
            ]);
        }

        Category::where('id',$id)->delete();

        $request->session()->flash('success','Service deleted successfully.');

        return response([
            'status' => 1
        ]);

    }

    public function getSlug(Request $request){
        $slug = SlugService::createSlug(Category::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }

}
