<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfileCollection extends ResourceCollection
{
    protected $owner;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $published = $this->collection
        ->filter(function($post) use ($request){
            return $post->published  == true;
        });

        $unpublished = $this->collection
        ->filter(function($post) use ($request){
            $this->owner = $post->owner_id;
            return $post->published  == false;
        });

        //$posts = [
            //'published' => $published,
            //'unpublished' => $unpublished
        //];

        return [
            'unpublished' => $this->when(
                auth()->id() == $this->owner,
                new PostCollection($unpublished)
            ),

            'published' => new PostCollection($published),

        ];
    }
}
