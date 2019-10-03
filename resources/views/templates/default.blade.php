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
                <div class="blog-post-tags">
                    @foreach($post->tags as $t)
                        <span class="blog-post-tags-item">
                            <a href="{{ route('nova-blog.tag', ['tag' => $t]) }}" title="{{ __('Posts with tag :name', ['name' => '#' . $t->name]) }}">
                                #{{ $t->name }}
                            </a>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if(config('nova-blog.controller.allow_commenting'))
        <div class="card blog-card">
            <div class="card-header">{{ __('Comments') }}</div>
            <div class="card-body">
            </div>
        </div>
    @endif
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
