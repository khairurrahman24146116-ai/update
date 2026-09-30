<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'image_path', 'category_id', 'status', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'publish');
    }

    protected static function booted(): void
    {
        static::saving(function (News $m) {
            if (empty($m->slug) && ! empty($m->title)) {
                $base = Str::slug($m->title) ?: 'berita-'.$m->id;
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $m->id ?? 0)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
                $m->slug = $slug;
            }
            if ($m->status === 'publish' && empty($m->published_at)) {
                $m->published_at = now();
            }
        });
    }
}
