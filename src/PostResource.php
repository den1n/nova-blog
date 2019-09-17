<?php

namespace Den1n\NovaBlog;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class PostResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = '';

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'slug',
        'title',
        'description',
        'content',
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     */
    public static $with = [
        'category',
        'author',
    ];

    /**
     * Indicates if the resource should be displayed in the sidebar.
     */
    public static $displayInNavigation = false;

    /**
     * Build an "index" query for the given resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy('published_at', 'desc');
        }
        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(Request $request): array
    {
        $resources = config('nova-blog.resources');
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('Category'), 'category', $resources['category'])
                ->sortable(),

            $this->makeTemplatesField()
                ->rules('required', 'string')
                ->displayUsingLabels()
                ->sortable(),

            Text::make(__('Slug'), 'slug')
                ->help(__('Will be filled automatically if leave empty'))
                ->rules('nullable', 'string', 'max:255')
                ->hideFromIndex(),

            Text::make(__('Title'), 'title')
                ->rules('required', 'string', 'max:255')
                ->hideFromIndex()
                ->hideFromDetail(),

            Text::make(__('Title'), 'title', function () {
                return sprintf('<a href="%s" title="%s" target="_blank">%s</a>',
                    $this->url, __('Open post in new window'), $this->title
                );
            })
                ->asHtml()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            Text::make(__('Keywords'), 'keywords')
                ->help(__('List of keywords separated by commas'))
                ->rules('nullable', 'string', 'max:255')
                ->hideFromIndex(),

            Text::make(__('Description'), 'description')
                ->rules('nullable', 'string', 'max:255')
                ->hideFromIndex(),

            Boolean::make(__('Is Published'), 'is_published')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make(__('Published At'), 'published_at')
                ->help(__('A date when post will be available for viewing'))
                ->rules('nullable', 'date')
                ->hideFromIndex()
                ->hideFromDetail()
                ->firstDayOfWeek(1),

            $this->makeEditorField(__('Annotation'), 'annotation')
                ->rules('nullable', 'string')
                ->hideFromIndex(),

            $this->makeEditorField(__('Content'), 'content')
                ->rules('nullable', 'string')
                ->hideFromIndex(),

            DateTime::make(__('Created At'), 'created_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make(__('Updated At'), 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make(__('Published At'), 'published_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            BelongsTo::make(__('Author'), 'author', $resources['user'])
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            BelongsToMany::make(__('Tags'), 'tags', $resources['tag']),
        ];
    }

    /**
     * Creates template selection field based on application configuration.
     */
    protected function makeTemplatesField(): Select
    {
        return Select::make(__('Template'), 'template')->options(function () {
            $templates = [];
            foreach (config('nova-blog.controller.templates') as $template)
                $templates[$template['name']] = __($template['description']);
            return $templates;
        });
    }

    /**
     * Creates field for edit rich content of resource based on application configuration.
     */
    protected function makeEditorField($name, $field): \Laravel\Nova\Fields\Field
    {
        $class = config('nova-blog.editor.class');
        $field = $class::make($name, $field);
        foreach (config('nova-blog.editor.options') as $method => $arguments) {
            if (method_exists($field, $method))
                $field->{$method}(...$arguments);
        }
        return $field;
    }

    /**
     * Get the URI key for the resource.
     */
    public static function uriKey(): string
    {
        return 'posts';
    }

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('Posts');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('Post');
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
        return [
            new AuthorFilter,
            new CategoryFilter,
            new TemplateFilter,
            new StatusFilter,
        ];
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
        return [
            (new PublishAction)->canSee(function ($request) {
                return $request->user()->can('blogUpdatePosts');
            }),
        ];
    }
}
