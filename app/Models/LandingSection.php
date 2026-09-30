<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = ['key', 'title', 'body', 'image_path', 'order', 'is_visible'];

    protected $casts = ['is_visible' => 'boolean'];
}
