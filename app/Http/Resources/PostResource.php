<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'post',
            'id' => (string) $this->id,

            'attributes' => [
                'title' => $this->title,
                'slug' => $this->slug,
                'body' => $this->body,
                'published' => $this->published,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'thumb_image' => $this->thumb_image,
                'cover_image' => $this->cover_image
            ],
            
            'relationships' => [
                'owner' => [
                    'data' => [
                        'type' => 'user',
                        'id' => (string) $this->owner_id,
                    ] 
                ]
            ],
        ];
    }

    public function with($request)
    {
        return [
            'included' => [new UserResource($this->whenLoaded('owner'))]
        ];
    }
}
