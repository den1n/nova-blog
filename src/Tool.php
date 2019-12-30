<?php

namespace Den1n\NovaBlog;

use Laravel\Nova\Nova;

class Tool extends \Laravel\Nova\Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     */
    public function boot(): void
    {
        $models = config('nova-blog.models');
        $resources = config('nova-blog.resources');

        foreach ($resources as $name => $class) {
            if ($name !== 'user') {
                $class::$model = $models[$name];
                Nova::resources([$class]);
            }
        }
    }

	/**
	 * Build the view that renders the navigation links for the tool.
	 */
	public function renderNavigation()
	{
        $resources = config('nova-blog.resources');

		return view('nova-blog::navigation', [
            'postUriKey' => $resources['post']::uriKey(),
            'commentUriKey' => $resources['comment']::uriKey(),
            'categoryUriKey' => $resources['category']::uriKey(),
            'tagUriKey' => $resources['tag']::uriKey(),
        ]);
	}
}
