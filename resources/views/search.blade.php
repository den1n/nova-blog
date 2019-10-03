@extends('nova-blog::layout')

@section('title', __('Search Results'))

@section('nova-blog-content')
    <div class="card blog-card">
        <div class="card-header">{{ __('Search Results') }}</div>
        <div class="card-body blog-post-list">
            @forelse($posts as $p)
                @include('nova-blog::partials.post', [
                    'post' => $p,
                ])
            @empty
                <div class="blog-notice">
                    {{ __('Nothing found') }}.
                </div>
            @endforelse
            {{ $posts->links() }}
        </div>
    </div>
@endsection

@section('nova-blog-sidebar')
    @include('nova-blog::sidebar.posts', [
        'posts' => $sidebarPosts,
    ])
    @include('nova-blog::sidebar.categories', [
        'categories' => $categories,
    ])
    @include('nova-blog::sidebar.tags', [
        'tags' => $tags,
    ])
@endsection
