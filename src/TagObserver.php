<?php

namespace Den1n\NovaBlog;

use Illuminate\Support\Str;

class TagObserver
{
    /**
     * Generate unique tag slug.
     */
    protected function generateSlug (Tag $tag): string
    {
        $slug = $tag->slug ?: Str::slug($tag->name);
        if ($count = Tag::where('id', '!=', $tag->id)->where('slug', $slug)->count())
            $slug .= '-' . ($count + 1);
        return $slug;
    }

    /**
     * Handle the Tag "saving" event.
     */
    public function saving(Tag $tag): void
    {
        $tag->slug = $this->generateSlug($tag);
    }
}
