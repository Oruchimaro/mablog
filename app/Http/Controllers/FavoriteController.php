<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    public function store(Post $post)
    {
        $post->favorite();

        if (request()->wantsJson()) {
            return response()
                ->json('Added To Favorites', 201);
        }

        return back();
    }


    public function destroy(Post $post)
    {
        $post->unfavorite();

        if (request()->wantsJson()) {
            return response()
                ->json('Removed From Favorites', 202);
        }

        return back();
    }


    public function isFavorited(Post $post)
    {
        return $post->isFavorited();
    }
}
