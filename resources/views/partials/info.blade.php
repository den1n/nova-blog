<div class="blog-post-info">
    <span class="blog-post-category">
        <a href="{{ route('nova-blog.category', ['category' => $post->category]) }}" title="{{ __('Posts in category :name', ['name' => $post->category->name]) }}">
            {{ $post->category->name }}
        </a>
    </span>
    @php

        $updatedTitle = $post->is_updated
            ? sprintf('title="%s: %s', __('Has been updated'), $post->updated_at->diffForHumans())
            : '';

    @endphp
    <span class="blog-post-date" {{ $updatedTitle }}>
        {{ $post->published_at->diffForHumans() }}
    </span>
    <a class="blog-post-avatar" href="{{ route('nova-blog.author', ['id' => $post->author->id]) }}" title="{{ __('Posts by :author', ['author' => $post->author->name]) }}">
        <img src="https://www.gravatar.com/avatar/{{ md5($post->author->email) }}?s=512" alt="{{ __('Avatar') }}">
    </a>
</div>
