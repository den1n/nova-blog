<div class="blog-post-tags">
    @foreach($tags as $t)
        <span class="blog-post-tags-item">
            <a href="{{ route('nova-blog.tag', ['tag' => $t]) }}" title="{{ __('Posts with tag :name', ['name' => '#' . $t->name]) }}">
                #{{ $t->name }}
            </a>
        </span>
    @endforeach
</div>
