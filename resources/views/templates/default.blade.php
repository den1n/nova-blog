@extends('nova-blog::layout')

@section('meta-keywords', implode(',', $post->keywords))
@section('meta-description', $post->description)
@section('title', $post->title)

@section('nova-blog-content')
    <div class="card blog-card">
        <div class="card-header">{{ $post->title }}</div>
        <div class="card-body">
            <div class="blog-post">
                @include('nova-blog::partials.info', [
                    'post' => $post,
                ])
                <div class="blog-post-annotation">{!! $post->annotation !!}</div>
                <div class="blog-post-content">{!! $post->content !!}</div>
                @include('nova-blog::partials.tags', [
                    'tags' => $post->tags,
                ])
            </div>
        </div>
    </div>
    @include('nova-blog::partials.comments', [
        'post' => $post,
    ])
@endsection

@section('nova-blog-sidebar')
    @include('nova-blog::sidebar.posts', [
        'posts' => $sidebarPosts,
    ])
    @include('nova-blog::sidebar.categories', [
        'categories' => $sidebarCategories,
    ])
    @include('nova-blog::sidebar.tags', [
        'tags' => $sidebarTags,
    ])
@endsection
