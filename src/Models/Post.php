<?php

namespace Den1n\NovaBlog\Models;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Post extends \Illuminate\Database\Eloquent\Model
{
    use Searchable;

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'template' => 'default',
        'keywords' => '[]',
    ];

    protected $appends = [
        'is_published',
        'is_updated',
        'url',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected $dates = [
        'published_at',
    ];

    protected $hidden = [
        'ts',
    ];

    protected $with = [
        'category',
        'author',
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
     * Get value of is_updated attribute.
     */
    public function getIsUpdatedAttribute (): bool
    {
        return $this->created_at < $this->updated_at;
    }

    /**
     * Get value of url attribute.
     */
    public function getUrlAttribute(): string
    {
        if ($this->exists) {
            return route('nova-blog.post', [
                'post' => $this,
            ]);
        } else
            return '';
    }

    /**
     * Searchable when published only.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->getIsPublishedAttribute();
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'annotation' => $this->annotation,
            'content' => $this->content,
        ];
    }

    /**
     * Get the options for searching engine.
     */
    public function searchableOptions()
    {
        return [
            'column' => 'ts',
            'maintain_index' => true,
            'rank' => [
                'fields' => [
                    'title' => 'A',
                    'annotation' => 'B',
                    'content' => 'C',
                ],
            ],
        ];
    }

    /**
     * Include only recent posts.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Include only posts by author.
     */
    public function scopeAuthor(Builder $query, int ...$ids): Builder
    {
        return $query->whereIn('author_id', $ids);
    }

    /**
     * Exclude posts from query.
     */
    public function scopeExclude(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('id', $ids);
    }

    /**
     * Exclude posts by author.
     */
    public function scopeExcludeByAuthor(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('author_id', $ids);
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
     * Get the category.
     */
    public function category()
    {
        return $this->belongsTo(config('nova-blog.models.category'));
    }

    /**
     * Get the comments.
     */
    public function comments()
    {
        return $this->hasMany(config('nova-blog.models.comment'))
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the tags.
     */
    public function tags()
    {
        return $this->belongsToMany(
            config('nova-blog.models.tag'),
            config('nova-blog.tables.post_tags')
        );
    }

    /**
     * Get the author.
     */
    public function author()
    {
        return $this->belongsTo(config('nova-blog.models.user'));
    }
}
