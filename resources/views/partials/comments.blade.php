@if(config('nova-blog.controller.allow_commenting'))
    <div class="card blog-card">
        <div class="card-header">{{ __('Comments') }}</div>
        <div class="card-body">
            <nova-blog-comments :post="{{ $post }}" :comments="{{ $post->comments }}" :user="{{ auth()->user() ?: '{}' }}" locale="{{ app()->getLocale() }}">
                <a style="align-self: center;" href="{{ route('nova-blog.login', ['next' => route('nova-blog.post', ['post' => $post])]) }}">
                    {{ __('Log in to leave a comment') }}
                </a>
            </nova-blog-comments>
        </div>
    </div>
@endif
