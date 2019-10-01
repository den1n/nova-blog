<?php

namespace Den1n\NovaBlog\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = '';

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id',
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     */
    public static $with = [
        'post',
        'author',
    ];

    /**
     * Get the value that should be displayed to represent the resource.
     */
    public function title(): string
    {
        return strip_tags($this->content);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     */
    public static function relatableComments(NovaRequest $request, $query): Builder
    {
        try {
            $resource = $request->findResourceOrFail();
            return $query->where('post_id', $resource->post_id)
                ->where('id', '!=', $resource->id);
        } catch (\Throwable $e) {
            return $query;
        }
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(Request $request): array
    {
        $resources = config('nova-blog.resources');
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('Post'), 'post', $resources['post'])
                ->sortable(),

            Text::make(__('Content'), 'content')
                ->hideFromDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->displayUsing(function () {
                    return implode(' ', array_slice(mb_split('\s+', strip_tags($this->content)), 0, 5)) . '...';
                }),

            $this->makeEditorField(__('Content'), 'content')
                ->rules('required', 'string:4000'),

            Number::make(__('Rating'), 'rating')
                ->sortable(),

            BelongsTo::make(__('Parent'), 'parent', $resources['comment'])
                ->hideFromIndex()
                ->nullable(),

            HasMany::make(__('Answers'), 'answers', $resources['comment']),

            DateTime::make(__('Created At'), 'created_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            DateTime::make(__('Updated At'), 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make(__('Author'), 'author', $resources['user'])
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),
        ];
    }

    /**
     * Get the URI key for the resource.
     */
    public static function uriKey(): string
    {
        return 'comments';
    }

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('Comments');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('Comment');
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
            new \Den1n\NovaBlog\Filters\Author,
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
        return [];
    }
}
