@extends('nova-blog::layout')

@section('title', __('Search result'))

@section('nova-blog-content')
    <div class="card blog-card">
        <div class="card-header">{{ __('Search result') }}</div>
        <div class="card-body blog-post-list">
            @forelse($posts as $p)
                @include('nova-blog::post', [
                    'post' => $p,
                ])
            @empty
                <div class="blog-notice">{{ __('Nothing found') }}.</div>
            @endforelse
            {{ $posts->links() }}
        </div>
    </div>
@endsection

@section('nova-blog-sidebar')
    @include('nova-blog::sidebar.posts', [
        'title' => 'Another posts',
        'posts' => $anotherPosts,
    ])
    @include('nova-blog::sidebar.categories', [
        'categories' => $categories,
    ])
    @include('nova-blog::sidebar.tags', [
        'tags' => $tags,
    ])
@endsection
