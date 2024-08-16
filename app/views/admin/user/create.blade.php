@extends('layout')

@section('title')
Dashboard. Creating new user
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-3 custom-form">
        <h3 class="text-center"> Creating new user </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::open(array(
        'action' => 'MissionNext\Controllers\Admin\UserController@create',
        'class' => 'custom-form',
        'role' => 'form'
        )) }}

        {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'username', 'autofocus' => 'true')) }}
        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'user e-mail')) }}
        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'user password')) }}

        <select class="form-control" name="role" id="role-select-id">
            @foreach($roles as $role)
                <option value="{{ $role['id'] }}">{{ $role['role'] }}</option>
            @endforeach
        </select>


        {{ Form::submit('Create ', array('class' => 'btn btn-sm btn-info')) }}

        {{ Form::close() }}
    </div>
</div>

@endsection

