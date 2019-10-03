@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ mix('index.css', 'vendor/nova-blog') }}">
    <div class="blog-content col-lg-8">
        @yield('nova-blog-content')
    </div>
    <div class="blog-sidebar col-lg-4">
        <div class="sticky-top">
            @include('nova-blog::sidebar.search')
            @yield('nova-blog-sidebar')
        </div>
    </div>
@endsection
