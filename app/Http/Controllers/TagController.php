<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::all());
    }

    public function store(Request $request)
    {
        if (! in_array($request->user()->role, ['admin', 'author'])) {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($tag, 201);
    }
}