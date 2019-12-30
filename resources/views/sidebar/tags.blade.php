@if(config('nova-blog.controller.tags_on_sidebar'))
    <div class="card blog-card">
        <div class="card-header">{{ __($title ?? 'Tags') }}</div>
        <div class="card-body">
            <div class="blog-tags">
                @forelse($tags as $t)
                    <span class="blog-tag">
                        <a href="{{ route('nova-blog.tag', ['tag' => $t]) }}" title="{{ __('Posts with tag :name', ['name' => '#' . $t->name]) }}">
                            #{{ $t->name }}
                        </a>
                    </span>
                @empty
                    <div>{{ __('Not found') }}</div>
                @endforelse
            </div>
        </div>
    </div>
@endif
