<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
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
            'type' => 'user',
            'id' => (string)$this->id,
            'attributes' => [
                'data' => [
                    'name' => $this->name,
                    'username' => $this->username,
                    'profile_photo_url' => $this->profile_photo_url,
                    'joined_since' => $this->created_at->diffForHumans()
                ]
            ]
        ];
    }
}
