<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts', 'public');
            }
        }

        $request->user()->posts()->create([
            'title'   => $validated['title'],
            'slug'    => Str::slug($validated['title']) . '-' . uniqid(),
            'content' => $validated['content'],
            'images'  => empty($imagePaths) ? null : $imagePaths,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $imagePaths = $post->images ?? [];
        if ($request->hasFile('images')) {
            if (!empty($imagePaths)) {
                foreach ($imagePaths as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts', 'public');
            }
        }

        $post->update([
            'title'   => $validated['title'],
            'slug'    => Str::slug($validated['title']) . '-' . uniqid(),
            'content' => $validated['content'],
            'images'  => empty($imagePaths) ? null : $imagePaths,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil diupdate.');
    }

    public function destroy(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        if (!empty($post->images)) {
            foreach ($post->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil dihapus.');
    }
}
