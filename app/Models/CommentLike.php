<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;

class CommentLike extends Model
{
    protected $fillable = ['user_id', 'comment_id'];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function comment() {
        return $this->belongsTo(Comment::class);
    }
}
