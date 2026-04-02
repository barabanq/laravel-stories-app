<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FollowNotification;

class UserController extends Controller
{
    public function show(User $user) {
        return view('user.show', compact ('user'));
    }

    public function subscribe(User $user) {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return back(); //нельзя подписываться на себя
        }

        //если уже подписан - отписка
        if ($authUser->following()->where('following_id', $user->id)->exists()) {
            $authUser->following()->detach($user->id);
        } else {
                $authUser->following()->attach($user->id);

                //отправка уведомления
                $user->notify(new FollowNotification($authUser));
        }
        return back();
    }
}
