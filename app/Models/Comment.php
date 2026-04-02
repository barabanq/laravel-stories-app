<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'story_id',
        'content',
        'parent_id'
    ];
    public function parent() {
    return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function likes() {
        return $this->hasMany(CommentLike::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
