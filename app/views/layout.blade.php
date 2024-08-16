<!DOCTYPE html>
<html lang="en" ng-app="mission-next">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    @section('styles')
        {{-- HTML::style('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css') --}}
        {{-- HTML::style('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css') --}}
        {{ HTML::style(URL::asset('packages/admin/css/bootstrap.css')) }}
        {{ HTML::style(URL::asset('packages/admin/css/bootstrap-theme.css')) }}
        {{ HTML::style(URL::asset('packages/admin/css/selectize-bootstrap3.css')) }}
        {{-- HTML::style(URL::asset('packages/admin/css/selectize.css')) --}}
        {{ HTML::style(URL::asset('packages/admin/css/base.css')) }}
        {{ HTML::style(URL::asset('packages/admin/css/jquery-ui.css')) }}
    @show
</head>

<body>

<div class="container">

    @section('menu')
        @include('admin.menu.menu')
        @if (Breadcrumbs::exists())
            {{ Breadcrumbs::render() }}
        @endif
    @show

    @yield('content')
    @yield('footer')
    <span class="token">
        {{ Form::token() }}
    </span>
</div>

@section('javascripts')
    {{ HTML::script(URL::asset('packages/admin/js/jquery-1.11.1.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/jquery-ui.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/selectize.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/bootstrap.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/angular.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/sanitize.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/dirPagination.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/angular-resource.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/angular-route.min.js')) }}
    {{ HTML::script(URL::asset('packages/admin/js/date.js')) }}
    {{ HTML::script(URL::asset('project/js/app.js')) }}
    <script>
        (function($){
            $.ajaxSetup({
                data:{
                    '_token' : $('.token input').val()
                }
            });
        })(jQuery)
    </script>
@show
<body>
</html>
