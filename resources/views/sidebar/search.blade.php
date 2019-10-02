@if(config('nova-blog.controller.allow_searching'))
    <div class="card blog-card">
        <div class="card-header">{{ __('Search Posts') }}</div>
        <div class="card-body">
            <div class="blog-search">
                <form action="{{ route('nova-blog.search') }}">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="text" name="query" value="{{ $oldQuery ?? '' }}" placeholder="{{ __('Enter search query') }}" required>
                            <div class="input-group-append ml-2">
                                <button class="btn btn-secondary" type="submit">{{ __('Search') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
