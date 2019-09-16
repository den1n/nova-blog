<?php

namespace Den1n\NovaBlog;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends \Laravel\Nova\Filters\Filter
{
    /**
     * Get the displayable name of the filter.
     */
    public function name(): string
    {
        return __('Category');
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('category_id', $value);
    }

    /**
     * Get the filter's available options.
     */
    public function options(Request $request): array
    {
        return Category::all()->pluck('id', 'name')->toArray();
    }
}
