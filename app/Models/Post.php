<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'body', 'published', 'owner_id'];


    /******************************* Methods *******************************/
    /** 
     * Format Created_at and updated_at columns to make it human readable
     **/

    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    /**
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }


    /**
     * State Changer for Published attribute on posts table
     * with this we can create posts that are published 
     * Post::factory()->count(5)->published()->create();
     */

    public function published()
    {
        return $this->state([
            'published' => true,
        ]);
    }

    /******************************* RelationShips *******************************/
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
