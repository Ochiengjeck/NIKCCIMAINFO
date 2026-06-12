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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('blog_category_id')->nullable()->after('slug')
                ->constrained('blog_categories')->nullOnDelete();
        });

        // Backfill: turn each distinct legacy `category` string into a real category row.
        if (Schema::hasColumn('blog_posts', 'category')) {
            $values = DB::table('blog_posts')->whereNotNull('category')->distinct()->pluck('category');

            foreach ($values as $value) {
                $name = Str::headline(str_replace('-', ' ', $value));
                $slug = Str::slug($value);

                $categoryId = DB::table('blog_categories')->where('slug', $slug)->value('id');
                if (! $categoryId) {
                    $categoryId = DB::table('blog_categories')->insertGetId([
                        'name' => $name,
                        'slug' => $slug,
                        'sort_order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('blog_posts')->where('category', $value)->update(['blog_category_id' => $categoryId]);
            }

            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('category')->default('news')->after('slug');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('blog_category_id');
        });
    }
};
