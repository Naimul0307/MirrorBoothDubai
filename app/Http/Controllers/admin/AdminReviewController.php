<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\TempFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AdminReviewController extends Controller
{
   public function index(Request $request)
    {
        $query = Review::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $reviews = $query->paginate(20);
        return view('admin.reviews.list', ['reviews' => $reviews]);
    }

    public function create()
    {
        return view('admin.reviews.create');
    }


    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:reviews,name',
            'slug' => 'required|unique:reviews,slug',
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $review = new Review;
            $review->name = $request->name;
            $review->slug = $request->slug;
            $review->status = $request->status;
            $review->save();

            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                // Replace ID with Slug in the new file name
                $newFileName = $tempFileName . '-' . $review->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/reviews/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360, 220);
                $img->save($dPath);

                // Generate Large Thumbnail
                $dPath = './uploads/reviews/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                
                // Save new file name in the database
                $review->image = $newFileName;
                $review->save();

                // Delete temp file
                File::delete($sourcePath);
            }

            $request->session()->flash('success','Review Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Review Created Successfully'
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
        $review = Review::findOrFail($id);
        return view('admin.reviews.edit', ['review' => $review]);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:reviews,name,' . $review->id,
            'slug' => 'required|unique:reviews,slug,' . $review->id,
        ]);

        if ($validator->passes()) {
            // Check if review exists
            if (empty($review)) {
                $request->session()->flash('error', 'Record not found');
                return response()->json([
                    'status' => 0,
                ]);
            }

            $oldImageName = $review->image;

            $review->name = $request->name;
            $review->slug = $request->slug;
            $review->status = $request->status;
            $review->save();

            // Handle the main image update
            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                $newFileName = pathinfo($tempFileName, PATHINFO_FILENAME) . '-' . $review->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/reviews/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360,220);
                $img->save($dPath);

                // Delete old small thumbnail
                $sourcePathSmall = './uploads/reviews/thumb/small/' . $oldImageName;
                File::delete($sourcePathSmall);

                // Generate Large Thumbnail
                $dPath = './uploads/reviews/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Delete old large thumbnail
                $sourcePathLarge = './uploads/reviews/thumb/large/' . $oldImageName;
                File::delete($sourcePathLarge);

                $review->image = $newFileName;
                $review->save();

                File::delete($sourcePath);
            }

            $request->session()->flash('success', 'Review updated Successfully');

            return redirect()->route('reviewList');

            return response()->json([
                'status' => 200,
                'message' => 'Review Updated Successfully'
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
        $review = Review::find($id);
        if (!$review) {
            $request->session()->flash('error', 'Record not found');
            return response(['status' => 0]);
        }

        if ($review->image) {
            File::delete(public_path('uploads/reviews/thumb/small/' . $review->image));
            File::delete(public_path('uploads/reviews/thumb/large/' . $review->image));
        }

        $review->delete();

        $request->session()->flash('success', 'Review deleted successfully');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(Review::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    }

    public function removeMainImage(Request $request, $id)
    {
            // Find the review by ID
            $review = Review::findOrFail($id);
            $imageName = $request->input('image');

            // Check if the image exists in the database
            if ($review->image === $imageName) {
                // Define paths for large and small images
                $largeImagePath = public_path('uploads/reviews/thumb/large/' . $imageName);
                $smallImagePath = public_path('uploads/reviews/thumb/small/' . $imageName);

                // Delete the image files from storage
                if (file_exists($largeImagePath)) {
                    unlink($largeImagePath);
                }
                if (file_exists($smallImagePath)) {
                    unlink($smallImagePath);
                }

                // Set the image field in the database to null
                $review->image = null;

                // Save the review instance
                if ($review->save()) {
                    return response()->json(['status' => 200, 'message' => 'Main image removed successfully']);
                } else {
                    return response()->json(['status' => 500, 'message' => 'Failed to remove image from the database']);
                }
            }

            return response()->json(['status' => 400, 'message' => 'Image not found']);
    }
}
