<div class="card blog-card">
    <div class="card-header">{{ __($title ?? 'Categories') }}</div>
    <div class="card-body">
        <div class="blog-post-categories">
            @forelse($categories as $c)
                <span class="blog-post-categories-item">
                    <a href="{{ route('nova-blog.category', ['category' => $c]) }}" title="{{ __('Posts in category :name', ['name' => $c->name]) }}">{{ $c->name }}</a>
                </span>
            @empty
                <span>{{ __('Not found') }}</span>
            @endforelse
        </div>
    </div>
</div>
