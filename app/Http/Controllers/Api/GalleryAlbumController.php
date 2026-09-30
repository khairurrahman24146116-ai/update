<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryAlbumController extends Controller
{
    public function publicIndex()
    {
        return GalleryAlbum::public()->with('items')->latest()->get();
    }

    public function publicShow(string $id)
    {
        $album = GalleryAlbum::public()->with('items')->where('id', $id)->orWhere('slug', $id)->firstOrFail();

        return $album;
    }

    public function index(Request $request)
    {
        return GalleryAlbum::withCount('items')
            ->when($request->search, fn ($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->latest()->paginate(15);
    }

    public function show(GalleryAlbum $galleryAlbum)
    {
        return $galleryAlbum->load('items');
    }

    public function store(Request $request)
    {
        $v = $request->validate(['title' => 'required|string|max:255', 'slug' => 'nullable|string|unique:gallery_albums,slug', 'description' => 'nullable|string', 'cover' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp', 'is_public' => 'boolean']);
        $data = collect($v)->except('cover')->toArray();
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('gallery', 'public');
        }
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $album = GalleryAlbum::create($data);
        ActivityLogger::log('gallery.create', $request->user(), GalleryAlbum::class, $album->id, $data);

        return response()->json($album, 201);
    }

    public function update(Request $request, GalleryAlbum $galleryAlbum)
    {
        $v = $request->validate(['title' => 'sometimes|string|max:255', 'slug' => 'sometimes|nullable|string|unique:gallery_albums,slug,'.$galleryAlbum->id, 'description' => 'nullable|string', 'cover' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp', 'is_public' => 'boolean']);
        $data = collect($v)->except('cover')->toArray();
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('gallery', 'public');
        }
        $galleryAlbum->update($data);
        ActivityLogger::log('gallery.update', $request->user(), GalleryAlbum::class, $galleryAlbum->id, $data);

        return $galleryAlbum->fresh()->load('items');
    }

    public function destroy(Request $request, GalleryAlbum $galleryAlbum)
    {
        $id = $galleryAlbum->id;
        $galleryAlbum->delete();
        ActivityLogger::log('gallery.delete', $request->user(), GalleryAlbum::class, $id);

        return response()->noContent();
    }

    public function storeItem(Request $request, GalleryAlbum $galleryAlbum)
    {
        $v = $request->validate(['image' => 'required|file|max:5120|mimes:jpg,jpeg,png,webp', 'caption' => 'nullable|string|max:255', 'order' => 'nullable|integer']);
        $path = $request->file('image')->store('gallery/items', 'public');
        $item = GalleryItem::create(['album_id' => $galleryAlbum->id, 'image_path' => $path, 'caption' => $v['caption'] ?? null, 'order' => $v['order'] ?? 0]);
        ActivityLogger::log('gallery.item.create', $request->user(), GalleryItem::class, $item->id, ['album_id' => $galleryAlbum->id]);

        return response()->json($item, 201);
    }

    public function destroyItem(Request $request, GalleryAlbum $galleryAlbum, GalleryItem $item)
    {
        if ((int) $item->album_id !== (int) $galleryAlbum->id) {
            abort(404);
        }
        $item->delete();
        ActivityLogger::log('gallery.item.delete', $request->user(), GalleryItem::class, $item->id);

        return response()->noContent();
    }

    public function updateItem(Request $request, GalleryAlbum $galleryAlbum, GalleryItem $item)
    {
        if ((int) $item->album_id !== (int) $galleryAlbum->id) {
            abort(404);
        }
        $v = $request->validate(['caption' => 'nullable|string|max:255', 'order' => 'nullable|integer']);
        $item->update($v);

        return $item->fresh();
    }
}
