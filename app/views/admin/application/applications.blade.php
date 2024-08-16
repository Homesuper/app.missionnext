@extends('layout')

@section('title')
    Dashboard. Applications
@endsection

@section('content')

<div class="row ">
    <div class="col-md-2 pull-right">
        <a class="btn btn-success btn-sm" href="{{ URL::route('applicationCreate')}}"><span class="glyphicon glyphicon-plus"> </span> Add App</a>
    </div>
</div>

<div class="row">
    <div class="col-md-11">
        <h3>
            Application list
        </h3>
    </div>
    <div class="col-md-1">
        <span class="pull-right"> {{ $applications->getTo() }} / {{ $applications->getTotal() }} </span>
    </div>
</div>
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

<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Application</th>
            <th>Public key</th>
            <th>Created at</th>
            <th></th>
        </tr>
    </thead>
    @foreach($applications as $application)
        <tr>
            <td>{{ $application->id }}</td>
            <td>{{ $application->name }}</td>
            <td>{{ $application->public_key }}</td>
            <td>{{ date("d.m.Y H:i", strtotime($application->created_at)) }}</td>
            <td class="text-center">
                <a href="{{ URL::route('applicationEdit', array('id' => $application->id)) }}" class="btn-warning btn btn-xs">
                    <span class="glyphicon glyphicon-edit"> </span> Edit </a>

                    {{ Form::open(array(
                        'action' => array('MissionNext\Controllers\Admin\ApplicationController@delete', $application->id),
                        'class' => 'pull-right',
                        'method' => 'delete',
                    )) }}

                    <input type="submit" class="btn btn-xs btn-danger" value=" Delete" onclick=' return confirm("confirm delete application {{ $application->name }}?")' >
                    {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>

<div class="text-center">
    {{ $applications->links() }}
</div>

@endsection
