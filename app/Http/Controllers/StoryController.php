<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use Illuminate\Routing\Redirector;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\CommentLike;
use App\Models\Notification;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class StoryController extends Controller
{
    //
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);
        $story = new Story();
        $story->title = $request->title;
        $story->content = $request->content;
        $story->status = 'pending';
        $story->user_id = auth::id();
        $story->save();

        $tagsString = $request->tags;
        $tagsArray = explode(' ', $tagsString);
        $tagIds = [];

        foreach ($tagsArray as $tagName) {
            $tagName = trim($tagName);
            $tagName = str_replace('#', '', $tagName);
            if ($tagName === '') {
                continue;
            }
            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);
            $tagIds[] = $tag->id;
        }
        $story->tags()->sync($tagIds);
        return redirect('/my-stories')->with('success', 'История отправлена на модерацию');
    }

    public function index(Request $request) {
        $query = Story::with(['tags', 'user', 'likes'])
        ->where('status', 'approved');

        if($request->q) {
            $search = $request->q;

        $query->where(function($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
            ->orWhere('content', 'like', '%' . $search . '%')
            ->orWhereHas('tags', function ($q) use ($search){
                $q->where('name', 'like', '%' . $search . '%');
            });
        });
        }

        if ($request->sort === 'popular') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        $stories = $query->paginate(5)->withQueryString();
        return view('stories.index', compact('stories'));
    }

    public function byTag($id) {
        $tag = Tag::findOrFail($id);
        $stories = $tag->stories()->where('status', 'approved')->get();
        return view('stories.by-tag', compact('tag', 'stories'));
    }

    public function myStories() {
        $stories = Story::with(['tags', 'user'])->where('user_id', Auth::id())->get();

        return view('stories.my', compact('stories'));
    }

    public function show($id) {
        $story = Story::with(['tags', 'user', 'comments' => function ($q) {
            $q->whereNull('parent_id')->with('replies.user', 'user');
        }
        ])
        ->where('status', 'approved')
        ->findOrFail($id);
        return view('stories.show', compact('story'));
    }

    public function destroy($id) {
        $story = Story::findOrFail($id);
        if ($story->user_id != Auth::id()) {
            abort(403);
        }
        $story->tags()->detach();
        $story->delete();
        return redirect('/my-stories')->with('success', 'История удалена');
    }

    public function edit($id) {
        $story = Story::findOrFail($id);
        if ($story->user_id != Auth::id()) {
            abort(403);
        }
        return view('stories.edit', compact('story'));
    }

    public function update(Request $request, $id) {
        $story = Story::with('tags')->findOrFail($id);
        if ($story->user_id != Auth::id()) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);
        $story->title = $request->title;
        $story->content = $request->content;
        $story->status ='pending';
        $story->save();

        $tagNames = array_filter(array_map('trim', explode(',', $request->tags)));

        $tagIds = [];

        foreach($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        $story->tags()->sync($tagIds);

        return redirect('/my-stories')->with('success', 'История обновлена и отправлена на модерацию');
    }
    public function like($id) {
    $story = Story::findOrFail($id);

    $like = Like::where('user_id', Auth::id())
        ->where('story_id', $id)
        ->first();

    if ($like) {
        $like->delete();
    } else {
        $story->likes()->create([
            'user_id' => Auth::id(),
        ]);
    }

    $likesCount = Like::where('story_id', $id)->count();

    return response()->json([
        'likes' => $likesCount,
        'liked' => !$like
    ]);
}
    public function comment(Request $request, $id) {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'story_id' => $id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);


        return response()->json([
            'id' => $comment->id,
            'content' => $comment->content,
            'user' => Auth::user()->name,
            'parent_id' => $comment->parent_id
        ]);
    }

    public function deleteComment($id) {
        $comment = Comment::findOrFail($id);

        if($comment->user_id != Auth::id()) {
            abort(403);
        }
        $comment->delete();
        return back()->with('success', 'Комментарий удален');
    }
    public function updateComments(Request $request, $id) {
        $comment = Comment::findOrFail($id);
        if($comment->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->content = $request->content;
        $comment->save();

        return back()->with('success', 'Комментарий обновлен');
    }

    public function feed() {
        $user = Auth::user();

        $followingIds = $user->following()->pluck('users.id');

        $stories = Story::with(['user', 'tags'])
        ->withCount('likes')
        ->where(function ($q) use ($followingIds, $user) {
            $q->whereIn('user_id', $followingIds)
              ->orWhere('user_id', $user->id);
        })
        ->where('status', 'approved')
        ->latest()
        ->paginate(5);

        return view ('stories.feed', compact('stories'));
    }
    public function likeComment($id) {
        $like = CommentLike::where('user_id', Auth::id())
        ->where('comment_id', $id)
        ->first();

        if($like) {
            $like->delete();
        } else {
            CommentLike::create([
                'user_id' => Auth::id(),
                'comment_id' => $id,
            ]);
        }
    }
    public function notifications()
{
    $notifications = Auth::user()->notifications()->latest()->get();

    return view('notifications.index', compact('notifications'));
}

    public function searchUsers(Request $request) {
        $q = $request->q;

        $users = User::where('name', 'like', "%$q%")->get();
        return view ('user.index', compact('users'));
    }
}
