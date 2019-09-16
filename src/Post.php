<?php

namespace Den1n\NovaBlog;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Post extends \Illuminate\Database\Eloquent\Model
{
    // use Searchable;

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'template' => 'default',
    ];

    protected $appends = [
        'is_published',
        'url',
    ];

    protected $dates = [
        'published_at',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('nova-blog.tables.posts', parent::getTable());
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get value of is_published attribute.
     */
    public function getIsPublishedAttribute (): bool
    {
        return now() >= $this->published_at;
    }

    /**
     * Get value of url attribute.
     */
    public function getUrlAttribute(): string
    {
        return route('nova-blog.show', [
            'post' => $this,
        ]);
    }

    /**
     * Searchable when published only.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->getIsPublishedAttribute();
    }

    /**
     * Include only recent posts.
     */
    public function scopeRecent(Builder $query, int $page = 0, int $limit = 20): Builder
    {
        return $query->latest('published_at', 'desc')
            ->skip($page * $limit)
            ->take($limit);
    }

    /**
     * Include only searched posts.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->whereRaw('ts @@ to_tsquery(:ts)', [
            'ts' => preg_replace('/\s+/', '|', $search),
        ]);
    }

    /**
     * Exclude posts from query.
     */
    public function scopeExclude(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('id', $ids);
    }

    /**
     * Exclude posts when owned by category.
     */
    public function scopeExcludeByCategory(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('category_id', $ids);
    }

    /**
     * Exclude posts when attached to tag.
     */
    public function scopeExcludeByTag(Builder $query, int ...$ids): Builder
    {
        return $query->whereDoesntHave('tags', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }

    /**
     * Get the category of the post.
     */
    public function category()
    {
        return $this->belongsTo(config('nova-blog.models.category'));
    }

    /**
     * Get the tags for the post.
     */
    public function tags()
    {
        return $this->belongsToMany(
            config('nova-blog.models.tag'),
            config('nova-blog.tables.post_tags')
        );
    }

    /**
     * Get the author of the post.
     */
    public function author()
    {
        return $this->belongsTo(config('nova-blog.models.user'))
            ->orderBy('name');
    }
}
