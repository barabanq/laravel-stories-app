<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\CommentLike;

class CommentLikeController extends Controller
{
    public function toggle(Comment $comment) {
        dd($comment->id);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unathorized'], 401);
        }
        $like = CommentLike::where('user_id', $user->id)
        ->where('comment_id', $comment->id)
        ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            CommentLike::create([
                'user_id' => $user->id,
                'comment_id' => $comment->id,
            ]);
            $liked = true;
        }
        return response()->json([
            'liked' => $liked,
            'count' => $comment->likes()->count(),
        ]);
    }
}
