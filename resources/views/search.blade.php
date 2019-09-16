@extends('nova-blog::layout')

@section('title', __('Search result'))

@section('nova-blog-content')
    <div class="card">
        <div class="card-header">
            {{ __('Search result') }}
        </div>
        <div class="card-body">
            @forelse($posts as $p)
                @include('nova-blog::post', [
                    'post' => $p,
                ])
            @empty
                <span>{{ __('Nothing found') }}</span>
            @endforelse
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
