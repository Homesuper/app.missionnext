<!-- Static navbar -->
<div class="navbar navbar-default" style="margin-bottom: 50px;" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                {{ link_to_route('adminHomepage', 'Mission Next dashboard', null, array('class' => 'navbar-brand')) }}
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ URL::route('applications')}}" > <span class="glyphicon glyphicon-globe"> </span> Applications</a>


                </li>
                <li>
                    <a href="{{ URL::route('languages')}}" > <span class="glyphicon glyphicon-flag"> </span> Languages</a>

                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-asterisk"> </span> Subscriptions <b class="caret"></b></a>
                    <ul class="dropdown-menu">

                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="javascript:void(0);">Config</a>
                            <ul class="dropdown-menu">
                                @foreach ($applications as $id => $app)
                                <li>
                                    <a href="{{ URL::route('sub.config.management' ) }}?app={{ $id }} ">
                                        <span class="glyphicon glyphicon-globe"> </span> {{ $app }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a tabindex="-1" href="{{ URL::route('sub.coupon.list')}}">Coupon</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ URL::route('users')}}#/"> <span class="glyphicon glyphicon-user"> </span> Users </a>
                </li>
                <li>
                    <a href="{{ URL::route('administrator.list')}}"> <span class="glyphicon glyphicon-user"> </span> Administrators </a>
                </li>
<!--                <li><a href="#">Link</a></li>-->
<!--                <li class="dropdown">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown 2<b class="caret"></b></a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        <li><a href="#">Action 1</a></li>-->
<!--                        <li><a href="#">Action 2</a></li>-->
<!--                        <li><a href="#">Action 3</a></li>-->
<!--                        <li class="divider"></li>-->
<!--                        <li class="dropdown-header">Header into dropdown</li>-->
<!--                        <li><a href="#">Link 1</a></li>-->
<!--                        <li><a href="#">Link 2</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <div class="navbar-form">
                        <a href="{{ URL::route('logout')}}" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-log-out"> </span> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</div>