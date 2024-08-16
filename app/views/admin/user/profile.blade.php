@extends('layout')

@section('title')
    Dashboard. User Profile
@endsection

@section('content')
    <button onclick="window.history.back();" type="button" class="btn btn-primary btn-sm">Back</button>
    <h3>Profile Data:</h3>
    <table class="table table-hover profile-table">
        <tbody>
        <thead>
        <tr>
            <th>Username</th>
            <td>{{ $user['username']  }}</td>
        </tr>
        <tr>
            <th>Registered</th>
            <td>{{ $user['created_at']  }}</td>
        </tr>
        </thead>
        <!-- str_replace commands added by Nelson Dec-16-2016 -->
        @foreach($sortedKeys as $item)

            @if ((is_string($user['profileData'][$item]) && !empty($user['profileData'][$item])) || (is_array($user['profileData'][$item]) && count($user['profileData'][$item]) > 0))
                <tr>
                    <th>{{ $fieldLabels[$item] }} </th>
                    <td>@if (is_string($user['profileData'][$item]))
                            @if (0 === strpos($user['profileData'][$item], $uploadFieldPrefix))
                                <a href="/profile/file/{{ $user['profileData'][$item] }}" target="_blank">
                                    {{ $user['profileData'][$item] }}
                                </a>
                            @else
                                {{ $item_val = str_replace("(!)","",$user['profileData'][$item]); $item_val }} 
                            @endif
                        @elseif (count($user['profileData'][$item]) > 1)
                            @foreach ($user['profileData'][$item] as $val)
                                <li>{{ $val = str_replace("(!)","",$val); $val }}</li>
                            @endforeach
                        @else
                          {{ $item_val = str_replace("(!)","",current($user['profileData'][$item])); $item_val }} 
                        @endif
                    </td>
                 </tr>
                @endif
                @endforeach

                </tbody>
    </table>
@endsection
