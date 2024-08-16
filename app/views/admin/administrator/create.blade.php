@extends('layout')

@section('title')
Dashboard. Creating new administrator
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3 custom-form">
        <h3 class="text-center"> Creating new admin </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::open(array(
        'route' => 'administrator.new',
        'class' => 'form-horizontal',
        'role' => 'form'
        )) }}

        <div class="form-group">
            {{ Form::label('username', 'Username', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username', 'autofocus' => 'true')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email', 'autofocus' =>
                'true')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password', 'autofocus' =>
                'true')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password_confirmation', 'Password confirm', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Password confirm', 'autofocus' =>
                'true')) }}
            </div>
        </div>


        {{ Form::submit('Save ', array('class' => 'btn btn-sm btn-info')) }}

        {{ Form::close() }}
    </div>
</div>

@endsection

