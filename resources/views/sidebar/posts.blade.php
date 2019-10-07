@if(config('nova-blog.controller.posts_on_sidebar'))
    <div class="card blog-card">
        <div class="card-header">{{ __($title ?? 'Recent Posts') }}</div>
        <div class="card-body blog-posts">
            @forelse($posts as $p)
                <div class="blog-post">
                    @include('nova-blog::partials.info', [
                        'post' => $p,
                    ])
                    <div class="blog-post-title">
                        <a href="{{ route('nova-blog.post', ['post' => $p]) }}">
                            {{ $p->title }}
                        </a>
                    </div>
                </div>
            @empty
                <div>{{ __('Not found') }}</div>
            @endforelse
        </div>
    </div>
@endif
