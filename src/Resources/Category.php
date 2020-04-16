<?php

namespace Den1n\NovaBlog\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = '';

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'slug',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(Request $request): array
    {
        $resources = config('nova-blog.resources');
        return [
            ID::make()->sortable(),

            Text::make(__('Slug'), 'slug')
                ->help(__('Will be filled automatically if leave empty'))
                ->rules('nullable', 'string', 'max:255')
                ->hideFromIndex(),

            Text::make(__('Name'), 'name')
                ->rules('required', 'string', 'max:255')
                ->hideFromIndex()
                ->hideFromDetail(),

            Text::make(__('Name'), 'name', function () {
                return sprintf('<a href="%s" title="%s" target="_blank">%s</a>',
                    $this->url, __('Open posts of category in new window'), $this->name
                );
            })
                ->asHtml()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            DateTime::make(__('Created At'), 'created_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            DateTime::make(__('Updated At'), 'updated_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            HasMany::make(__('Posts'), 'posts', $resources['post']),
        ];
    }

    /**
     * Get the URI key for the resource.
     */
    public static function uriKey(): string
    {
        return 'nova-blog-categories';
    }

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('Categories');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('Category');
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(Request $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(Request $request): array
    {
        return [];
    }
}
