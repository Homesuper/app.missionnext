@extends('layout')

@section('title')
Dashboard. Editing language
@endsection

@section('content')

<div class="row">
    <div class="col-md-4 col-md-offset-3 custom-form">

        <h3 class="text-center"> Editing language </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::model($language, array('route' => array('languageEdit', $language->id))) }}
        {{ Form::select('key', LanguageInfo::$codes, null, array('class' => 'form-control')) }}


        <a href="{{ URL::route('languages') }}" class="btn btn-sm btn-warning pull-left cancel_btm"> Cancel </a>
        <input type="submit" value="Save" class="btn btn-sm btn-info pull-right">

        {{ Form::close() }}

    </div>
</div>
@endsection

