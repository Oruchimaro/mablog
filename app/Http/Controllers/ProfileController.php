<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index($profile)
    {
        $prof = Profile::find($profile);

        $posts = Post::whereOwnerId($prof->user->id)
                ->with('owner')
                ->get();
        
        return (new ProfileCollection($posts))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $prof = $profile->with('user')->find($profile)->first();

        if (request()->wantsJson()) {
            return (new ProfileResource($prof))
                ->response()
                ->setStatusCode(200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'bio' => 'min:5',
            'username' => 'min:5|unique:users,username',
            'email' => 'email:rfc|unique:users,email',
            'phone' => 'unique:users,email',
            'password' => 'min:6'
        ]);

        $profile->update([
            'bio' => $request->bio,
            'resume' => $request->resume
        ]);

        $profile->user()->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password)
        ]);

        return response()->json([
            'message' => "Updated !!!"
        ], 200);
    }
}
