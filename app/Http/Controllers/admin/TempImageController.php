<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempFile;

class TempImageController extends Controller
{
  public function upload(Request $request) {
        $temp = new TempFile;
        $temp->name = 'TEMP VALUE';
        $temp->save();

        $image = $request->file('file');
        $destinationPath = './uploads/temp/';

        // Make sure folder exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $extension = $image->getClientOriginalExtension();
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalName . '-' . uniqid() . '.' . $extension;

        $image->move($destinationPath, $newFileName);

        $temp->name = $newFileName;
        $temp->save();

        return response()->json([
            'status' => 200,
            'id' => $temp->id,
            'name' => $newFileName
        ]);
    }

    public function uploadGalleryImage(Request $request) {
        $temp = new TempFile;
        $temp->name = 'TEMP VALUE';
        $temp->save(); // Creates a blank entry in DB

        $image = $request->file('file');
        $destinationPath = './uploads/temp/';

        // Create directory if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $extension = $image->getClientOriginalExtension();
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        // Avoid conflicts by appending a unique ID
        $newFileName = $originalName . '-' . uniqid() . '.' . $extension;

        // Move the uploaded file
        $image->move($destinationPath, $newFileName);

        // Update DB entry with actual file name
        $temp->name = $newFileName;
        $temp->save();

        return response()->json([
            'status' => 200,
            'id' => $temp->id,
            'name' => $newFileName
        ]);
    }
}
