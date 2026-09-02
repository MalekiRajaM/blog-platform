<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
   public function index(Request $request)
{
    $query = Post::with(['user', 'category', 'tags'])->where('status', 'published');

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('content', 'like', '%' . $search . '%');
        });
    }

    $posts = $query->get();

    return response()->json($posts);
}

    public function show(Post $post)
    {
        $post->load(['user', 'category', 'tags', 'comments']);

        return response()->json($post);
    }

    public function store(Request $request)
{
    if (! in_array($request->user()->role, ['admin', 'author'])) {
        return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'status' => 'in:draft,published',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
    ]);

    $post = Post::create([
        'title' => $validated['title'],
        'slug' => Str::slug($validated['title']),
        'content' => $validated['content'],
        'category_id' => $validated['category_id'],
        'status' => $validated['status'] ?? 'draft',
        'user_id' => $request->user()->id,
    ]);

    if (isset($validated['tags'])) {
        $post->tags()->attach($validated['tags']);
    }

    return response()->json($post->load('tags'), 201);
}

    public function update(Request $request, Post $post)
{
    if ($request->user()->id !== $post->user_id && $request->user()->role !== 'admin') {
        return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
    }

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'content' => 'sometimes|required|string',
        'category_id' => 'sometimes|required|exists:categories,id',
        'status' => 'in:draft,published',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
    ]);

    if (isset($validated['title'])) {
        $validated['slug'] = Str::slug($validated['title']);
    }

    $post->update($validated);

    if (isset($validated['tags'])) {
        $post->tags()->sync($validated['tags']);
    }

    return response()->json($post->load('tags'));
}

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'پست حذف شد.']);
    }
}