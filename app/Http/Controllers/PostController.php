<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    public function index()
    {
        $posts = Post::wherePublished(true)->with('owner')->latest()->get();

        if (request()->wantsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('Posts/Posts', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'body' => $request->body,
            'owner_id' => auth()->id(),
            'published' => (isset($request->published)) ? $request->published : 0
        ]);

        if (request()->wantsJson()) {
            return response()->json(['status' => '200', 'post' => $post->load('owner')]);
        }

        return redirect()->route('posts.show', compact('post'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (request()->wantsJson()) {
            return response()->json($post->load('owner'));
        }

        return Inertia::render('Posts/Show', [
            'post' => $post->load('owner')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (request()->wantsJson()) {
            return response()->json($post);
        }

        return Inertia::render('Posts/Edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'body' => $request->body,
            'published' => $request->published
        ]);

        if (request()->wantsJson()) {
            return response()->json(['status' => 200, 'post' => $post]);
        }

        return redirect()->route('posts.show', compact('post'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['status' => 201, 'message' => 'Deleted !!!']);
        }

        return redirect()->route('posts.index');
    }
}
