<?php

namespace Den1n\NovaBlog;

use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Generate unique category slug.
     */
    protected function generateSlug (Category $category): string
    {
        $slug = $category->slug ?: Str::slug($category->name);
        if ($count = Category::where('id', '!=', $category->id)->where('slug', $slug)->count())
            $slug .= '-' . ($count + 1);
        return $slug;
    }

    /**
     * Handle the Category "saving" event.
     */
    public function saving(Category $category): void
    {
        $category->slug = $this->generateSlug($category);
    }
}
