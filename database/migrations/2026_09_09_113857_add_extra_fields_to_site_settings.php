<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('nspp')->nullable()->after('app_name');
            $table->string('npsn')->nullable()->after('nspp');
            $table->string('jenjang')->default('SMA Sains & Tahfidz Berasrama Penuh')->after('npsn');
            $table->string('akreditasi')->default('A+ Unggul (BAN-SM 2024)')->after('jenjang');
            $table->string('milad')->default('2012 M / 1433 H')->after('akreditasi');
            $table->string('tagline')->default('Membentuk Generasi Unggul Berkarakter Madani & Berwawasan Global')->after('milad');
            $table->text('vision')->nullable()->after('tagline');
            $table->string('hero_image')->nullable()->after('vision');
            $table->string('favicon')->nullable()->after('logo_path');
            $table->string('kop_surat')->nullable()->after('favicon');
            $table->string('email')->nullable()->after('phone');
            $table->string('map_lat')->nullable()->after('email');
            $table->string('map_lng')->nullable()->after('map_lat');
            $table->string('instagram')->nullable()->after('map_lng');
            $table->string('youtube')->nullable()->after('instagram');
            $table->string('tiktok')->nullable()->after('youtube');
            $table->string('facebook')->nullable()->after('tiktok');
            $table->json('social_enabled')->nullable()->after('facebook');
            $table->string('wa_helpdesk')->nullable()->after('social_enabled');
            $table->string('pstn')->nullable()->after('wa_helpdesk');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['nspp', 'npsn', 'jenjang', 'akreditasi', 'milad', 'tagline', 'vision', 'hero_image', 'favicon', 'kop_surat', 'email', 'map_lat', 'map_lng', 'instagram', 'youtube', 'tiktok', 'facebook', 'social_enabled', 'wa_helpdesk', 'pstn']);
        });
    }
};
