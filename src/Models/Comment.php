<?php

namespace Den1n\NovaBlog\Models;

class Comment extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'is_updated',
        'gravatar_id',
        'readable_created_at',
        'readable_updated_at',
    ];

    protected $with = [
        'author',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $comment) {
            $comment->author_id = $comment->author_id ?: auth()->user()->id;
        });
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('nova-blog.tables.comments', parent::getTable());
    }

    /**
     * Get value of is_updated attribute.
     */
    public function getIsUpdatedAttribute (): bool
    {
        return $this->created_at < $this->updated_at;
    }

    /**
     * Get value of gravatar_id attribute.
     */
    public function getGravatarIdAttribute(): string
    {
        return md5($this->author->email);
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
