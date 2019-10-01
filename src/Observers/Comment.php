<?php

namespace Den1n\NovaBlog\Observers;

use Den1n\NovaBlog\Models\Comment as Model;

class Comment
{
    /**
     * Handle the Post "saving" event.
     */
    public function saving(Model $comment): void
    {
        $comment->author_id = $comment->author_id ?: auth()->user()->id;
    }
}
