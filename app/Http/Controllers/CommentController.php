<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'is_approved' => false,
        ]);

        return response()->json($comment, 201);
    }

    public function approve(Request $request, Comment $comment)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $comment->is_approved = true;
        $comment->save();

        return response()->json($comment);
    }

    public function index(Post $post)
    {
        $comments = $post->comments()->where('is_approved', true)->with('user')->get();

        return response()->json($comments);
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($request->user()->id !== $comment->user_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'کامنت حذف شد.']);
    }
}