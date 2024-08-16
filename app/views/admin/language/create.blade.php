@extends('layout')

@section('title')
Dashboard. Creating new language
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-3 custom-form">
        <h3 class="text-center"> Creating new language </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
                {{ $error }}
            @endforeach
        </div>
        @endif
        {{ Form::open(array(
            'action' => 'MissionNext\Controllers\Admin\LanguageController@create',
            'class' => 'custom-form',
            'role' => 'form'
        )) }}

        {{ Form::select('key', LanguageInfo::$codes, null, array('class' => 'form-control')) }}
        {{ Form::submit('Create ', array('class' => 'btn btn-sm btn-info')) }}

        {{ Form::close() }}

    </div>
</div>

@endsection

