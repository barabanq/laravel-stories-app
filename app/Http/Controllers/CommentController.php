<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Request $request, Story $story) {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'story_id' => $story->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => $comment->user->name,
                'parent_id' => $comment->parent_id,
            ]);
        }
        return redirect()->back();
    }
    public function update(Request $request, Comment $comment) {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        if($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
    public function deleteComment(Comment $comment) {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();
        return redirect()->back();
    }
}
