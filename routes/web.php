<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\Admin\StoryModerationController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

// Пользователи
Route::get('/user/{user}', [UserController::class, 'show']);
Route::post('/user/{user}/subscribe', [UserController::class, 'subscribe'])->middleware('auth');

// Истории
Route::get('/stories', [StoryController::class, 'index']);
Route::get('/stories/{id}', [StoryController::class, 'show'])->where('id', '[0-9]+')->name('stories.show');

//  Теги
Route::get('/tags/{id}', [StoryController::class, 'byTag']);

// Лайки историй
Route::post('/stories/{id}/like', [StoryController::class, 'like'])->middleware('auth');

// КОММЕНТАРИИ
Route::post('/stories/{story}/comment', [CommentController::class, 'store'])->middleware('auth');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware('auth');
Route::delete('/comments/{comment}', [StoryController::class, 'deleteComment'])->middleware('auth');

// ЛАЙКИ КОММЕНТАРИЕВ
Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggle'])->middleware('auth');

// Лента
Route::get('/feed', [StoryController::class, 'feed'])->middleware('auth');

// Уведомления
Route::get('/notifications', [StoryController::class, 'notifications'])->middleware('auth');

// Поиск
Route::get('/users', [StoryController::class, 'searchUsers']);

Route::middleware('auth')->group(function () {
    Route::get('/stories/create', function () {
        return view('stories.create');
    });

    Route::post('/stories', [StoryController::class, 'store']);
    Route::get('/my-stories', [StoryController::class, 'myStories']);
    Route::delete('/stories/{id}', [StoryController::class, 'destroy']);
    Route::get('/stories/{id}/edit', [StoryController::class, 'edit']);
    Route::put('/stories/{id}', [StoryController::class, 'update']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Админка
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/stories', [StoryModerationController::class, 'index']);
    Route::post('/admin/stories/{id}/approve', [StoryModerationController::class, 'approve']);
    Route::post('/admin/stories/{id}/reject', [StoryModerationController::class, 'reject']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';