@extends('layout')

@section('title')
Dashboard. Editing config
@endsection

@section('content')

<div class="row">
    <div class="col-md-6 col-md-offset-3 custom-form">

        <h3 class="text-center"> Editing config </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::model($model, array('route' => array('sub.config.edit', $model->id))) }}

        <div class="form-group">
            {{ Form::label('role', 'Role', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::select('role', $roles, null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('app_id', 'Application', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::select('app_id', $applications, null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('partnership', 'Partership', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::select('partnership', $partnerships, null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('cost', 'Cost', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('cost', null, array('class' => 'form-control', 'placeholder' => 'cost', 'autofocus' => 'true')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('period', 'Period', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('period', null, array('class' => 'form-control', 'placeholder' => 'period', 'autofocus' => 'true')) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        {{ Form::hidden('is_recurrent', 0); }}
                        {{ Form::checkbox('is_recurrent', 1) }} Is recurrent?
                    </label>
                </div>
            </div>
        </div>

        <a href="{{ URL::route('sub.config.list') }}" class="btn btn-sm btn-warning pull-left cancel_btm"> Cancel </a>
        <input type="submit" value="Edit" class="btn btn-sm btn-info pull-right">

        {{ Form::close() }}

    </div>
</div>
@endsection

