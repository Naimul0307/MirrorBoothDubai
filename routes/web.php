<?php

use App\Http\Controllers\admin\AdminBlogsController;
use App\Http\Controllers\admin\AdminCompanyController;
use App\Http\Controllers\admin\AdminFaqController;
use App\Http\Controllers\admin\AdminReviewController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AdminAddonController;
use App\Http\Controllers\admin\AdminAdvanceSetupController;
use App\Http\Controllers\admin\AdminLocationController;
use App\Http\Controllers\admin\AdminPackageController;
use App\Http\Controllers\admin\AdminBrandController;
use App\Http\Controllers\admin\AdminExtraHourController;
use App\Http\Controllers\admin\AdminPackageTimesController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkingCompanyController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuoteController;

Route::get('/quote-calculator', [QuoteController::class, 'index'])
    ->middleware('admin.auth')
    ->name('quote.index');

Route::get('/project', [HomeController::class, 'project'])->name('project');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/companies', [WorkingCompanyController::class, 'index'])->name('companies.index');
Route::get('/search-services', [HomeController::class, 'searchServices'])->name('services.search');
Route::get('/sitemap.xml', [SitemapController::class, 'generate']);

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/services',[ ServicesController::class, 'index' ])->name('services');
Route::get('/service/{slug}',[ ServicesController::class, 'detail'])->name('service.detail');
Route::get('/category/{slug}', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/contact',[ ContactController::class, 'index' ])->name('contact');
Route::get('/blogs',[ BlogController::class, 'index' ])->name('blogs');
Route::get('/blog/{slug}',[ BlogController::class, 'detail' ])->name('blog.detail');

Route::get('/faq',[ FaqController::class, 'index' ])->name('faq');
Route::post('/send-email',[ ContactController::class, 'sendEmail' ])->name('sendContactEmail');

Route::group(['prefix' => 'account'],function(){

    Route::group(['middleware' => 'guest'],function(){
        Route::get('login',[LoginController::class, 'index'])->name('account.login');
        Route::get('register',[LoginController::class, 'register'])->name('account.register');
        Route::post('process-register',[LoginController::class, 'processRegister'])->name('account.processRegister');
        Route::post('auth',[LoginController::class, 'authenticate'])->name('account.auth');
    });

    Route::group(['middleware' => 'auth'],function(){
        Route::get('logout',[LoginController::class, 'logout'])->name('account.logout');
        Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');
    });
});


Route::group(['prefix' => 'admin'],function(){

    Route::group(['middleware' => 'admin.guest'],function(){
        Route::get('login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('auth',[AdminLoginController::class, 'authenticate'])->name('admin.auth');
    });

    Route::group(['middleware' => 'admin.auth'],function(){
        Route::get('logout',[AdminLoginController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard',[AdminDashboardController::class,'index'])->name('admin.dashboard');

         //service route
        Route::get('/services/create',[ServiceController::class,'create'])->name('service.create.form');
        Route::post('/services/create',[ServiceController::class,'save'])->name('service.create');
        Route::post('/temp/upload',[TempImageController::class,'upload'])->name('tempUpload');
        Route::post('/temp/uploads',[TempImageController::class,'uploadGalleryImage'])->name('uploadGalleryImage');
        Route::post('/service/{id}/remove-image', [ServiceController::class, 'removeMainImage'])->name('service.remove.image');
        Route::post('/service/{id}/remove-gallery-image', [ServiceController::class, 'removeGalleryImage'])->name('service.remove.gallery.image');
        Route::get('/services',[ServiceController::class,'index'])->name('serviceList');
        Route::get('/services/edit/{id}',[ServiceController::class,'edit'])->name('service.edit');
        Route::post('/services/edit/{id}',[ServiceController::class,'update'])->name('service.edit.update');
        Route::post('/services/delete/{id}',[ServiceController::class,'delete'])->name('service.delete');
        Route::get('/services/get-slug',[ServiceController::class,'getSlug'])->name('service.slug');

        //category route
        Route::get('/category',[CategoryController::class,'index'])->name('categoryList');
        Route::get('/category/create',[CategoryController::class,'create'])->name('category.create');
        Route::post('/category/create',[CategoryController::class,'store'])->name('category.save');
        Route::get('/category/edit/{id}',[CategoryController::class,'edit'])->name('category.edit');
        Route::post('/category/edit/{id}',[CategoryController::class,'update'])->name('category.update');
        Route::post('/category/delete/{id}',[CategoryController::class,'delete'])->name('category.delete');
        Route::get('/category/get-slug',[CategoryController::class,'getSlug'])->name('category.slug');

       // Blog Routes
       Route::get('/blog',[AdminBlogsController::class, 'index'])->name('bloglist');
       Route::get('/blogs/create',[AdminBlogsController::class,'create'])->name('blogs.create.form');
       Route::post('/blogs/create',[AdminBlogsController::class,'save'])->name('blogs.create');
       Route::get('/blogs/edit/{id}',[AdminBlogsController::class,'edit'])->name('blogs.edit');
       Route::post('/blogs/edit/{id}', [AdminBlogsController::class, 'update'])->name('blogs.update');
       Route::post('/blogs/delete/{id}',[AdminBlogsController::class,'delete'])->name('blogs.delete');
       Route::get('/blogs/get-slug',[AdminBlogsController::class,'getSlug'])->name('blogs.slug');
       Route::post('/blogs/{id}/remove-image', [AdminBlogsController::class, 'removeMainImage'])->name('blogs.remove.image');
       Route::post('/summernote/image-upload', [AdminBlogsController::class, 'uploadSummernoteImage'])->name('summernote.image.upload');

        // Faq Routes
        Route::get('/faq',[AdminFaqController::class,'index'])->name('faqList');
        Route::get('/faq/create',[AdminFaqController::class,'create'])->name('faq.create.form');
        Route::post('/faq/save',[AdminFaqController::class,'save'])->name('faq.save');
        Route::get('/faq/edit/{id}',[AdminFaqController::class,'edit'])->name('faq.edit');
        Route::post('/faq/edit/{id}',[AdminFaqController::class,'update'])->name('faq.update');
        Route::post('/faq/delete/{id}',[AdminFaqController::class,'delete'])->name('faq.delete');

        //Company Routes
        Route::get('/companies',[AdminCompanyController::class,'index'])->name('companyList');
        Route::get('/companies/create',[AdminCompanyController::class,'create'])->name('company.create');
        Route::post('/companies/create',[AdminCompanyController::class,'save'])->name('company.store');
        Route::get('/companies/edit/{id}',[AdminCompanyController::class,'edit'])->name('company.edit');
        Route::post('/companies/edit/{id}',[AdminCompanyController::class,'update'])->name('company.update');
        Route::post('/companies/delete/{id}',[AdminCompanyController::class,'delete'])->name('company.delete');
        Route::get('/companies/get-slug',[AdminCompanyController::class,'getSlug'])->name('company.slug');
        Route::post('/companies/{id}/remove-image', [AdminCompanyController::class, 'removeMainImage'])->name('company.remove.image');

        // Review Routes
        Route::get('/reviews',[AdminReviewController::class,'index'])->name('reviewList');
        Route::get('/reviews/create',[AdminReviewController::class,'create'])->name('review.create');
        Route::post('/reviews/create',[AdminReviewController::class,'save'])->name('review.store');
        Route::get('/reviews/edit/{id}',[AdminReviewController::class,'edit'])->name('review.edit');
        Route::post('/reviews/edit/{id}',[AdminReviewController::class,'update'])->name('review.update');
        Route::post('/reviews/delete/{id}',[AdminReviewController::class,'delete'])->name('review.delete');
        Route::get('/reviews/get-slug',[AdminReviewController::class,'getSlug'])->name('review.slug');
        Route::post('/reviews/{id}/remove-image', [AdminReviewController::class, 'removeMainImage'])->name('review.remove.image');

        //Addons Route
        Route::get('/addons',[AdminAddonController::class,'index'])->name('addonList');
        Route::get('/addons/create',[AdminAddonController::class,'create'])->name('addon.create');
        Route::post('/addons/create',[AdminAddonController::class,'save'])->name('addon.store');
        Route::get('/addons/edit/{id}',[AdminAddonController::class,'edit'])->name('addon.edit');
        Route::post('/addons/edit/{id}',[AdminAddonController::class,'update'])->name('addon.update');
        Route::post('/addons/delete/{id}',[AdminAddonController::class,'delete'])->name('addon.delete');
        Route::get('/addons/get-slug',[AdminAddonController::class,'getSlug'])->name('addon.slug');
       
        //PackageTimes Route
        Route::get('/packageTimes',[AdminPackageTimesController::class,'index'])->name('packageTimesList');
        Route::get('/packageTimes/create',[AdminPackageTimesController::class,'create'])->name('packageTimes.create');
        Route::post('/packageTimes/create',[AdminPackageTimesController::class,'save'])->name('packageTimes.store');
        Route::get('/packageTimes/edit/{id}',[AdminPackageTimesController::class,'edit'])->name('packageTimes.edit');
        Route::post('/packageTimes/edit/{id}',[AdminPackageTimesController::class,'update'])->name('packageTimes.update');
        Route::post('/packageTimes/delete/{id}',[AdminPackageTimesController::class,'delete'])->name('packageTimes.delete');
        Route::get('/packageTimes/get-slug',[AdminPackageTimesController::class,'getSlug'])->name('packageTimes.slug');
        
        //Advance Setup Route
        Route::get('/advance-setup',[AdminAdvanceSetupController::class,'index'])->name('advanceSetupList');
        Route::get('/advance-setup/create',[AdminAdvanceSetupController::class,'create'])->name('advanceSetup.create');
        Route::post('/advance-setup/create',[AdminAdvanceSetupController::class,'save'])->name('advanceSetup.store');
        Route::get('/advance-setup/edit/{id}',[AdminAdvanceSetupController::class,'edit'])->name('advanceSetup.edit');
        Route::post('/advance-setup/edit/{id}',[AdminAdvanceSetupController::class,'update'])->name('advanceSetup.update');
        Route::post('/advance-setup/delete/{id}',[AdminAdvanceSetupController::class,'delete'])->name('advanceSetup.delete');
        Route::get('/advance-setup/get-slug',[AdminAdvanceSetupController::class,'getSlug'])->name('advanceSetup.slug');
        //Locations Route
        Route::get('/locations',[AdminLocationController::class,'index'])->name('locationList');
        Route::get('/locations/create',[AdminLocationController::class,'create'])->name('location.create');
        Route::post('/locations/create',[AdminLocationController::class,'save'])->name('location.store');
        Route::get('/locations/edit/{id}',[AdminLocationController::class,'edit'])->name('location.edit');
        Route::post('/locations/edit/{id}',[AdminLocationController::class,'update'])->name('location.update');
        Route::post('/locations/delete/{id}',[AdminLocationController::class,'delete'])->name('location.delete');
        Route::get('/locations/get-slug',[AdminLocationController::class,'getSlug'])->name('location.slug');

        //Package Route
        Route::get('/packages',[AdminPackageController::class,'index'])->name('packageList');
        Route::get('/packages/create',[AdminPackageController::class,'create'])->name('package.create');
        Route::post('/packages/create',[AdminPackageController::class,'save'])->name('package.store');
        Route::get('/packages/edit/{id}',[AdminPackageController::class,'edit'])->name('package.edit');
        Route::post('/packages/edit/{id}',[AdminPackageController::class,'update'])->name('package.update');
        Route::post('/packages/delete/{id}',[AdminPackageController::class,'delete'])->name('package.delete');
        Route::get('/packages/get-slug',[AdminPackageController::class,'getSlug'])->name('package.slug');

        
        //Branding Route
        Route::get('/branding',[AdminBrandController::class,'index'])->name('brandingList');
        Route::get('/branding/create',[AdminBrandController::class,'create'])->name('branding.create');
        Route::post('/branding/create',[AdminBrandController::class,'save'])->name('branding.store');
        Route::get('/branding/edit/{id}',[AdminBrandController::class,'edit'])->name('branding.edit');
        Route::post('/branding/edit/{id}',[AdminBrandController::class,'update'])->name('branding.update');
        Route::post('/branding/delete/{id}',[AdminBrandController::class,'delete'])->name('branding.delete');
        Route::get('/pacbrandingkages/get-slug',[AdminBrandController::class,'getSlug'])->name('branding.slug');

        //Extra Hour
        Route::get('/hours',[AdminExtraHourController::class,'index'])->name('hoursList');
        Route::get('/hours/create',[AdminExtraHourController::class,'create'])->name('hours.create');
        Route::post('/hours/create',[AdminExtraHourController::class,'save'])->name('hours.store');
        Route::get('/hours/edit/{id}',[AdminExtraHourController::class,'edit'])->name('hours.edit');
        Route::post('/hours/edit/{id}',[AdminExtraHourController::class,'update'])->name('hours.update');
        Route::post('/hours/delete/{id}',[AdminExtraHourController::class,'delete'])->name('hours.delete');
        Route::get('/hours/get-slug',[AdminExtraHourController::class,'getSlug'])->name('hours.slug');

        // Setting Routes
        Route::get('/settings',[SettingsController::class,'index'])->name('settings.index');
        Route::post('/settings',[SettingsController::class,'save'])->name('settings.save');

    });
});

