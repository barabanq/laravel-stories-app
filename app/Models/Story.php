<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;

class Story extends Model
{
    //
    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
