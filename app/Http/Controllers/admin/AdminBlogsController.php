<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Exports\ServicesExport;
use App\Models\TempFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminBlogsController extends Controller
{
 public function index(Request $request)
    {
        $blogs = Blog::orderBy('created_at','ASC');

        if (!empty($request->keyword)) {
            $blogs = $blogs->where('name','like','%'.$request->keyword.'%');
        }

        $blogs = $blogs->paginate(20);

        $data['blogs'] = $blogs;

        return view('admin.blog.list',$data);
    }
    
    public function create() {
        return view('admin.blog.create');
    }
    
    // This method will save a blog in DB
    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:blogs'
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $blog = new Blog;
            $blog->name = $request->name;
            $blog->description = $request->description;
            $blog->short_desc = $request->short_description;
            $blog->meta_title = $request->meta_title ?: $request->name . ' | MIRROR BOOTH EVENT SERVICES L.L.C';
            $blog->meta_description = $request->meta_description ?: 'EXPLORE ' . $request->name . ' FROM MIRROR BOOTH EVENT SERVICES L.L.C, PROVIDING TOP EVENT SERVICES IN DUBAI.';
            $blog->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI, UAE';
            $blog->image_alt_text = $request->image_alt_text;
            $blog->status = $request->status;
            $blog->save();

            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                // Replace ID with Slug in the new file name
                $newFileName = $tempFileName . '-' . $blog->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/blogs/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360, 220);
                $img->save($dPath);

                // Generate Large Thumbnail
                $dPath = './uploads/blogs/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Save new file name in the database
                $blog->image = $newFileName;
                $blog->save();

                // Delete temp file
                File::delete($sourcePath);
            }

            $request->session()->flash('success','Blog Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Blog Created Successfully'                                       
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
        $blog = Blog::where('id',$id)->first();
        if(empty($blog)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('bloglist');
        }
        $data['blog'] = $blog;
        return view('admin.blog.edit',$data); 
    }

    public function update($id, Request $request) {
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                $request->session()->flash('error', 'Record not found');
                return response()->json(['status' => 0]);
            }

            $oldDescription = $blog->description;
            $oldImageName = $blog->image;

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:blogs,name,' . $blog->id,
                'slug' => 'required|unique:blogs,slug,' . $blog->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->errors()
                ]);
            }

            // Update fields
            $blog->name = $request->name;
            $blog->slug = $request->slug;
            $blog->description = $request->description;
            $blog->short_desc = $request->short_description;
            $blog->meta_title = $request->meta_title ?: $request->name . ' | MIRROR BOOTH EVENT SERVICES L.L.C';
            $blog->meta_description = $request->meta_description ?: 'EXPLORE ' . $request->name . ' FROM MIRROR BOOTH EVENT SERVICES L.L.C, PROVIDING TOP EVENT SERVICES IN DUBAI.';
            $blog->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI, UAE';
            $blog->image_alt_text = $request->image_alt_text;
            $blog->status = $request->status;

            // Detect and delete removed Summernote images
            $oldImages = [];
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $oldDescription, $oldMatches);
            $oldImages = $oldMatches[1] ?? [];

            $newImages = [];
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $request->description, $newMatches);
            $newImages = $newMatches[1] ?? [];

            $deletedImages = array_diff($oldImages, $newImages);

            foreach ($deletedImages as $imgUrl) {
                $parsed = parse_url($imgUrl);
                if (isset($parsed['path'])) {
                    $relativePath = ltrim($parsed['path'], '/');
                    $imagePath = public_path($relativePath);
                    if (Str::contains($imagePath, 'uploads/blogs/summernote') && File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }

            // Process new main image if uploaded
            if ((int)$request->image_id > 0) {
                $tempImage = TempFile::find($request->image_id);

                if ($tempImage) {
                    $tempFileName = $tempImage->name;
                    $ext = pathinfo($tempFileName, PATHINFO_EXTENSION);
                    $baseName = pathinfo($tempFileName, PATHINFO_FILENAME);
                    $newFileName = $baseName . '-' . $blog->slug . '.' . $ext;

                    $sourcePath = public_path('uploads/temp/' . $tempFileName);

                    $manager = new ImageManager(new Driver());

                    // Small Thumbnail
                    $smallThumbPath = public_path('uploads/blogs/thumb/small/' . $newFileName);
                    $img = $manager->read($sourcePath);
                    $img->cover(360, 220);
                    $img->save($smallThumbPath);

                    // Large Thumbnail
                    $largeThumbPath = public_path('uploads/blogs/thumb/large/' . $newFileName);
                    $img = $manager->read($sourcePath);
                    $img->scaleDown(1150);
                    $img->save($largeThumbPath);

                    // Delete old thumbnails
                    File::delete(public_path('uploads/blogs/thumb/small/' . $oldImageName));
                    File::delete(public_path('uploads/blogs/thumb/large/' . $oldImageName));

                    // Update DB and delete temp
                    $blog->image = $newFileName;
                    File::delete($sourcePath);
                }
            }

            $blog->save();

            $request->session()->flash('success', 'Blog updated Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Blog updated Successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Blog Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong. Please check logs.',
                'error' => $e->getMessage() // Only return this in local/dev
            ], 500);
        }
    }
    public function delete($id, Request $request) {
        $blog = Blog::where('id',$id)->first();
        if (empty($blog)) {
            $request->session()->flash('error','Record not found');
            return response([
                'status' => 0
            ]);
        }

        $path = './uploads/blogs/thumb/small/'.$blog->image;
        File::delete($path);

        $path = './uploads/blogs/thumb/large/'.$blog->image;
        File::delete($path);

        Blog::where('id',$id)->delete();

        $request->session()->flash('success','Blog deleted successfully.');

        return response([
            'status' => 1
        ]);
    }


    public function getSlug(Request $request){
        $slug = SlugService::createSlug(Blog::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }

    public function removeMainImage(Request $request, $id)
    {
        // Find the service by ID
        $blog = Blog::findOrFail($id);
        $imageName = $request->input('image');

        // Check if the image exists in the database
        if ($blog->image === $imageName) {
            // Define paths for large and small images
            $largeImagePath = public_path('uploads/blogs/thumb/large/' . $imageName);
            $smallImagePath = public_path('uploads/blogs/thumb/small/' . $imageName);

            // Delete the image files from storage
            if (file_exists($largeImagePath)) {
                unlink($largeImagePath);
            }
            if (file_exists($smallImagePath)) {
                unlink($smallImagePath);
            }

            // Set the image field in the database to null
            $blog->image = null;

            // Save the service instance
            if ($blog->save()) {
                return response()->json(['status' => 200, 'message' => 'Main image removed successfully']);
            } else {
                return response()->json(['status' => 500, 'message' => 'Failed to remove image from the database']);
            }
        }

        return response()->json(['status' => 400, 'message' => 'Image not found']);
    }

    public function uploadSummernoteImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/blogs/summernote');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            return asset('uploads/blogs/summernote/' . $filename);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }

}
