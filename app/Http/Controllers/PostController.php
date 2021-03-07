<?php

namespace App\Http\Controllers;

use File;
use Storage;
use Image;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])
            ->except(['index', 'show', 'limiter']);
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
        $image = $this->storeImage($request); //image handler

        $post = Post::create([ //authorization in request class
            'title' => $request->title,
            'slug' => time(). '-' .Str::slug($request->title, '-'),
            'body' => $request->body,
            'owner_id' => auth()->id(),
            'published' => (isset($request->published)) ? $request->published : 0,
            'thumb_img' => $image['thumb'],
            'cover_img' => $image['cover']
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

        $this->deleteImage($post->thumb_img);
        $this->deleteImage($post->cover_img);
        
        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted !!!'], 202);
        }    
    
        return redirect()->route('posts.index');
    }

    protected function storeImage($request)
    {
        if ($request->hasFile('cover'))
        {
            $image = $request->file('cover');

            $path = 'app/public/uploads/cover/' . auth()->id() . '/' ;
            $path_without_app = 'public/uploads/cover/' . auth()->id() . '/' ;

            // $ext = $image->getClientOriginalExtension();
            $ext = 'webp';
            $cname = "cover-". time() . '-' . auth()->id() . '.' . $ext;
            $tname = "thumb-". time() . '-' . auth()->id() . '.' . $ext;

            if ( ! \Storage::exists($path_without_app)) {
                \Storage::makeDirectory($path_without_app, 493, true);
            }

            $cover = Image::make($image->getRealPath())
                ->resize(800, 530)
                ->encode('webp', 80);
            $cover->save( storage_path( $path .$cname  ) );

            $thumb = Image::make($image->getRealPath())
                ->resize(348, 200)
                ->text('MA BLOG' , 0, 10, function($font) {
                    $font->size(24);
                    $font->color(array(0, 0, 0, 0.4));
                })
                ->encode('webp', 80);
            $thumb->save( storage_path( $path .$tname  ) );
        }
        
        return [
            'cover' => '/storage/uploads/cover/'. auth()->id() .'/'. $cname,
            'thumb' => '/storage/uploads/cover/'. auth()->id() .'/'. $tname
        ];
    }

    protected function deleteImage($path)
    {
        if(File::exists(public_path($path)) && $path != "/storage/default.jpeg"){
            File::delete(public_path($path));
        }

        return true;
    }

    /**
     * Get Posts before a specefic post with a limit that defaults to 60
     */
    public function limiter()
    {
        $limit = request()->limit ?: 60 ;

        $posts = Post::where( 'id', '<', request()->post )
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->with('owner')
            ->get();

        return (new PostCollection($posts))
            ->response()
            ->setStatusCode(200);
    }
}
