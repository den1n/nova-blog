@if(config('nova-blog.controller.categories_on_sidebar'))
    <div class="card blog-card">
        <div class="card-header">{{ __($title ?? 'Categories') }}</div>
        <div class="card-body">
            <div class="blog-categories">
                @forelse($categories as $c)
                    <span class="blog-category">
                        <a href="{{ route('nova-blog.category', ['category' => $c]) }}" title="{{ __('Posts in category :name', ['name' => $c->name]) }}">
                            {{ $c->name }}
                        </a>
                    </span>
                @empty
                    <div>{{ __('Not found') }}</div>
                @endforelse
            </div>
        </div>
    </div>
@endif
