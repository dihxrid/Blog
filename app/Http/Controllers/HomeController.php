<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $featuredPosts = Post::published()
            ->featured()
            ->with('categories')
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestPosts = Post::published()
            ->with('categories')
            ->latest('published_at')
            ->take(9)
            ->get();


        return view('home', [
            'featuredPosts' => $featuredPosts,
            'latestPosts' => $latestPosts
        ]);
    }
}
