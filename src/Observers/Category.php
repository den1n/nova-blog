<?php

namespace Den1n\NovaBlog\Observers;

use Den1n\NovaBlog\Models\Category as Model;

class Category
{
    /**
     * Generate unique category slug.
     */
    protected function generateSlug (Model $category): string
    {
        $slug = $category->slug ?: \Illuminate\Support\Str::slug($category->name);
        if ($count = config('nova-blog.models.category')::where('id', '!=', $category->id)->where('slug', $slug)->count())
            $slug .= '-' . ($count + 1);
        return $slug;
    }

    /**
     * Handle the Category "saving" event.
     */
    public function saving(Model $category): void
    {
        $category->slug = $this->generateSlug($category);
    }
}
