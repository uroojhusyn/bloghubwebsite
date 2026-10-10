<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| BlogHub ships as a Blade shell over a static frontend prototype, so every
| route maps to exactly one PageController method that returns a view. Page
| content is hydrated client-side from public/assets/js/data.js -- no database
| queries happen in this phase.
|
*/

// ---------------------------------------------------------------- Home
Route::get('/', [PageController::class, 'home'])->name('home');

// ------------------------------------------------------------ Blog reads
Route::get('/blogs', [PageController::class, 'blogs'])->name('blogs');
Route::get('/blog-details', [PageController::class, 'blogDetails'])->name('blog-details');

// ----------------------------------------------------------- Taxonomies
Route::get('/categories', [PageController::class, 'categories'])->name('categories');
// A single category, addressed by its kebab-case slug. Placed after the exact
// /categories route; the different segment counts mean they never collide.
Route::get('/categories/{slug}', [PageController::class, 'categoryShow'])
    ->name('categories.show')
    ->where('slug', '[a-z0-9-]+');
Route::get('/authors', [PageController::class, 'authors'])->name('authors');
Route::get('/author-profile', [PageController::class, 'authorProfile'])->name('author-profile');

// ------------------------------------------------------- Static content
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// --------------------------------------------------------------- Search
Route::get('/search-results', [PageController::class, 'searchResults'])->name('search-results');

// ----------------------------------------------------------------- Auth
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/forgot-password', [PageController::class, 'forgotPassword'])->name('forgot-password');

// ------------------------------------------------------- Custom 404 page
// Must stay last: the fallback only matches when nothing above it did.
Route::fallback([PageController::class, 'notFound'])->name('404');
