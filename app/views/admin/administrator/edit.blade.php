@extends('layout')

@section('title')
Dashboard. Editing coupon
@endsection

@section('content')

<div class="row">
    <div class="col-md-6 col-md-offset-3 custom-form">

        <h3 class="text-center"> Editing Administrator </h3>
        @if (!$errors->isEmpty())
        <div class="alert alert-danger">
            @foreach ($errors->all('<p>:message</p>') as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif

        {{ Form::model($model, array('route' => array('administrator.update', $model->id), 'class' => 'form-horizontal')) }}

        <div class="form-group">
            {{ Form::label('username', 'Username', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username', 'autofocus' =>
                'true')) }}
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
            {{ Form::label('new_password', 'New Password', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::password('new_password', array('class' => 'form-control', 'placeholder' => 'New Password', 'autofocus' =>
                'true')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('new_password_confirmation', 'Pepeat Password', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::password('new_password_confirmation', array('class' => 'form-control', 'placeholder' => 'Pepeat New Password', 'autofocus' =>
                'true')) }}
            </div>
        </div>
        <div class="btn-toolbar" role="toolbar">
            <a href="{{ URL::route('administrator.list') }}" class="btn btn-sm btn-warning pull-left"> Cancel </a>
            {{ Form::button('Update', array('class' => 'btn btn-sm btn-info pull-left', 'type' => 'submit' )) }}
        </div>
        {{ Form::close() }}

    </div>
</div>
@endsection

@section('javascripts')
@parent
{{ HTML::script(URL::asset('js/md5.js')) }}
{{ HTML::script(URL::asset('js/generateCoupon.js')) }}
@stop

