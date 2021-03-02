<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
                ->map(function($post) use ($request){
                    return (new PostResource($post))->toArray($request);
                })
        ];
    }

    public function with($request)
    {
        return [
            'included' => $this->collection
                ->pluck('owner')
                ->unique()
                ->values()
                ->map(function($owner){
                    return new UserResource($owner);
                })
        ];
    }
}
