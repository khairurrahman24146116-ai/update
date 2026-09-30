<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function publicIndex()
    {
        return NewsCategory::orderBy('name')->get(['id', 'name', 'slug', 'description']);
    }

    public function index()
    {
        return NewsCategory::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $v = $request->validate(['name' => 'required|string|max:255', 'slug' => 'nullable|string|unique:news_categories,slug', 'description' => 'nullable|string']);
        $c = NewsCategory::create($v);
        ActivityLogger::log('news_category.create', $request->user(), NewsCategory::class, $c->id, $v);

        return response()->json($c, 201);
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $v = $request->validate(['name' => 'sometimes|string|max:255', 'slug' => 'sometimes|nullable|string|unique:news_categories,slug,'.$newsCategory->id, 'description' => 'nullable|string']);
        $newsCategory->update($v);
        ActivityLogger::log('news_category.update', $request->user(), NewsCategory::class, $newsCategory->id, $v);

        return $newsCategory->fresh();
    }

    public function destroy(Request $request, NewsCategory $newsCategory)
    {
        if ($newsCategory->news()->exists()) {
            abort(409, 'Kategori masih dipakai berita');
        }
        $newsCategory->delete();
        ActivityLogger::log('news_category.delete', $request->user(), NewsCategory::class, $newsCategory->id);

        return response()->noContent();
    }
}
