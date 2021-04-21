<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'type' => 'profile',
            'id' => (string)$this->id,
            'bio' => $this->bio,
            'resume' => $this->resume,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'profile_photo_url' => $this->profile_photo_url,
            'joined_since' => $this->created_at->diffForHumans(),
            'email' => $this->when(auth()->id() == $this->user_id, $this->user->email),
            'phone' => $this->when(auth()->id() == $this->user_id, $this->user->phone),
        ];
    }
}
