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

        if ($validator->passes()) {

            $review = new Review;
            $review->name = $request->name;
            $review->slug = $request->slug;
            $review->status = $request->status;
            $review->save();

            if ($request->image_id > 0) {

                $tempImage = TempFile::find($request->image_id);

                if ($tempImage) {

                    $tempFileName = $tempImage->name;
                    $ext = pathinfo($tempFileName, PATHINFO_EXTENSION);

                    // MAIN IMAGE = SLUG ONLY
                    $newFileName = $review->slug . '.' . $ext;

                    $sourcePath = './uploads/temp/' . $tempFileName;

                    $manager = new ImageManager(new Driver());

                    // SMALL
                    $img = $manager->read($sourcePath);
                    $img->cover(360, 220);
                    $img->save('./uploads/reviews/thumb/small/' . $newFileName);

                    // LARGE
                    $img = $manager->read($sourcePath);
                    $img->scaleDown(1150);
                    $img->save('./uploads/reviews/thumb/large/' . $newFileName);

                    $review->image = $newFileName;
                    $review->save();

                    File::delete($sourcePath);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Review Created Successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => $validator->errors()
        ]);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:reviews,name,' . $review->id,
            'slug' => 'required|unique:reviews,slug,' . $review->id,
        ]);

        if ($validator->passes()) {

            $oldImage = $review->image;

            $review->name = $request->name;
            $review->slug = $request->slug;
            $review->status = $request->status;
            $review->save();

            if ($request->image_id > 0) {

                $tempImage = TempFile::find($request->image_id);

                if ($tempImage) {

                    $tempFileName = $tempImage->name;
                    $ext = pathinfo($tempFileName, PATHINFO_EXTENSION);

                    $newFileName = $review->slug . '.' . $ext;

                    $sourcePath = './uploads/temp/' . $tempFileName;

                    $manager = new ImageManager(new Driver());

                    $img = $manager->read($sourcePath);
                    $img->cover(360, 220);
                    $img->save('./uploads/reviews/thumb/small/' . $newFileName);

                    $img = $manager->read($sourcePath);
                    $img->scaleDown(1150);
                    $img->save('./uploads/reviews/thumb/large/' . $newFileName);

                    File::delete('./uploads/reviews/thumb/small/' . $oldImage);
                    File::delete('./uploads/reviews/thumb/large/' . $oldImage);

                    $review->image = $newFileName;
                    $review->save();

                    File::delete($sourcePath);
                }
            }

            return redirect()->route('reviewList');
        }

        return response()->json([
            'status' => 0,
            'errors' => $validator->errors()
        ]);
    }

    public function removeMainImage(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->image) {
            File::delete('./uploads/reviews/thumb/small/' . $review->image);
            File::delete('./uploads/reviews/thumb/large/' . $review->image);
        }

        $review->image = null;
        $review->save();

        return response()->json([
            'status' => 200,
            'message' => 'Image removed'
        ]);
    }
}
