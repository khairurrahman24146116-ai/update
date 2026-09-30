<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPrincipal extends Model
{
    protected $fillable = ['name', 'title', 'photo_path', 'signature_path', 'term_start', 'is_current'];

    protected $casts = ['term_start' => 'date', 'is_current' => 'boolean'];
}
