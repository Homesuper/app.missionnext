@extends('layout')

@section('title')
Dashboard. Editing user
@endsection

@section('content')

<div class="row">
    <div class="col-md-4 col-md-offset-3 custom-form">

        <h3 class="text-center"> Editing user </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::model($user, array('route' => array('userEdit', $user->id))) }}

        {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'username', 'autofocus' => 'true')) }}
        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'user email')) }}
        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'user password')) }}

        <a href="{{ URL::route('users') }}" class="btn btn-sm btn-warning pull-left cancel_btm"> Cancel </a>
        <input type="submit" value="Edit" class="btn btn-sm btn-info pull-right">

        {{ Form::close() }}

    </div>
</div>
@endsection