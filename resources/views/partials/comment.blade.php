<div class="blog-comment">
    <a class="blog-comment-anchor" id="comment-{{ $comment->id }}"></a>
    <img class="blog-comment-avatar" src="https://www.gravatar.com/avatar/{{ md5($comment->author->email) }}?s=512" alt="{{ __('Avatar') }}">
    <div class="blog-comment-detail">
        <div class="blog-comment-info">
            <span class="blog-comment-author">{{ $comment->author->name }}</span>
            <span class="blog-comment-date" title="{{ __('Updated At') }}: {{ $comment->updated_at }}">
                {{ $comment->created_at->diffForHumans() }}
            </span>
            <a class="blog-comment-link" href="#comment-{{ $comment->id }}" title="{{ __('Link to comment') }}">#</a>
        </div>
        <div class="blog-comment-content">{{ $comment->content }}</div>
        <div class="blog-comment-controls">
            <a href="#">{{ __('Reply') }}</a>
        </div>
    </div>
</div>
