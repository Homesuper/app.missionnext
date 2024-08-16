@extends('layout')

@section('title')
Dashboard. Subscription. Coupon
@endsection

@section('content')

<div class="row ">
    <div class="col-md-2 pull-right">
        <a class="btn btn-success btn-sm" href="{{ URL::route('sub.coupon.create')}}"><span class="glyphicon glyphicon-plus"> </span> Add Coupon</a>
    </div>
</div>
<div class="row">
    <div class="col-md-11">
        <h3>
            Coupon list
        </h3>
    </div>
    <div class="col-md-1">
        <span class="pull-right"> {{ $models->getTo() }} / {{ $models->getTotal() }} </span>
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
        <th>Code</th>
        <th>Value</th>
        <th>Is Active</th>

        <th></th>
    </tr>
    </thead>
    @foreach($models as $idx=>$model)
    <tr>
        <td>{{ $model->id }}</td>
        <td>{{ $model->code }}</td>
        <td>$ {{ $model->value }}</td>
        <td>@if ($model->is_active) <span class="label label-success">Active</span> @else <span class="label label-default">Used</span> @endif  </td>
        <td class="text-center">
            <a href="{{ URL::route('sub.coupon.edit', array(  $model->id) ) }}" class="btn-warning btn btn-xs">
                <span class="glyphicon glyphicon-edit"> </span> Edit </a>

            {{ Form::open(array(
            'route' => array('sub.coupon.delete', $model->id),
            'class' => 'pull-right',
            'method' => 'delete',
            )) }}

            <input type="submit" class="btn btn-xs btn-danger" value=" Delete" onclick=' return confirm("confirm delete coupon?")' >
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>

<div class="text-center">
    {{ $models->links() }}
</div>

@endsection
