<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['app_name', 'nspp', 'npsn', 'jenjang', 'akreditasi', 'milad', 'tagline', 'vision', 'address', 'logo_path', 'favicon', 'kop_surat', 'phone', 'pstn', 'wa_helpdesk', 'email', 'map_lat', 'map_lng', 'instagram', 'youtube', 'tiktok', 'facebook', 'hero_image'];

    protected $casts = ['social_enabled' => 'array'];
}
