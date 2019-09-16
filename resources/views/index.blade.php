@extends('nova-blog::layout')

@section('title', __('Recent posts'))

@section('nova-blog-content')
    <div class="card">
        <div class="card-header">{{ __('Recent posts') }}</div>
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
    @include('nova-blog::sidebar.categories', [
        'categories' => $categories,
    ])
    @include('nova-blog::sidebar.tags', [
        'tags' => $tags,
    ])
@endsection
