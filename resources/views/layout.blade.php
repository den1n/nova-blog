@extends('layouts.app')

@section('content')
    <div class="nova-blog container">
        <div class="row">
            <div class="blog-content col-lg-8">
                @yield('nova-blog-content')
            </div>
            <div class="blog-sidebar col-lg-4">
                <div class="sticky-top">
                    @include('nova-blog::sidebar.search')
                    @yield('nova-blog-sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
