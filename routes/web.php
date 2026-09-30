<?php

use Illuminate\Support\Facades\Route;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthorProfileController;
use App\Http\Controllers\SearchController;

// Author Controllers
use App\Http\Controllers\Author\AuthorAuthController;
use App\Http\Controllers\Author\AuthorDashboardController;
use App\Http\Controllers\Author\AuthorProjectController;
use App\Http\Controllers\Author\AuthorProductController;
use App\Http\Controllers\Author\AuthorProfileController as AuthorProfileEditController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProjectCategoryController;
use App\Http\Controllers\Admin\AdminCountryController;
use App\Http\Controllers\Admin\AdminProductCategoryController;
use App\Http\Controllers\Admin\AdminAuthorController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminInquiryController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminPartnerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EditorUploadController;

/*
|--------------------------------------------------------------------------
| Common Authenticated Utility Routes (Editor Image Uploads)
|--------------------------------------------------------------------------
*/
Route::post('/editor/upload-image', [EditorUploadController::class, 'upload'])->name('editor.upload-image');

/*
|--------------------------------------------------------------------------
| Public Routes (Nook Magazine Frontend)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

// Products (Matching Images 2 & 3)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{id}/inquiry', [ProductController::class, 'submitInquiry'])->name('products.inquiry');

// Editorial: Articles & News (Completely Separated)
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Author / Architect Profile & Follow System
Route::get('/architect/{username}', [AuthorProfileController::class, 'show'])->name('author.profile');
Route::post('/architect/{id}/follow', [AuthorProfileController::class, 'toggleFollow'])->name('author.follow');

// CMS Pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Universal Search
Route::get('/search', [SearchController::class, 'index'])->name('search');


/*
|--------------------------------------------------------------------------
| Author Guard Authentication & Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('author')->name('author.')->group(function () {
    // Guest Routes
    Route::get('/login', [AuthorAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthorAuthController::class, 'login']);
    Route::get('/register', [AuthorAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthorAuthController::class, 'register']);

    // Authenticated Author Routes
    Route::middleware('auth:author')->group(function () {
        Route::post('/logout', [AuthorAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

        // Profile Edit & Update
        Route::get('/profile', [AuthorProfileEditController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AuthorProfileEditController::class, 'update'])->name('profile.update');

        // Project Submissions (Starts as pending)
        Route::get('/projects', [AuthorProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [AuthorProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [AuthorProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{id}/edit', [AuthorProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{id}', [AuthorProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{id}', [AuthorProjectController::class, 'destroy'])->name('projects.destroy');

        // Product Submissions (Starts as pending)
        Route::get('/products', [AuthorProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AuthorProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AuthorProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [AuthorProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [AuthorProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [AuthorProductController::class, 'destroy'])->name('products.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| Admin Guard Authentication & Control Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Authenticated Admin Guard
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin Profile Management
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        // Project Management (Review, Edit all, Approve, Reject, Delete)
        Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{id}/edit', [AdminProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{id}', [AdminProjectController::class, 'update'])->name('projects.update');
        Route::post('/projects/{id}/approve', [AdminProjectController::class, 'approve'])->name('projects.approve');
        Route::post('/projects/{id}/reject', [AdminProjectController::class, 'reject'])->name('projects.reject');
        Route::post('/projects/{id}/set-feature/{feature}', [AdminProjectController::class, 'setFeature'])->name('projects.set-feature');
        Route::delete('/projects/{id}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

        // Project Categories & Countries Management
        Route::resource('project-categories', AdminProjectCategoryController::class);
        Route::resource('countries', AdminCountryController::class);

        // Product Management (Review, Edit all, Approve, Reject, Delete)
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
        Route::post('/products/{id}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
        Route::post('/products/{id}/reject', [AdminProductController::class, 'reject'])->name('products.reject');
        Route::post('/products/{id}/toggle-property-sell', [AdminProductController::class, 'togglePropertySell'])->name('products.toggle-property-sell');
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Product Categories Management
        Route::resource('product-categories', AdminProductCategoryController::class);

        // Author Management (List, Status toggle, Delete)
        Route::get('/authors', [AdminAuthorController::class, 'index'])->name('authors.index');
        Route::get('/authors/{id}', [AdminAuthorController::class, 'show'])->name('authors.show');
        Route::post('/authors/{id}/toggle-status', [AdminAuthorController::class, 'toggleStatus'])->name('authors.toggle-status');
        Route::delete('/authors/{id}', [AdminAuthorController::class, 'destroy'])->name('authors.destroy');

        // CMS Page Management (Page Create, Edit, Delete)
        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [AdminPageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [AdminPageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{id}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{id}', [AdminPageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{id}', [AdminPageController::class, 'destroy'])->name('pages.destroy');

        // Editorial: Articles Management
        Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
        Route::get('/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/articles/{id}', [AdminArticleController::class, 'update'])->name('articles.update');
        Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');

        // Editorial: Architecture News Management
        Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
        Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
        Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

        // Product Inquiries Management
        Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{id}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::delete('/inquiries/{id}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

        // Partners Management (Footer Partners)
        Route::resource('partners', AdminPartnerController::class)->except(['create', 'show', 'edit']);
        Route::post('/partners/{id}/toggle-status', [AdminPartnerController::class, 'toggleStatus'])->name('partners.toggle-status');

        // General System & Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
