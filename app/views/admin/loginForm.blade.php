@extends('layout')

@section('title')
    Login form
@endsection

@section('menu')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-4 col-md-4 custom-form login-form">
            <h3 class="text-center"> Please sign in </h3>
            @if (!$errors->isEmpty())
            <div class="alert alert-danger">
                @foreach ($errors->all('<p>:message</p>') as $error)
                    {{ $error }}
                @endforeach
            </div>
            @endif

            @if (Session::has('info'))
            <div class="alert alert-danger">
                {{ Session::get('info') }}
            </div>
            @endif

            {{ Form::open(array(
                'action' => 'MissionNext\Controllers\Admin\AdminBaseController@login',
                'class' => 'form-signin',
                'role' => 'form'
            )) }}

            {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'username', 'autofocus' => 'true')) }}
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'password')) }}

            {{ Form::submit('Sign in', array('class' => 'btn btn-sm btn-primary')) }}

            {{ Form::close() }}
        </div>
    </div>

@endsection

