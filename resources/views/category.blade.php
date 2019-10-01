@extends('nova-blog::layout')

@section('title', $category->name)

@section('nova-blog-content')
    <div class="card blog-card">
        <div class="card-header">{{ $category->name }}</div>
        <div class="card-body blog-post-list">
            @foreach($posts as $p)
                @include('nova-blog::post', [
                    'post' => $p,
                ])
            @endforeach
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
        'title' => 'Another categories',
        'categories' => $anotherCategories,
    ])
    @include('nova-blog::sidebar.tags', [
        'tags' => $tags,
    ])
@endsection
