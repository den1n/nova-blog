<?php

namespace Den1n\NovaBlog\Models;

use Illuminate\Database\Eloquent\Builder;

class Tag extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'url',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable()
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
        return route('nova-blog.tag', [
            'tag' => $this,
        ]);
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
