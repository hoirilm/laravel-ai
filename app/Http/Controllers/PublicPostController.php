<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('home', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }
}
