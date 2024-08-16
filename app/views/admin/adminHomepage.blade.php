@extends('layout')

@section('title')
    Dashboard home page
@endsection

@section('content')

<?php
$current_time = date("M j, Y H:i:s");  // Format: Monday Nov 11, 2011 11:11AM
?>
<div class="row">
    <!--<div class="col-md-offset-4 col-md-4 login-form">-->
    <div class="col-md-offset-4 col-md-4">
        <h3> Administrative Home</h3>
			<p>Welcome&nbsp;to&nbsp;the&nbsp;MissionNext&nbsp;Application&nbsp;Programming&nbsp;Interface&nbsp;(API)</p>
            <p>The MissionNext Server Time is <?php echo "$current_time"; ?></p>
            <p>Current user is <font color="#000000">{{ Sentry::getUser()->username }}</font> </p><br/>
			<p align="center"><a href="dashboard/subscription/coupon">Manage Coupons</a> </p>
			<p><font color="#FFFFFF">/api/app/views/admin/adminHomepage.blade.php</font> </p><br/>
			
			
    </div>
</div>

@endsection

