<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'cover_path', 'is_public'];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class, 'album_id')->orderBy('order');
    }

    public function scopePublic($q)
    {
        return $q->where('is_public', true);
    }

    protected static function booted(): void
    {
        static::saving(function (GalleryAlbum $m) {
            if (empty($m->slug) && ! empty($m->title)) {
                $base = Str::slug($m->title) ?: 'album-'.$m->id;
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $m->id ?? 0)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
                $m->slug = $slug;
            }
        });
    }
}
