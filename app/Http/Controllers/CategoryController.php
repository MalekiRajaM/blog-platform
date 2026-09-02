<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function show(Category $category)
    {
        return response()->json($category->load('posts'));
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($category, 201);
    }

    public function destroy(Request $request, Category $category)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'اجازه‌ی این کار رو نداری.'], 403);
        }

        $category->delete();

        return response()->json(['message' => 'دسته‌بندی حذف شد.']);
    }
}