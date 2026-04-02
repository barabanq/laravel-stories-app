<?php
namespace App\Services;

use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class StoryService {
    public function create(array $data): Story {
        return Story::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);
    }
    public function update(Story $story, array $data): Story {
        $story->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
        return $story;
    }
    public function toggleLike(Story $story): bool {
        $user = Auth::user();
        $like = $story->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            return false;
        }
        $story->likes()->create(['user_id' => Auth::id()]);
        return true;
    }
}