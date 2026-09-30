<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::saving(function (NewsCategory $m) {
            if (empty($m->slug) && ! empty($m->name)) {
                $base = Str::slug($m->name) ?: 'kategori-'.$m->id;
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
