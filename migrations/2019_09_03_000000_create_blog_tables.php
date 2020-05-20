<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = config('nova-blog.tables');

        Schema::create($tables['categories'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($tables['posts'], function (Blueprint $table) use ($tables) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->jsonb('keywords')->nullable()->default('[]');
            $table->string('description')->nullable();
            $table->string('template');
            $table->text('annotation')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('category_id')->constrained($tables['categories'])->onDelete('cascade');
            $table->foreignId('author_id')->constrained($tables['users'])->onDelete('cascade');
            $table->timestamps();
            $table->timestamp('published_at')->useCurrent();

            $table->index('category_id');
            $table->index('author_id');
            $table->index('published_at');
        });

        if (config('database.default') === 'pgsql') {
            DB::statement(sprintf('alter table %s add ts tsvector null', $tables['posts']));
            DB::statement(sprintf('create index %1$s_ts_index on %1$s using gin (ts)', $tables['posts']));
        }

        Schema::create($tables['tags'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($tables['post_tags'], function (Blueprint $table) use ($tables) {
            $table->foreignId('post_id')->constrained($tables['posts'])->onDelete('cascade');
            $table->foreignId('tag_id')->constrained($tables['tags'])->onDelete('cascade');

            $table->index('post_id');
            $table->index('tag_id');
        });

        Schema::create($tables['comments'], function (Blueprint $table) use ($tables) {
            $table->increments('id');
            $table->string('content', 4000);
            $table->foreignId('post_id')->constrained($tables['posts'])->onDelete('cascade');
            $table->foreignId('author_id')->constrained($tables['users'])->onDelete('cascade');
            $table->timestamps();

            $table->index('post_id');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = config('nova-blog.tables');
        Schema::dropIfExists($tables['comments']);
        Schema::dropIfExists($tables['post_tags']);
        Schema::dropIfExists($tables['tags']);
        Schema::dropIfExists($tables['posts']);
        Schema::dropIfExists($tables['categories']);
    }
}
