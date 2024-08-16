@extends('layout')

@section('title')
Dashboard. Languages
@endsection

@section('content')
<div class="row ">
    <div class="col-md-2 pull-right">
        <a class="btn btn-success btn-sm" href="{{ URL::route('languageCreate')}}"><span class="glyphicon glyphicon-plus"> </span> Add Language</a>
    </div>
</div>
<div class="row">
    <div class="col-md-11">
        <h3>
            Languages list
        </h3>
    </div>
    <div class="col-md-1">
        <span class="pull-right"> {{ $langs->getTo() }} / {{ $langs->getTotal() }} </span>
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
        <th>Language key</th>
        <th>Language</th>
        <th></th>
    </tr>
    </thead>
    @foreach($langs as $lang)
    <tr>
        <td>{{ $lang->id }}</td>
        <td>{{ $lang->key }}</td>
        <td>{{ $lang->name }}</td>
        <td class="text-center">
            <a href="{{ URL::route('languageEdit', array('id' => $lang->id)) }}" class="btn-warning btn btn-xs">
                <span class="glyphicon glyphicon-edit"> </span> Edit </a>

            {{ Form::open(array(
                'action' => array('MissionNext\Controllers\Admin\LanguageController@delete', $lang->id),
                'class' => 'pull-right',
                'method' => 'delete',
            )) }}

            <input type="submit" class="btn btn-xs btn-danger" value=" Delete" onclick=' return confirm("confirm delete {{ $lang->name }} language?")' >
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>

<div class="text-center">
    {{ $langs->links() }}
</div>

@endsection
