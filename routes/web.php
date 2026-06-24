<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPostController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPostController::class, 'index'])->name('home');
Route::get('/post/{post:slug}', [PublicPostController::class, 'show'])->name('post.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Posts Route
    Route::resource('admin/posts', AdminPostController::class)->names('admin.posts');
});

require __DIR__.'/auth.php';
