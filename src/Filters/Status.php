<?php

namespace Den1n\NovaBlog\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Status extends \Laravel\Nova\Filters\Filter
{
    /**
     * Get the displayable name of the filter.
     */
    public function name(): string
    {
        return __('Status');
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Request $request, $query, $value): Builder
    {
        switch ($value) {
            case 'published':
                return $query->where('published_at', '<=', now());
            case 'hidden':
                return $query->where('published_at', '>', now());
        }
    }

    /**
     * Get the filter's available options.
     */
    public function options(Request $request): array
    {
        return [
            __('Is Published') => 'published',
            __('Is Hidden') => 'hidden',
        ];
    }
}
