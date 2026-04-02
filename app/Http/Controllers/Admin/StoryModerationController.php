<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;

class StoryModerationController extends Controller
{
    public function index() {
        $stories = Story::with(['tags', 'user'])->where('status', 'pending')->get();
        return view('admin.stories.index', compact('stories'));
    }
    public function approve($id) {
        $story = Story::findOrFail($id);
        $story->status = 'approved';
        $story->save();

        return redirect()->back();
    }
    public function reject($id) {
        $story = Story::findOrFail($id);
        $story->status = 'rejected';
        $story->save();
        return redirect()->back();
    }
}
