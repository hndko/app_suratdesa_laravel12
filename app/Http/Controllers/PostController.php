<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pengumuman Desa',
            'posts' => Post::with('user')->latest()->paginate(25),
        ];
        return view('backend.post.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Pengumuman Baru',
        ];
        return view('backend.post.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        $input = $request->only(['title', 'content', 'status']);
        $input['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . time();
        $input['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            $file->storeAs('posts', $filename, 'public');
            $input['image'] = 'posts/' . $filename;
        }

        Post::create($input);

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        $data = [
            'title' => 'Edit Pengumuman',
            'post' => $post,
        ];
        return view('backend.post.edit', $data);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        $input = $request->only(['title', 'content', 'status']);
        // Only update slug if title changed significantly or optionally keep it
        $input['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
            }

            $file = $request->file('image');
            $filename = $file->hashName();
            $file->storeAs('posts', $filename, 'public');
            $input['image'] = 'posts/' . $filename;
        }

        $post->update($input);

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if ($post->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
