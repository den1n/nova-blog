<?php

namespace Den1n\NovaBlog\Models;

class Comment extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'rating' => 0,
    ];

    protected $appends = [
        'readable_created_at',
        'readable_updated_at',
    ];

    protected $with = [
        'author',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('nova-blog.tables.comments', parent::getTable());
    }

    /**
     * Get value of readable_created_at attribute.
     */
    public function getReadableCreatedAtAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get value of readable_updated_at attribute.
     */
    public function getReadableUpdatedAtAttribute(): string
    {
        return $this->updated_at->diffForHumans();
    }

    /**
     * Get the post.
     */
    public function post()
    {
        return $this->belongsTo(config('nova-blog.models.post'));
    }

    /**
     * Get the author.
     */
    public function author()
    {
        return $this->belongsTo(config('nova-blog.models.user'));
    }
}
