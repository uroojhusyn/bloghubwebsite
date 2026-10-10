<?php

namespace App\Http\Controllers;

/**
 * PageController
 *
 * One method per page, each returning a Blade view from resources/views.
 * No database access in this phase -- views are a shell and the markup is
 * populated client-side from public/assets/js/data.js.
 */
class PageController extends Controller
{
    /**
     * Home page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Blog listing / archive.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function blogs()
    {
        return view('blogs');
    }

    /**
     * Single blog post detail.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function blogDetails()
    {
        return view('blog-details');
    }

    /**
     * All categories.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function categories()
    {
        return view('categories');
    }

    /**
     * A single category, addressed by its kebab-case slug.
     *
     * There is no server-side lookup in this phase -- the view is a shell and
     * public/assets/js/data.js resolves the slug client-side. Phase 2 swaps
     * this for a real query plus a 404 when the slug is unknown; the URL shape
     * does not have to change, so no shared link will break.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function categoryShow(string $slug)
    {
        return view('category-show', ['slug' => $slug]);
    }

    /**
     * All authors.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function authors()
    {
        return view('authors');
    }

    /**
     * Individual author profile.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function authorProfile()
    {
        return view('author-profile');
    }

    /**
     * About page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Contact page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Search results page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchResults()
    {
        return view('search-results');
    }

    /**
     * Login page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Registration page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function register()
    {
        return view('register');
    }

    /**
     * Forgot password page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function forgotPassword()
    {
        return view('forgot-password');
    }

    /**
     * Privacy policy page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function privacy()
    {
        return view('privacy');
    }

    /**
     * Terms of service page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function terms()
    {
        return view('terms');
    }

    /**
     * Custom 404 page, served by the route fallback with a real 404 status.
     *
     * @return \Illuminate\Http\Response
     */
    public function notFound()
    {
        return response()->view('errors.404', [], 404);
    }
}
