<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Setting;
use App\Models\FeaturedService;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class SettingsController extends Controller
{
  public function index(){
        $settings = Setting::find(1);

        $categories = Category::orderBy('name','asc')->get();
        $featuredServices = FeaturedService::select('categories.name','featured_services.*')
                            ->leftJoin('categories','categories.id','featured_services.category_id')
                            ->orderBy('sort_order','ASC')->get();

        return view('admin.settings', [
            'settings' => $settings,
            'categories' => $categories,
            'featuredServices' => $featuredServices,
            'meta_title' => 'SETTINGS | MIRROR BOOTH EVENT SERVICES L.L.C',
            'meta_description' => 'CONFIGURE AND MANAGE THE SETTINGS FOR MIRROR BOOTH EVENT SERVICES L.L.C',
            'meta_keywords' => 'SETTINGS, ADMIN, MIRROR BOOTH, SERVICES, EVENTS, ADMIN PANEl, MIRROR BOOTH EVENT SERVICES L.L.C'
        ]);
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(),[
            'website_title' => 'required'
        ]);

        parse_str($request->categories,$categoryArray);

        if (!empty($categoryArray['category'])){
            FeaturedService::truncate();
            foreach($categoryArray['category'] as $key => $category) {
                $featuredService = new FeaturedService;
                $featuredService->category_id = $category;
                $featuredService->sort_order = $key;
                $featuredService->save();
            }
        }

        if($validator->passes()) {
            // Save form values here
            $settings = Setting::find(1);
            if ($settings == null) {
                $settings = new Setting;
                $settings->website_title = $request->website_title;
                $settings->email = $request->email;
                $settings->phone = $request->phone;
                $settings->facebook_url = $request->facebook_url;
                $settings->twitter_url = $request->twitter_url;
                $settings->instagram_url = $request->instagram_url;
                $settings->whatsapp_url = $request->whatsapp_url;
                $settings->linkedin_url = $request->linkedin_url;
                $settings->tiktok_url = $request->tiktok_url;
                $settings->youtube_url = $request->youtube_url;
                $settings->contact_card_one = $request->contact_card_one;
                $settings->contact_card_two = $request->contact_card_two;
                $settings->contact_card_three = $request->contact_card_three;
                $settings->copy = $request->copy;
                $settings->save();
            } else {
                $settings->website_title = $request->website_title;
                $settings->email = $request->email;
                $settings->phone = $request->phone;
                $settings->facebook_url = $request->facebook_url;
                $settings->twitter_url = $request->twitter_url;
                $settings->instagram_url = $request->instagram_url;
                $settings->whatsapp_url = $request->whatsapp_url;
                $settings->linkedin_url = $request->linkedin_url;
                $settings->tiktok_url = $request->tiktok_url;
                $settings->youtube_url = $request->youtube_url;
                $settings->contact_card_one = $request->contact_card_one;
                $settings->contact_card_two = $request->contact_card_two;
                $settings->contact_card_three = $request->contact_card_three;
                $settings->copy = $request->copy;
                $settings->save();
            }

            $request->session()->flash('success', 'Settings saved successfully');

            return response()->json([
                'status' => 200,
            ]);
        } else {
            // Return errors here
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
    }
}
