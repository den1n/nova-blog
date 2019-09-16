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
        $resources['post']::$model = $models['post'];
        $resources['category']::$model = $models['category'];
        $resources['tag']::$model = $models['tag'];

        if ($resources['post'] == PostResource::class) {
            Nova::resources([
                $resources['post'],
            ]);
        }

        if ($resources['category'] == CategoryResource::class) {
            Nova::resources([
                $resources['category'],
            ]);
        }

        if ($resources['tag'] == TagResource::class) {
            Nova::resources([
                $resources['tag'],
            ]);
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
            'categoryUriKey' => $resources['category']::uriKey(),
            'tagUriKey' => $resources['tag']::uriKey(),
        ]);
	}
}
