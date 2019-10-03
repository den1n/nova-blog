<div class="blog-post">
    @include('nova-blog::info', [
        'post' => $post,
    ])
    <div class="blog-post-title">
        <a href="{{ route('nova-blog.show', ['post' => $post]) }}">
            {{ $post->title }}
        </a>
    </div>
    @if($post->annotation)
        <div class="blog-post-annotation">
            {!! $post->annotation !!}
        </div>
        <div class="blog-post-read-next">
            <a href="{{ route('nova-blog.show', ['post' => $post]) }}">
                {{ __('Read next') }}
            </a>
        </div>
    @else
        <div class="blog-post-content">
            {!! $post->content !!}
        </div>
    @endif
</div>
