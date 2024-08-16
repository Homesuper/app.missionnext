@extends('layout')

@section('title')
Dashboard. Users
@endsection

@section('content')
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

<div class="row ">
    <div class="col-md-2 pull-right">
        <a class="btn btn-success btn-sm" href="{{ URL::route('administrator.create') }}"><span class="glyphicon glyphicon-plus"> </span> Add Admin</a>
    </div>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Username</th>
        <th></th>
    </tr>
    </thead>
    @foreach($models as $idx=>$model)
    <tr>
        <td>{{ $model->id }}</td>
        <td>{{ $model->email }}</td>
        <td>{{ $model->username }}</td>
        <td class="text-center">
            <a href="{{ URL::route('administrator.edit', array(  $model->id) ) }}" class="btn-warning btn btn-xs">
                <span class="glyphicon glyphicon-edit"> </span> Edit </a>
            {{ Form::open(array(
            'route' => array('administrator.delete', $model->id),
            'class' => 'pull-right',
            'method' => 'delete',
            )) }}
            @if (Sentry::getUser()->id !== $model->id)


                <input type="submit" class="btn btn-xs btn-danger" value=" Delete" onclick=' return confirm("confirm delete admin?")' >
            @else
                <input disabled="disabled" type="submit" class="btn btn-xs btn-danger" value=" Delete" onclick=' return confirm("confirm delete admin?")' >
            @endif
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>



@endsection
@endsection

