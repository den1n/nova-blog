<div class="blog-tags">
    @foreach($tags as $t)
        <span class="blog-tag">
            <a href="{{ route('nova-blog.tag', ['tag' => $t]) }}" title="{{ __('Posts with tag :name', ['name' => '#' . $t->name]) }}">
                #{{ $t->name }}
            </a>
        </span>
    @endforeach
</div>
