<?php

namespace Den1n\NovaBlog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Category extends \Illuminate\Database\Eloquent\Model
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

        static::saving(function (self $category) {
            $category->slug = static::generateSlug($category);
        });
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
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
        if ($this->exists) {
            return route('nova-blog.category', [
                'category' => $this,
            ]);
        } else
            return '';
    }

    /**
     * Generate unique category slug.
     */
    protected static function generateSlug (self $category): string
    {
        $counter = 1;
        $slug = $original = $category->slug ?: Str::slug($category->name);

        while (static::where('id', '!=', $category->id)->where('slug', $slug)->exists())
            $slug = $original . '-' . (++$counter);

        return $slug;
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
