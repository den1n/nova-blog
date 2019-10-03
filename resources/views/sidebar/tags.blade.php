@if(config('nova-blog.controller.tags_on_sidebar'))
    <div class="card blog-card">
        <div class="card-header">{{ __($title ?? 'Tags') }}</div>
        <div class="card-body">
            <div class="blog-post-tags">
                @forelse($tags as $t)
                    <span class="blog-post-tags-item">
                        <a href="{{ route('nova-blog.tag', ['tag' => $t]) }}" title="{{ __('Posts with tag :name', ['name' => '#' . $t->name]) }}">
                            #{{ $t->name }}
                        </a>
                    </span>
                @empty
                    <span>{{ __('Not found') }}</span>
                @endforelse
            </div>
        </div>
    </div>
@endif
