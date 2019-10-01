@extends('nova-blog::layout')

@section('meta-keywords', $post->keywords)
@section('meta-description', $post->description)
@section('title', $post->title)

@section('nova-blog-content')
    <div class="card blog-card">
        <div class="card-header">{{ $post->title }}</div>
        <div class="card-body">
            <div class="blog-post">
                @include('nova-blog::info', [
                    'post' => $post
                ])
                <div class="blog-post-annotation">{!! $post->annotation !!}</div>
                <div class="blog-post-content">{!! $post->content !!}</div>
                @include('nova-blog::tags', [
                    'tags' => $post->tags,
                ])
            </div>
        </div>
    </div>
    <div class="card blog-card">
        <div class="card-header">{{ __('Comments') }}</div>
        <div class="card-body">
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
        'title' => 'Another tags',
        'tags' => $anotherTags,
    ])
@endsection
