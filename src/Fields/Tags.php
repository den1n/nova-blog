<?php

namespace Den1n\NovaBlog\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;

class Tags extends \Laravel\Nova\Fields\Field
{
    public $component = 'nova-blog-tags-field';

    /**
     * Set maximum count of suggested tags.
     */
    public function limit(int $limit)
    {
        return $this->withMeta(['limit' => $limit]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        get_class($model)::saved(function ($model) use ($request, $requestAttribute) {
            $tagModel = config('nova-blog.models.tag');
            $tagNames = array_filter(explode('|', $request[$requestAttribute]));
            $tags = $tagModel::whereIn('name', $tagNames)->get();

            foreach ($tagNames as $name) {
                if (!$tags->contains('name', $name))
                    $tags->push($tagModel::create(['name' => $name]));
            }

            $model->tags()->sync($tags->pluck('id'));
        });
    }

    /**
     * Resolve the given attribute from the given resource.
     */
    public function resolveAttribute($resource, $attribute = null)
    {
        return $resource->tags->pluck('name');
    }
}
