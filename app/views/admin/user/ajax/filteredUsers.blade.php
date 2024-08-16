<table class="table table-hover" data-userCount="{{$count}}" id="first-result-table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>E-mail</th>
        <th>Created at</th>
        <th>Last login</th>
        <th></th>
    </tr>
    </thead>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ date("d.m.Y H:i", strtotime($user->created_at)) }}</td>
        <td>{{ date("d.m.Y H:i", strtotime($user->last_login)) }}</td>
        <td>
            <a href="{{ URL::route('userEdit', array('id' => $user->id)) }}" class="btn-warning btn btn-xs">
                <span class="glyphicon glyphicon-edit"> </span> Edit </a>

            {{ Form::open(array(
                'action' => array('MissionNext\Controllers\Admin\UserController@delete', $user->id),
                'class' => 'pull-right',
                'method' => 'delete',
            )) }}

            <input type="submit" class="btn btn-xs btn-danger" value=' Delete' onclick=' return confirm("confirm delete user {{ $user->username }} ?")' >
            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>
<div id="filter-more-rezult"></div>

<div class="text-center ajax-footer">
    <input type="button" class="btn btn-sm btn-success" id="show-more-filtered-data" value=" Show more " />
</div>