<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test home page</title>
</head>
<body>

    <div>
        <p>Home page</p>
    </div>

    @foreach($users as $user)
        {{ $user->username }} --- {{ $user->email }} <br/>
    @endforeach

    <?php

        var_dump($u1, $u2);

    ?>

</body>
</html>
