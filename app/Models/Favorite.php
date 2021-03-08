<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];

    /**============================ Relationships ====================== */
    public function favorited()
    {
        return $this->morphTo();
    }
}
