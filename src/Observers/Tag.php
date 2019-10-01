<?php

namespace Den1n\NovaBlog\Observers;

use Den1n\NovaBlog\Models\Tag as Model;

class Tag
{
    /**
     * Generate unique tag slug.
     */
    protected function generateSlug (Model $tag): string
    {
        $slug = $tag->slug ?: \Illuminate\Support\Str::slug($tag->name);
        if ($count = config('nova-blog.models.tag')::where('id', '!=', $tag->id)->where('slug', $slug)->count())
            $slug .= '-' . ($count + 1);
        return $slug;
    }

    /**
     * Handle the Tag "saving" event.
     */
    public function saving(Model $tag): void
    {
        $tag->slug = $this->generateSlug($tag);
    }
}
