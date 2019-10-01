<?php

namespace Den1n\NovaBlog\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Template extends \Laravel\Nova\Filters\Filter
{
    /**
     * Get the displayable name of the filter.
     */
    public function name(): string
    {
        return __('Template');
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('template', $value);
    }

    /**
     * Get the filter's available options.
     */
    public function options(Request $request): array
    {
        $templates = [];
        foreach (config('nova-blog.controller.templates') as $template)
            $templates[__($template['description'])] = $template['name'];
        return $templates;
    }
}
