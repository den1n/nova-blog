@if(config('nova-blog.controller.allow_commenting'))
    <div class="card blog-card">
        <div class="card-header">{{ __('Comments') }}</div>
        <div class="card-body">
            @php

                $userAttributes = [];
                if ($user = auth()->user())
                    $userAttributes = array_merge($user->toArray(), ['gravatar_id' => md5($user->email)]);

            @endphp
            <nova-blog-comments :post="{{ $post }}" :comments="{{ $post->comments }}" :user="{{ json_encode((object)$userAttributes) }}" locale="{{ app()->getLocale() }}">
                <a style="align-self: center;" href="{{ route('nova-blog.login', ['next' => route('nova-blog.post', ['post' => $post])]) }}">
                    {{ __('Log in to leave a comment') }}
                </a>
            </nova-blog-comments>
        </div>
    </div>
@endif
