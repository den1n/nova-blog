<?php

namespace Den1n\NovaBlog;

use Illuminate\Http\Request;

class TagsFieldController extends \Illuminate\Routing\Controller
{
    public function index(Request $request)
    {
        $query = config('nova-blog.models.tag')::query();

        if ($tag = $request->tag)
            $query->where('name', 'like', $tag . '%');

        if ($limit = intval($request->limit))
            $query->take($limit);

        return $query->orderBy('name')->get()
            ->pluck('name');
    }
}
