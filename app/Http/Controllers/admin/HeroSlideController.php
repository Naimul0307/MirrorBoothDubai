<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlide;
use App\Models\TempFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class HeroSlideController extends Controller
{
  public function index(Request $request)
    {
        $query = HeroSlide::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $heroSlides = $query->paginate(20);
        return view('admin.hero_slides.list', ['heroSlides' => $heroSlides]);
    }

    public function create()
    {
        return view('admin.hero_slides.create');
    }


    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:hero_slides',
            'slug' => 'required|unique:hero_slides',
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $company = new HeroSlide;
            $company->name = $request->name;
            $company->slug = $request->slug;
            $company->status = $request->status;
            $company->save();

            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                // Replace ID with Slug in the new file name
                $newFileName = $tempFileName . '-' . $company->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/hero_slides/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360, 220);
                $img->save($dPath);

                // Generate Large Thumbnail
                $dPath = './uploads/hero_slides/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);


                // Save new file name in the database
                $company->image = $newFileName;
                $company->save();

                // Delete temp file
                File::delete($sourcePath);
            }

            $request->session()->flash('success','Hero Slide Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Hero Slide Created Successfully'
            ]);

        } else {
            // return errors
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $heroSlide = HeroSlide::findOrFail($id);

        return view('admin.hero_slides.edit', [
            'heroSlide' => $heroSlide
        ]);
    }
    public function update(Request $request, $id)
    {
        $heroSlide = HeroSlide::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:hero_slides,name,' . $heroSlide->id,
            'slug' => 'required|unique:hero_slides,slug,' . $heroSlide->id,
        ]);

        if ($validator->passes()) {
            // Check if service exists
            if (empty($heroSlide)) {
                $request->session()->flash('error', 'Record not found');
                return response()->json([
                    'status' => 0,
                ]);
            }

            $oldImageName = $heroSlide->image;

            $heroSlide->name = $request->name;
            $heroSlide->slug = $request->slug;
            $heroSlide->status = $request->status;
            $heroSlide->save();

            // Handle the main image update
            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                $newFileName = pathinfo($tempFileName, PATHINFO_FILENAME) . '-' . $heroSlide->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/hero_slides/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360,220);
                $img->save($dPath);

                // Delete old small thumbnail
                $sourcePathSmall = './uploads/hero_slides/thumb/small/' . $oldImageName;
                File::delete($sourcePathSmall);

                // Generate Large Thumbnail
                $dPath = './uploads/hero_slides/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Delete old large thumbnail
                $sourcePathLarge = './uploads/hero_slides/thumb/large/' . $oldImageName;
                File::delete($sourcePathLarge);

                $heroSlide->image = $newFileName;
                $heroSlide->save();

                File::delete($sourcePath);
            }

            $request->session()->flash('success', 'Hero Slide updated Successfully');

            return redirect()->route('heroSlideList');

            return response()->json([
                'status' => 200,
                'message' => 'Hero Slide Updated Successfully'
            ]);

        } else {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete(Request $request, $id)
    {
        $heroSlide = HeroSlide::find($id);
        if (!$heroSlide) {
            $request->session()->flash('error', 'Record not found');
            return response(['status' => 0]);
        }

        if ($heroSlide->image) {
            File::delete(public_path('uploads/hero_slides/thumb/small/' . $heroSlide->image));
            File::delete(public_path('uploads/hero_slides/thumb/large/' . $heroSlide->image));
        }

        $heroSlide->delete();

        $request->session()->flash('success', 'Hero Slide deleted successfully');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(HeroSlide::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    }

    public function removeMainImage(Request $request, $id)
    {
            // Find the company by ID
            $heroSlide = HeroSlide::findOrFail($id);
            $imageName = $request->input('image');

            // Check if the image exists in the database
            if ($heroSlide->image === $imageName) {
                // Define paths for large and small images
                $largeImagePath = public_path('uploads/hero_slides/thumb/large/' . $imageName);
                $smallImagePath = public_path('uploads/hero_slides/thumb/small/' . $imageName);

                // Delete the image files from storage
                if (file_exists($largeImagePath)) {
                    unlink($largeImagePath);
                }
                if (file_exists($smallImagePath)) {
                    unlink($smallImagePath);
                }

                // Set the image field in the database to null
                $heroSlide->image = null;

                // Save the hero slide instance
                if ($heroSlide->save()) {
                    return response()->json(['status' => 200, 'message' => 'Main image removed successfully']);
                } else {
                    return response()->json(['status' => 500, 'message' => 'Failed to remove image from the database']);
                }
            }

            return response()->json(['status' => 400, 'message' => 'Image not found']);
    }

}
