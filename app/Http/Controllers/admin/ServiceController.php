<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ServicesExport;
use App\Models\Category;
use App\Models\Service;
use App\Models\TempFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ServiceController extends Controller
{
  public function index(Request $request) {
        $query = Service::orderBy('created_at','ASC')
            ->select('services.*','categories.name as categoryName')
            ->leftJoin('categories','categories.id','services.category_id');

        if (!empty($request->keyword)) {
            $query->where(function($q) use ($request) {
                $q->where('services.name','like','%'.$request->keyword.'%')
                  ->orWhere('categories.name','like','%'.$request->keyword.'%');
            });
        }

        if (!empty($request->category)) {
            $query->where('services.category_id', $request->category);
        }

        $services = $query->latest('services.id')->paginate(20);

        $data['services'] = $services;
        $data['categories'] = Category::orderBy('name', 'ASC')->get(); // Send categories to the view

        return view('admin.services.list', $data);
    }

    public function create() {
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        return view('admin.services.create',$data);
    }

    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:services',
            'slug' => 'required|unique:services',
            'category' => 'required',
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $service = new Service;
            $service->name = $request->name;
            $service->slug = $request->slug;
            $service->category_id = $request->category;
            $service->description = $request->description;
            $service->short_desc = $request->short_description;
            $service->meta_title = $request->meta_title ?: $request->name . ' | MIRROR BOOTH EVENT SERVICES L.L.C';
            $service->meta_description = $request->meta_description ?: 'EXPLORE ' . $request->name . ' FROM MIRROR BOOTH EVENT SERVICES L.L.C, PROVIDING TOP EVENT SERVICES IN DUBAI.';
            $service->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI, UAE';
            $service->videos_link = $request->videos_link;
            $service->additional_videos_links = $request->input('additional_videos_links'); // Array of additional video links\
            $service->status = $request->status;
            $service->image_alt_text = $request->image_alt_text;
            $service->save();

            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                // Replace ID with Slug in the new file name
                $newFileName = $tempFileName . '-' . $service->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/services/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360, 220);
                $img->save($dPath);

                // Generate Large Thumbnail
                $dPath = './uploads/services/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Save new file name in the database
                $service->image = $newFileName;
                $service->save();

                // Delete temp file
                File::delete($sourcePath);
            }

            // Gallery images handling
            if ($request->has('gallery_images') && $request->image_id > 0) {
                $galleryImageIds = explode(',', $request->gallery_images);
                $galleryImagePaths = [];

                foreach ($galleryImageIds as $imageId) {
                    $tempImage = TempFile::where('id', $imageId)->first();
                    if ($tempImage) {
                        $tempFileName = $tempImage->name;
                        $imageArray = explode('.', $tempFileName);
                        $ext = end($imageArray);

                        // Use slug instead of ID for gallery image name
                        $newFileName = $tempFileName . '-' . $service->slug . '.' . $ext;

                        $sourcePath = './uploads/temp/' . $tempFileName;

                        // Save original size
                        $dPath = './uploads/services/gallery/' . $newFileName;
                        $manager = new ImageManager(new Driver());
                        $img = $manager->read($sourcePath);
                        $img->save($dPath);

                        $galleryImagePaths[] = $newFileName;

                        // Optionally delete temp file
                        File::delete($sourcePath);
                    }
                }

                // Store gallery images paths in the service table
                $service->gallery_images = json_encode($galleryImagePaths);
                $service->save();
            }

            $request->session()->flash('success','Service Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Service Created Successfully'
            ]);

        } else {
            // return errors
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id) {
        $service = Service::findOrFail($id);
        $categories = Category::orderBy('created_at', 'DESC')->get();

        // Check if additional_videos_links is a JSON string or an array
        $additional_videos_links = is_string($service->additional_videos_links)
            ? json_decode($service->additional_videos_links, true)
            : $service->additional_videos_links;

        return view('admin.services.edit', [
            'service' => $service,
            'categories' => $categories,
            'additional_videos_links' => $additional_videos_links,
        ]);
    }

    public function update($id, Request $request) {
        $service = Service::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:services,name,' . $service->id . ',id',
            'slug' => 'required|unique:services,slug,' . $service->id . ',id',
            'category' => 'required',
            'videos_link' => 'nullable|url',
            'short_description' => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->passes()) {
            // Check if service exists
            if (empty($service)) {
                $request->session()->flash('error', 'Record not found');
                return response()->json([
                    'status' => 0,
                ]);
            }

            $oldImageName = $service->image;

            $service->name = $request->name;
            $service->slug = $request->slug;
            $service->category_id = $request->category;
            $service->description = $request->description;
            $service->short_desc = $request->short_description;
            $service->meta_title = $request->meta_title ?: $request->name . ' | MIRROR BOOTH EVENT SERVICES L.L.C';
            $service->meta_description = $request->meta_description ?: 'EXPLORE ' . $request->name . ' FROM MIRROR BOOTH EVENT SERVICES L.L.C, PROVIDING TOP EVENT SERVICES IN DUBAI.';
            $service->meta_keywords = $request->meta_keywords ?: 'MIRROR BOOTH, EVENT SERVICES, ' . $request->name . ', DUBAI, UAE';
            $service->videos_link = $request->videos_link;
            $service->status = $request->status;
            $service->additional_videos_links = json_encode($request->additional_videos_links);
            $service->image_alt_text = $request->image_alt_text;
            $service->image_alt_text = $request->image_alt_text ?: $request->name . ' | MIRROR BOOTH EVENT SERVICES L.L.C';
            $service->save();

            // Handle the main image update
            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                $newFileName = pathinfo($tempFileName, PATHINFO_FILENAME) . '-' . $service->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/services/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360,220);
                $img->save($dPath);

                // Delete old small thumbnail
                $sourcePathSmall = './uploads/services/thumb/small/' . $oldImageName;
                File::delete($sourcePathSmall);

                // Generate Large Thumbnail
                $dPath = './uploads/services/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Delete old large thumbnail
                $sourcePathLarge = './uploads/services/thumb/large/' . $oldImageName;
                File::delete($sourcePathLarge);

                $service->image = $newFileName;
                $service->save();

                File::delete($sourcePath);
            }

            // Handling gallery images
            if ($request->has('gallery_images')) {
                $galleryImageIds = explode(',', $request->gallery_images);
                $galleryImagePaths = [];

                // Add old gallery images
                if (!empty($service->gallery_images)) {
                    $existingGalleryImages = json_decode($service->gallery_images);
                    $galleryImagePaths = array_merge($galleryImagePaths, $existingGalleryImages);
                }

                // Add new gallery images
                foreach ($galleryImageIds as $imageId) {
                    $tempImage = TempFile::where('id', $imageId)->first();
                    if ($tempImage) {
                        $tempFileName = $tempImage->name;
                        $imageArray = explode('.', $tempFileName);
                        $ext = end($imageArray);

                        // Use slug instead of ID for gallery image name
                        $newFileName = $tempFileName . '-' . $service->slug . '.' . $ext;

                        $sourcePath = './uploads/temp/' . $tempFileName;

                        // Save original size image
                        $dPath = './uploads/services/gallery/' . $newFileName;
                        $manager = new ImageManager(new Driver());
                        $img = $manager->read($sourcePath);
                        $img->save($dPath);

                        $galleryImagePaths[] = $newFileName;

                        // Optionally delete the temp image
                        File::delete($sourcePath);
                    }
                }

                // Store gallery image paths in the service table as a JSON string
                $service->gallery_images = json_encode($galleryImagePaths);
                $service->save();
            }

            $request->session()->flash('success', 'Service updated Successfully');

            return redirect()->route('serviceList'); // Redirect to service list route

            return response()->json([
                'status' => 200,
                'message' => 'Service Updated Successfully'
            ]);

        } else {
            // Return validation errors
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function removeMainImage(Request $request, $id)
    {
        // Find the service by ID
        $service = Service::findOrFail($id);
        $imageName = $request->input('image');

        // Check if the image exists in the database
        if ($service->image === $imageName) {
            // Define paths for large and small images
            $largeImagePath = public_path('uploads/services/thumb/large/' . $imageName);
            $smallImagePath = public_path('uploads/services/thumb/small/' . $imageName);

            // Delete the image files from storage
            if (file_exists($largeImagePath)) {
                unlink($largeImagePath);
            }
            if (file_exists($smallImagePath)) {
                unlink($smallImagePath);
            }

            // Set the image field in the database to null
            $service->image = null;

            // Save the service instance
            if ($service->save()) {
                return response()->json(['status' => 200, 'message' => 'Main image removed successfully']);
            } else {
                return response()->json(['status' => 500, 'message' => 'Failed to remove image from the database']);
            }
        }

        return response()->json(['status' => 400, 'message' => 'Image not found']);
    }


    public function removeGalleryImage(Request $request, $id)
    {
        $request->validate(['image' => 'required|string']);
        $image = $request->input('image');
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['status' => 404, 'message' => 'Service not found.']);
        }

        // Decode the gallery images from JSON and filter out the selected image
        $galleryImages = json_decode($service->gallery_images, true);

        if (($key = array_search($image, $galleryImages)) !== false) {
            unset($galleryImages[$key]);
            $service->gallery_images = json_encode(array_values($galleryImages)); // Re-index the array
            $service->save();

            // Check if the file exists and delete it from storage
            $filePath = public_path('uploads/services/gallery/' . $image); // Use public_path() for direct access
            if (file_exists($filePath)) {
                unlink($filePath); // Use unlink() to delete the file
            }

            return response()->json(['status' => 200, 'message' => 'Image removed successfully.']);
        }

        return response()->json(['status' => 400, 'message' => 'Image not found in gallery.']);
    }


    public function delete($id, Request $request) {

        $service = Service::where('id',$id)->first();

        if (empty($service)) {

            $request->session()->flash('error','Record not found');

            return response([
                'status' => 0
            ]);
        }

        $path = './uploads/services/thumb/small/'.$service->image;
        File::delete($path);

        $path = './uploads/services/thumb/large/'.$service->image;
        File::delete($path);

      // Check and delete gallery images if they exist
        if ($service->gallery_images) {
            $galleryImages = json_decode($service->gallery_images); // Assuming it's a JSON array

            foreach ($galleryImages as $image) {
                $galleryImagePath = './uploads/services/gallery/' . $image;
                File::delete($galleryImagePath);
            }
        }

        Service::where('id',$id)->delete();

        $request->session()->flash('success','Service deleted successfully.');

        return response([
            'status' => 1
        ]);
    }


    public function getSlug(Request $request){
        $slug = SlugService::createSlug(Service::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}
