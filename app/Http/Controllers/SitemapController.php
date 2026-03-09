<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Blog;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
 public function generate()
    {
        $sitemap = Sitemap::create();

        // === Static pages ===
        $sitemap->add(Url::create('/'))
                ->add(Url::create('/about'))
                ->add(Url::create('/services'))
                ->add(Url::create('/contact'))
                ->add(Url::create('/blogs'))
                ->add(Url::create('/faq'));

        // === Dynamic Category Pages ===
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create("/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at)
            );
        }

        // === Dynamic Service Detail Pages ===
        $services = Service::all();
        foreach ($services as $service) {
            $sitemap->add(
                Url::create("/service/{$service->slug}")
                    ->setLastModificationDate($service->updated_at)
            );
        }

        // === Dynamic Blog Detail Pages ===
        $blogs = Blog::all();
        foreach ($blogs as $blog) {
            $sitemap->add(
                Url::create("/blog/{$blog->slug}")
                    ->setLastModificationDate($blog->updated_at)
            );
        }

        // Save sitemap file
        $sitemap->writeToFile(public_path('sitemap.xml'));

        // Return XML response (optional)
        return Response::make($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
}
