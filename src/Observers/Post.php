<?php

namespace Den1n\NovaBlog\Observers;

use Den1n\NovaBlog\Models\Post as Model;

class Post
{
    /**
     * Generate unique post slug.
     */
    protected function generateSlug (Model $post): string
    {
        $counter = 1;
        $slug = $original = $post->slug ?: \Illuminate\Support\Str::slug($post->title);
        while (config('nova-blog.models.post')::where('id', '!=', $post->id)->where('slug', $slug)->exists())
            $slug = $original . '-' . (++$counter);
        return $slug;
    }

    /**
     * Handle the Post "saving" event.
     */
    public function saving(Model $post): void
    {
        $post->published_at = $post->published_at ?: now();
        $post->slug = $this->generateSlug($post);
        $post->author_id = $post->author_id ?: auth()->user()->id;
    }
}
