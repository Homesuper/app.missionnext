<div class="user-list">
    <div class="row">
        <div class="col-md-7 col-md-offset-1">

            {{ Form::open(array(
            'action' => array('MissionNext\Controllers\Admin\UserController@searching'),
            'class' => 'form-inline user-search-form',
            'role' => 'form'
            )) }}
            {{ Form::text('search', null, array('class' => 'form-control', 'placeholder' => 'search by user or email')) }}
            <input type="submit" value=" Search " class="btn btn-sm btn-info ">
            {{ Form::close() }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="text-center">
                        User list Line 21
                    </h3>
                </div>
                <div class="col-md-2 pagination-info">
                    <span class="pull-right"> {{ $users->getTo() }} / {{ $users->getTotal() }} </span>
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

            <div id="firter-rezult">
            </div>

            <div id="default-rezult">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>E-mail</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Last login</th>
                        <th></th>
                    </tr>
                    </thead>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="user#/{{$user->id}}">{{ $user->username }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td><strong>{{ strtoupper($user->status) }}</strong></td>
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

                <div class="text-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h3 class="text-center">Filters:</h3>

            <div class="user-filters pull-right">
                <label for="apps-select-id">By applications:</label>
                <textarea id="apps-select-id"></textarea>
            </div>

            <div class="user-filters pull-right">
                <label for="role-select-id">By roles:</label>
                <textarea id="role-select-id"></textarea>
            </div>
        </div>
    </div>
</div>
<script>
    var pagination = 10;
    var count = 1;

    var initFilters = function(){

        $.post("{{ URL::route('getRoles') }}")
            .done(function(msg){
               console.log(msg);
                $('#apps-select-id').selectize({
                    plugins: ['remove_button'],
                    delimiter: ',',
                    maxItems: null,
                    valueField: 'id',
                    labelField: 'name',
                    searchField: 'name',
                    options: msg['apps'],
                    create: false
                });
                $('#role-select-id').selectize({
                    plugins: ['remove_button'],
                    delimiter: ',',
                    maxItems: null,
                    valueField: 'id',
                    labelField: 'role',
                    searchField: 'role',
                    options: msg['roles'],
                    create: false
                });
            })
            .error(function(msg){
                console.log(msg);
            });

        $('#apps-select-id').change(function() {
            count = 1;
            var selectAppValue = $(this).val();
            var selectRoleValue = $('#role-select-id').val();

            if (selectAppValue == '' && selectRoleValue == '' ) {
                location.reload();
            }
            $.post("{{ URL::route('userFilters') }}", {appId: selectAppValue, roleId: selectRoleValue, take: pagination } )
                .done(function(msg){
                    $('#default-rezult').remove();
                    $('.pagination-info').hide();
                    $('#firter-rezult').html(msg);

                    var totalCount = $('#first-result-table').data('usercount');
                    if(count * pagination >= totalCount) {
                        $('#show-more-filtered-data').remove();
                    }
                })
                .error(function(msg){
                    console.log(msg);
                });
        });

        $('#role-select-id').change(function() {
            count = 1;
            var selectAppValue = $('#apps-select-id').val();
            var selectRoleValue = $(this).val();

            if (selectAppValue == '' && selectRoleValue == '' ) {
                location.reload();
            }
            $.post("{{ URL::route('userFilters') }}", {appId: selectAppValue, roleId: selectRoleValue, take: pagination } )
                .done(function(msg){
                    $('#default-rezult').remove();
                    $('.pagination-info').hide();
                    $('#firter-rezult').html(msg);

                    var totalCount = $('#first-result-table').data('usercount');
                    if(count * pagination >= totalCount) {
                        $('#show-more-filtered-data').remove();
                    }
                })
                .error(function(msg){
                    console.log(msg);
                });
        });

        $('#firter-rezult').on('click', '#show-more-filtered-data', function(event) {
            var itemObj = $(event.target);
            var appId = $('#apps-select-id').val();
            var roleId = $('#role-select-id').val();
            var totalCount = $('#first-result-table').data('usercount');

            $.post("{{ URL::route('filteredUsersByEth') }}", {appId: appId, roleId: roleId, take: pagination, skip: count * pagination } )
                .done(function(msg){
                    count++;
                    $('#first-result-table').append(msg);
                    if(count * pagination >= totalCount) {
                        itemObj.remove();
                    }
                })
                .error(function(msg){
                    console.log(msg);
                });
        });
    };



    $(document).ready(function() {
         initFilters();
    });

    $('.container').on('click', '.pagination a', function(e){
        e.preventDefault();

        $.get($(this).attr('href')).done(function(data){

            var $userList = $(data).filter('.user-list');
            $('.col-md-9').html($userList.find('.col-md-9').html());
            initFilters();

        } );
    });

</script>