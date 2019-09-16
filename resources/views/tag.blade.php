@extends('nova-blog::layout')

@section('title', $tag->name)

@section('nova-blog-content')
    <div class="card">
        <div class="card-header">{{ $tag->name }}</div>
        <div class="card-body">
            @foreach($posts as $p)
                @include('nova-blog::post', [
                    'post' => $p,
                ])
            @endforeach
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
        'title' => 'Another tags',
        'tags' => $anotherTags,
    ])
@endsection
