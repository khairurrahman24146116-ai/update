<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_categories', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
        });
        $cats = DB::table('news_categories')->get();
        foreach ($cats as $c) {
            $base = Str::slug($c->name ?? 'kategori-'.$c->id);
            $slug = $base ?: 'kategori-'.$c->id;
            $i = 1;
            $try = $slug;
            while (DB::table('news_categories')->where('slug', $try)->where('id', '!=', $c->id)->exists()) {
                $try = $slug.'-'.(++$i);
            }
            DB::table('news_categories')->where('id', $c->id)->update(['slug' => $try]);
        }
        Schema::table('news_categories', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('slug')->nullable()->after('title');
            $table->text('excerpt')->nullable()->after('slug');
            $table->longText('body')->nullable()->after('excerpt');
            $table->string('image_path')->nullable()->after('body');
            $table->foreignId('category_id')->nullable()->after('image_path')->constrained('news_categories')->nullOnDelete();
            $table->string('status')->default('draft')->after('category_id');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->index(['status', 'published_at']);
        });
        $items = DB::table('news')->get();
        foreach ($items as $n) {
            $base = Str::slug($n->title ?? 'berita-'.$n->id);
            $slug = $base ?: 'berita-'.$n->id;
            $i = 1;
            $try = $slug;
            while (DB::table('news')->where('slug', $try)->where('id', '!=', $n->id)->exists()) {
                $try = $slug.'-'.(++$i);
            }
            DB::table('news')->where('id', $n->id)->update(['slug' => $try]);
        }
        Schema::table('news', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('slug')->nullable()->after('title');
            $table->text('description')->nullable()->after('slug');
            $table->string('cover_path')->nullable()->after('description');
            $table->boolean('is_public')->default(false)->after('cover_path');
            $table->index('is_public');
        });
        $albums = DB::table('gallery_albums')->get();
        foreach ($albums as $a) {
            $base = Str::slug($a->title ?? 'album-'.$a->id);
            $slug = $base ?: 'album-'.$a->id;
            $i = 1;
            $try = $slug;
            while (DB::table('gallery_albums')->where('slug', $try)->where('id', '!=', $a->id)->exists()) {
                $try = $slug.'-'.(++$i);
            }
            DB::table('gallery_albums')->where('id', $a->id)->update(['slug' => $try]);
        }
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->foreignId('album_id')->nullable()->after('id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->string('image_path')->nullable()->after('album_id');
            $table->string('caption')->nullable()->after('image_path');
            $table->integer('order')->default(0)->after('caption');
        });

        Schema::table('p_p_d_b_waves', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->string('banner_path')->nullable()->after('description');
            $table->date('start_date')->nullable()->after('banner_path');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('status')->default('buka')->after('end_date');
            $table->boolean('is_public')->default(true)->after('status');
            $table->index('start_date');
        });
        $waves = DB::table('p_p_d_b_waves')->get();
        foreach ($waves as $w) {
            $base = Str::slug($w->name ?? 'gelombang-'.$w->id);
            $slug = $base ?: 'gelombang-'.$w->id;
            $i = 1;
            $try = $slug;
            while (DB::table('p_p_d_b_waves')->where('slug', $try)->where('id', '!=', $w->id)->exists()) {
                $try = $slug.'-'.(++$i);
            }
            DB::table('p_p_d_b_waves')->where('id', $w->id)->update(['slug' => $try]);
        }
        Schema::table('p_p_d_b_waves', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('p_p_d_b_registrations', function (Blueprint $table) {
            $table->foreignId('wave_id')->nullable()->after('id')->constrained('p_p_d_b_waves')->cascadeOnDelete();
            $table->string('name')->nullable()->after('wave_id');
            $table->string('nisn')->nullable()->after('name');
            $table->string('phone')->nullable()->after('nisn');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->string('status')->default('pending')->after('address');
            $table->text('notes')->nullable()->after('status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('p_p_d_b_registrations', function (Blueprint $table) {
            $table->dropForeign(['wave_id']);
            $table->dropColumn(['wave_id', 'name', 'nisn', 'phone', 'email', 'address', 'status', 'notes']);
        });
        Schema::table('p_p_d_b_waves', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['name', 'slug', 'description', 'banner_path', 'start_date', 'end_date', 'status', 'is_public']);
        });
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropForeign(['album_id']);
            $table->dropColumn(['album_id', 'image_path', 'caption', 'order']);
        });
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['title', 'slug', 'description', 'cover_path', 'is_public']);
        });
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropUnique(['slug']);
            $table->dropColumn(['title', 'slug', 'excerpt', 'body', 'image_path', 'category_id', 'status', 'published_at']);
        });
        Schema::table('news_categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['name', 'slug', 'description']);
        });
    }
};
