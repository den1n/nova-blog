<?php

namespace Den1n\NovaBlog\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;

class Keywords extends \Laravel\Nova\Fields\Field
{
    public $component = 'nova-blog-keywords-field';

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        get_class($model)::saving(function ($model) use ($request, $requestAttribute) {
            $model->keywords = array_unique(array_filter(explode('|', $request[$requestAttribute])));
        });
    }

    /**
     * Resolve the given attribute from the given resource.
     */
    public function resolveAttribute($resource, $attribute = null)
    {
        return $resource->keywords;
    }
}
