<?php

namespace Den1n\NovaBlog;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends \Laravel\Nova\Filters\Filter
{
    /**
     * Get the displayable name of the filter.
     */
    public function name(): string
    {
        return __('Author');
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('author_id', $value);
    }

    /**
     * Get the filter's available options.
     */
    public function options(Request $request): array
    {
        return config('nova-blog.models.user')::all()->pluck('id', 'name')->toArray();
    }
}
