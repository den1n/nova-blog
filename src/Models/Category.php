<?php

namespace Den1n\NovaBlog\Models;

use Illuminate\Database\Eloquent\Builder;

class Category extends \Illuminate\Database\Eloquent\Model
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
        return config('nova-blog.tables.categories', parent::getTable());
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
        return route('nova-blog.category', [
            'category' => $this,
        ]);
    }

    /**
     * Exclude categories from query.
     */
    public function scopeExclude(Builder $query, int ...$ids): Builder
    {
        return $query->whereNotIn('id', $ids);
    }

    /**
     * Get the posts of the category.
     */
    public function posts()
    {
        return $this->hasMany(config('nova-blog.models.post'));
    }
}
