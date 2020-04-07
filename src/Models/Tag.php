<?php

namespace Den1n\NovaBlog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Tag extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'url',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $tag) {
            $tag->slug = static::generateSlug($tag);
        });
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('nova-blog.tables.tags', parent::getTable());
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get value of url attribute.
     */
    public function getUrlAttribute(): string
    {
        if ($this->exists) {
            return route('nova-blog.tag', [
                'tag' => $this,
            ]);
        } else
            return '';
    }

    /**
     * Generate unique category slug.
     */
    protected static function generateSlug (self $tag): string
    {
        $counter = 1;
        $slug = $original = $tag->slug ?: Str::slug($tag->name);

        while (static::where('id', '!=', $tag->id)->where('slug', $slug)->exists())
            $slug = $original . '-' . (++$counter);

        return $slug;
    }

    /**
     * Exclude tags from query.
     */
    public function scopeExclude(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('id', $ids);
    }

    /**
     * Exclude tags when owned by a post.
     */
    public function scopeExcludeByPost(Builder $query, int ...$ids): Builder
    {
        return $query->whereDoesntHave('posts', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }

    /**
     * Get the posts of the tag.
     */
    public function posts()
    {
        return $this->belongsToMany(
            config('nova-blog.models.post'),
            config('nova-blog.tables.post_tags')
        );
    }
}
