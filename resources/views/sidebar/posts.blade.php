<div class="card">
    <div class="card-header">{{ __($title ?? 'Posts') }}</div>
    <div class="card-body">
        @forelse($posts as $p)
            <div class="blog-post">
                @include('nova-blog::info', [
                    'post' => $p,
                ])
                <div class="blog-post-title">
                    <a href="{{ route('nova-blog.show', ['post' => $p]) }}">{{ $p->title }}</a>
                </div>
            </div>
        @empty
            <div>{{ __('Not found') }}</div>
        @endforelse
    </div>
</div>
