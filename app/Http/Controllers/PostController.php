<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])
            ->except(['index', 'show']);
    }

    public function index()
    {
        $per_page = request('per_page') ?: 6; 
        $posts = Post::wherePublished(true)->with('owner')->latest();

        if (request()->wantsJson()) {
            return (new PostCollection($posts->paginate($per_page)))
                ->response()
                ->setStatusCode(200);
        }

        return Inertia::render('Posts/Posts', [
            'posts' => $posts->get(),
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
        // authorization in request class
        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'body' => $request->body,
            'owner_id' => auth()->id(),
            'published' => (isset($request->published)) ? $request->published : 0
        ]);

        if (request()->wantsJson()) {
            return (new PostResource($post->load('owner')))
            ->response()
            ->setStatusCode(201);
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
            return (new PostResource($post->load('owner')))
                ->response()
                ->setStatusCode(200);
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
        $this->authorize('update', $post);

        if (request()->wantsJson()) {
            return response()->json($post, 200);
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
        $this->authorize('update', $post);

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'body' => $request->body,
            'published' => $request->published
        ]);

        if (request()->wantsJson()) {
            return (new PostResource($post->load('owner')))
                ->response()
                ->setStatusCode(202);
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
        $this->authorize('delete', $post);

        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted !!!'], 202);
        }

        return redirect()->route('posts.index');
    }
}
