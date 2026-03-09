<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingCompany;
use App\Models\TempFile;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminCompanyController extends Controller
{
  public function index(Request $request)
    {
        $query = WorkingCompany::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $companies = $query->paginate(20);
        return view('admin.companies.list', ['companies' => $companies]);
    }

    public function create()
    {
        return view('admin.companies.create');
    }


    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:working_companies',
            'slug' => 'required|unique:working_companies',
        ]);

        if($validator->passes()) {
            // Form validated successfully

            $company = new WorkingCompany;
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
                $dPath = './uploads/companies/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360, 220);
                $img->save($dPath);

                // Generate Large Thumbnail
                $dPath = './uploads/companies/thumb/large/' . $newFileName;
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

            $request->session()->flash('success','Company Created Successfully');

            return response()->json([
                'status' => 200,
                'message' => 'Company Created Successfully'
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
        $company = WorkingCompany::findOrFail($id);
        return view('admin.companies.edit', ['company' => $company]);
    }

    public function update(Request $request, $id)
    {
        $company = WorkingCompany::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:working_companies,name,' . $company->id,
            'slug' => 'required|unique:working_companies,slug,' . $company->id,
        ]);

        if ($validator->passes()) {
            // Check if service exists
            if (empty($company)) {
                $request->session()->flash('error', 'Record not found');
                return response()->json([
                    'status' => 0,
                ]);
            }

            $oldImageName = $company->image;

            $company->name = $request->name;
            $company->slug = $request->slug;
            $company->status = $request->status;
            $company->save();

            // Handle the main image update
            if ($request->image_id > 0) {
                $tempImage = TempFile::where('id', $request->image_id)->first();
                $tempFileName = $tempImage->name;
                $imageArray = explode('.', $tempFileName);
                $ext = end($imageArray);

                $newFileName = pathinfo($tempFileName, PATHINFO_FILENAME) . '-' . $company->slug . '.' . $ext;

                $sourcePath = './uploads/temp/' . $tempFileName;

                // Generate Small Thumbnail
                $dPath = './uploads/companies/thumb/small/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->cover(360,220);
                $img->save($dPath);

                // Delete old small thumbnail
                $sourcePathSmall = './uploads/companies/thumb/small/' . $oldImageName;
                File::delete($sourcePathSmall);

                // Generate Large Thumbnail
                $dPath = './uploads/companies/thumb/large/' . $newFileName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sourcePath);
                $img->scaleDown(1150);
                $img->save($dPath);

                // Delete old large thumbnail
                $sourcePathLarge = './uploads/companies/thumb/large/' . $oldImageName;
                File::delete($sourcePathLarge);

                $company->image = $newFileName;
                $company->save();

                File::delete($sourcePath);
            }

            $request->session()->flash('success', 'Company updated Successfully');

            return redirect()->route('companyList'); 

            return response()->json([
                'status' => 200,
                'message' => 'Company Updated Successfully'
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
        $company = WorkingCompany::find($id);
        if (!$company) {
            $request->session()->flash('error', 'Record not found');
            return response(['status' => 0]);
        }

        if ($company->image) {
            File::delete(public_path('uploads/companies/thumb/small/' . $company->image));
            File::delete(public_path('uploads/companies/thumb/large/' . $company->image));
        }

        $company->delete();

        $request->session()->flash('success', 'Company deleted successfully');

        return response(['status' => 1]);
    }

    public function getSlug(Request $request)
    {
        $slug = SlugService::createSlug(WorkingCompany::class, 'slug', $request->name);
        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    }

    public function removeMainImage(Request $request, $id)
    {
            // Find the company by ID
            $company = WorkingCompany::findOrFail($id);
            $imageName = $request->input('image');

            // Check if the image exists in the database
            if ($company->image === $imageName) {
                // Define paths for large and small images
                $largeImagePath = public_path('uploads/companies/thumb/large/' . $imageName);
                $smallImagePath = public_path('uploads/companies/thumb/small/' . $imageName);

                // Delete the image files from storage
                if (file_exists($largeImagePath)) {
                    unlink($largeImagePath);
                }
                if (file_exists($smallImagePath)) {
                    unlink($smallImagePath);
                }

                // Set the image field in the database to null
                $company->image = null;

                // Save the company instance
                if ($company->save()) {
                    return response()->json(['status' => 200, 'message' => 'Main image removed successfully']);
                } else {
                    return response()->json(['status' => 500, 'message' => 'Failed to remove image from the database']);
                }
            }

            return response()->json(['status' => 400, 'message' => 'Image not found']);
    }

}
