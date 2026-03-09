<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\FeaturedService;
use App\Models\Setting;

function getSettings(){
    return Setting::first();
}

function getCategories(){
    return FeaturedService::leftJoin('categories','categories.id','featured_services.category_id')
    ->orderBy('sort_order','ASC')
    ->get();
}
?>
