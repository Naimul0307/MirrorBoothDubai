<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;


class DashboardController extends Controller
{
    public function index() {
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');

        // Delete temp images
        $tempFiles = TempFile::where('created_at','<=',$dayBeforeToday)->get();

        foreach($tempFiles as $tempFile){
            $path = public_path('/uploads/temp'.$tempFile->name);
            if(File::exists($path)){
                File::delete($path);
            }
            TempFile::where('id',$tempFile->id)->delete();
        }

        return view('admin.dashboard', [
            'meta_title' => 'ADMIN DASHBOARD | MIRROR MIRROR MIRROR SERVICES L.L.C',
            'meta_description' => 'MANAGE ADMIN SETTINGS AND DATA AT MIRROR BOOTH EVENT SERVICES L.L.C.',
            'meta_keywords' => 'ADMIN DASHBOARD, MIRROR MIRROR, EVENTS, ADMIN PANEL, MIRROR MIRROR MIRROR SERVICES L.L.C.'
        ]);
    }
}
