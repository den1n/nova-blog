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

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('nova-blog.tables.comments', parent::getTable());
    }

    /**
     * Get parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(config('nova-blog.models.comment'), 'parent_id');
    }

    /**
     * Get answers comments.
     */
    public function answers()
    {
        return $this->hasMany(config('nova-blog.models.comment'), 'parent_id');
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
