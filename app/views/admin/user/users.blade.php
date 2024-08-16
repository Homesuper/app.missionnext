@extends('layout')

@section('title')
    Dashboard. Users
@endsection

@section('content')
@if (Session::has('info'))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('info') }}
    </div>
@endif
@if (Session::has('warning'))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('warning') }}
    </div>
@endif
<div ng-view></div>
@endsection

@section('javascripts')
@parent
{{ HTML::script(URL::asset('project/js/controllers/user.js')) }}


@endsection
