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
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->string('template');
            $table->text('annotation')->nullable();
            $table->text('content')->nullable();
            $table->integer('category_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->timestamps();
            $table->timestamp('published_at')->useCurrent();

            $table->foreign('category_id')->references('id')->on($tables['categories']);
            $table->foreign('author_id')->references('id')->on($tables['users']);

            $table->index('published_at');
        });

        DB::statement(sprintf('alter table %s add ts tsvector null', $tables['posts']));
        DB::statement(sprintf('create index %1$s_ts_index on %1$s using gin (ts)', $tables['posts']));

        Schema::create($tables['tags'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($tables['post_tags'], function (Blueprint $table) use ($tables) {
            $table->integer('post_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->foreign('post_id')->references('id')->on($tables['posts']);
            $table->foreign('tag_id')->references('id')->on($tables['tags']);

        });

        Schema::create($tables['comments'], function (Blueprint $table) use ($tables) {
            $table->increments('id');
            $table->string('content', 4000);
            $table->integer('rating')->nullable()->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('post_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on($tables['comments']);
            $table->foreign('post_id')->references('id')->on($tables['posts']);
            $table->foreign('author_id')->references('id')->on($tables['users']);
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
