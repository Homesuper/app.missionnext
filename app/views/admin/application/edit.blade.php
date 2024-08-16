@extends('layout')

@section('title')
Dashboard. Editing application
@endsection

@section('content')

<div class="row">
    <div class="col-md-4 col-md-offset-3 custom-form">

        <h3 class="text-center"> Editing application </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::model($application, array('route' => array('applicationEdit', $application->id))) }}

        {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'application name', 'autofocus' => 'true')) }}
        {{ Form::text('public_key', null, array('class' => 'form-control', 'placeholder' => 'put your public key')) }}
        {{ Form::password('private_key', array('class' => 'form-control', 'placeholder' => 'put your private key')) }}

        <a href="{{ URL::route('applications') }}" class="btn btn-sm btn-warning pull-left cancel_btm"> Cancel </a>
        <input type="submit" value="Save" class="btn btn-sm btn-info pull-right">

        {{ Form::close() }}

    </div>
</div>
@endsection

