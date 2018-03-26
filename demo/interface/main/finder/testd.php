<?php
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Sanitize escapes and stop fake register globals.
//
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="generator" content="vBulletin 3.7.3" />

<meta name="keywords" content=" Split screen into 3 div containers, javascript, php, cgi, xml, css, asp, mysql help, 

database, webmaster, dhtml" />
<meta name="description" content=" Split screen into 3 div containers JavaScript programming" />
<title>Dashboard</title>
<link rel="shortcut icon" href="favicon.ico" />
<!-- jquery splitter -->
<link rel="stylesheet" href="../../../library/slicklab/css/style.css">
    <link href="../../../library/slicklab/css/style-responsive.css" rel="stylesheet">
    <!--easy pie chart-->
    <link href="../../../library/slicklab/js/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen" />

    <!--vector maps -->
    <link rel="stylesheet" href="../../../library/slicklab/js/vector-map/jquery-jvectormap-1.1.1.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
    .state-overview .symbol i {
    font-size: 35px;
    position: absolute;
    margin-top: -12px;
    margin-left: -8px;
}
circle{
    fill: #000;
    stroke: #848484;
}
.mega-bg {
    background-image: url("http://zada.ba/wp-content/themes/zada/images/doctor.png");
    background-repeat: no-repeat;
    background-position: bottom;
    position: absolute;
    right: 0;
    bottom: -5px;
    width: 300px;
    height: 162px;
    background-size:42%;
}</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
</head>
<body>
					<?php
$query = sqlQuery("SELECT a.no_of_admitted_patient, b.no_of_op_patient,c.no_of_op_patient_today,
d.no_of_ip_patient_today,e.no_of_visits_today,f.total_registrations
FROM
(SELECT count(id)no_of_admitted_patient FROM form_encounter where pc_catid=12)a,
(SELECT count(id)no_of_op_patient FROM form_encounter where pc_catid!=12)b,
(SELECT count(id)no_of_op_patient_today 
FROM form_encounter where pc_catid!=12 and date(date)=current_date())c,
(SELECT count(id)no_of_ip_patient_today 
FROM form_encounter where pc_catid=12 and date(date)=current_date())d,
(SELECT count(id)no_of_visits_today FROM form_encounter where date(date)=current_Date())e,
(SELECT count(id)total_registrations from patient_Data)f");
?>
<div class="body-content">
    <section>

            <!-- page head start-->
            <div class="page-head">
                <h3>
                    Dashboard
                </h3>
                <span class="sub-title">Welcome Administrator!</span>
                <div class="state-information">
                    <div class="state-graph">
                        <div id="balance" class="chart"></div>
                        <div class="info">Patients Registered | <?php echo $query['total_registrations']; ?></div>
                    </div>
                    <div class="state-graph">
                        <div id="item-sold" class="chart"></div>
                        <div class="info">Patients Admitted | <?php echo $query['no_of_admitted_patient']; ?></div>
                    </div>
                </div>
            </div>
            <!-- page head end-->

            <!--body wrapper start-->
            <div class="wrapper">
                <!--state overview start-->
                <div class="row state-overview">
                    <div class="col-xs-3">
                        <section class="panel yellow">
                            <div class="symbol">
                                <i class="fa fa-user
                                "></i>
                            </div>
                            <div class="value white">
                                <h1 class="timer" data-from="0" data-to="320"
                                    data-speed="1000">
                                    <?php echo $query['no_of_visits_today']; ?>
                                </h1>
                                <p>Todays Visits</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-3">
                        <section class="panel ">
                            <div class="symbol purple-color">
                                <i class="fa fa-wheelchair"></i>
                            </div>
                            <div class="value gray">
                                <h1 class="purple-color timer" data-from="0" data-to="123"
                                    data-speed="1000">
                                    <?php echo $query['no_of_op_patient']; ?>
                                </h1>
                                <p>OPD Patient</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-3">
                        <section class="panel green">
                            <div class="symbol ">
                                <i class="fa fa-calendar "></i>
                            </div>
                            <div class="value white">
                                <h1 class="timer" data-from="0" data-to="432"
                                    data-speed="1000">
                                    <?php echo $query['no_of_op_patient_today']; ?>
                                </h1>
                                <p>Today OPs</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-3">
                        <section class="panel red">
                            <div class="symbol ">
                                <i class="fa fa-bed" aria-hidden="true"></i>

                            </div>
                            <div class="value white">
                                <h1 class="timer" data-from="0" data-to="432"
                                    data-speed="1000">
                                    <?php echo $query['no_of_ip_patient_today']; ?>
                                </h1>
                                <p>Todays IPs</p>
                            </div>
                        </section>
                    </div>
                </div>
                <!--state overview end-->

                <div class="row">
<div class="col-md-8" style="max-height: 632px;overflow:hidden">
                    <section class="panel post-wrap pro-box team-member">
                        <aside class="bg-primary v-align">
                            <div class="panel-body text-center">
                                <div class="team-member-wrap" id="list">
                                    <div class="team-member-info">
                                        <!--<div class="action-set">
                                            <a href="javascript:;" class="tooltips" data-original-title="Profile Info" data-toggle="tooltip" data-placement="top">
                                                <i class="fa fa-reorder"></i>
                                            </a>
                                        </div> -->
                                        <div class="team-title">
                                            <a href="javascript:;" class="m-name">
                                                Alison Jones
                                            </a>
                                            <span class="sub-title">Sr Doctor</span>
                                        </div>

                                        <div class="call-info">
                                            <a href="inbox-compose.html">
                                                <i class="fa fa-envelope-o"></i>
                                            </a>
                                            <img src="../../../library/slicklab/img/img2.jpg" alt="" />
                                            <a href="inbox-compose.html">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        </div>
										<div class="call-info">
										<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                                        </div><div class="status">
                                            <h5>specialized</h5>
                                            <span>Bypass Surgery</span>

                                    </div><br>
									<div class="status">
                                            <h5>Room No</h5>
                                            <span>5</span>

                                    </div><br>
									<div class="status">
                                            <h5>Patients Seen</h5>
                                            <span>15</span>

                                    </div><br>
									<div class="status">
                                            <h5>Paitients waiting</h5>
                                            <span>5</span>

                                    </div>
                                </div>
                            </div>
                            </div>
                        </aside>
						<div style="height: 630px;overflow-y: scroll;">
                        <aside style="width: 800px;">
                            <header class="panel-heading head-border">
                                Doctors
                                <span class="action-tools pull-right">
                                    <a class="fa fa-reorder" href="javascript:;"></a>
                                </span>
                            </header>
							 <?php  $newcrop_user_role=sqlStatement("select * from users where newcrop_user_role='erxdoctor'"); ?>
                            <div class="post-info">
							                                <div class="add-more-member">
                                    <a href="javascript:;" class=" ">Search Doctors</a>
                                    <a href="javascript:;" class="add-btn pull-right">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </div>
                                <ul class="team-list cycle-pager external" id='no-template-pager'>
								<?php   while($newcrop_user_roles = sqlFetchArray($newcrop_user_role)){ 
								if($newcrop_user_roles['status'] == 1) {
								$status = 'online';
								} elseif ($newcrop_user_roles['status'] == 2) {
									$status = 'busy';
								} else {
									$status = 'offline';
								}
																   $today = date('Y-m-d',strtotime("+0 days"));

$query_seen = "select a.*, b.*,round(((b.pending_patient/a.total_no_of_patients )*100),0)pending_percent,round((((a.total_no_of_patients-b.pending_patient)/a.total_no_of_patients)*100),0)seen_percent, (a.total_no_of_patients-b.pending_patient)no_of_examined_patients
from
(SELECT count(b.id) total_no_of_patients 
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$newcrop_user_roles['id']."'  and date(b.date)=date('".$today."') )a,

(SELECT count(b.id)pending_patient
FROM patient_data a,form_encounter b 
where a.pid=b.pid and b.provider_id='".$newcrop_user_roles['id']."'  and date(b.date)=date('".$today."') 
and out_to is null and out_time is  null)b";
$res_seen = sqlStatement($query_seen);
$res_seen1 = sqlFetchArray($res_seen);
								?>
                                    <li >
                                        <a href="javascript:void(0);" onClick="postData(<?php echo $newcrop_user_roles['id']; ?>);" >
                                            <span class="thumb-small">
														<?php if($newcrop_user_roles['user_image']) { ?>
              <img src="../<?php echo $newcrop_user_roles['user_image']; ?>" class="circle" alt="User Image">
			<?php } elseif($newcrop_user_roles['newcrop_user_role'] == 'erxdoctor') { ?>
			<img src="../../../library/dist/img/doctor.png" class="img-circle" alt="User Image">
			<?php }?>
                                                <i class="<?php echo $status; ?> dot"></i>
                                            </span>
                                            <span class="name"><?php echo ucfirst(strtolower($newcrop_user_roles['fname'])).' '. ucfirst(strtolower($newcrop_user_roles['lname'])); ?></span><span class="pull-right"><?php  echo $res_seen1['no_of_examined_patients']; ?> <i class="fa fa-arrow-up green" style="color:#00a65a"></i></span><span class="pull-right" style="clear: right;
margin: 10px;
"><?php  echo $res_seen1['pending_patient']; ?> <i class="fa fa-arrow-down red" style="color:  #dd4b39"></i></span>
                                        </a>
                                    </li>
								<?php } ?>
                                <!--    <li>
                                        <a href="javascript:;">
                                            <span class="thumb-small">
                                                <img class="circle" src="../../../library/slicklab/img/img1.jpg" alt=""/>
                                                <i class="away dot"></i>
                                            </span>
                                            <span class="name">Joliana Devis</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="thumb-small">
                                                <img class="circle" src="../../../library/slicklab/img/img3.jpg" alt=""/>
                                                <i class="busy dot"></i>
                                            </span>
                                            <span class="name">David Alexzender</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="thumb-small">
                                                <img class="circle" src="../../../library/slicklab/img/img4.jpg" alt=""/>
                                                <i class="offline dot"></i>
                                            </span>
                                            <span class="name">Emma Rose</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="thumb-small">
                                                <img class="circle" src="../../../library/slicklab/img/img1.jpg" alt=""/>
                                                <i class="online dot"></i>
                                            </span>
                                            <span class="name">Jacqueline Jones</span>
                                        </a>
                                    </li> -->

                                </ul>

                            </div>
                        </aside>
						</div>
                    </section>
                </div>
		<?php

		$noti_qry = "select a.model_id,b.message,b.type, b.icon_class,c.user_id,is_read,c.created_at
from notifications a, notification_types b, user_notification c
where a.type_id=b.id and a.id=c.notification_id and c.user_id=".$_SESSION['authUserID']." ORDER BY c.created_at desc";
			
				$notification_arr = sqlStatement($noti_qry);
					 
					 ?>
                <div class="col-md-4">
                    <section class="panel">
                        <header class="panel-heading head-border">
                            Notifications (<?php echo sqlNumRows($notification_arr); ?>)
                            <span class="tools pull-right">
                                <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="noti-information notification-menu">
                            <!--notification info start-->
                            <div class="notification-list mail-list not-list">
							<?php
							
								if(sqlNumRows($notification_arr)) {
									
								while($notification = sqlFetchArray($notification_arr)) { 
								
							if($notification['type'] == 'registration') {
									if($notification['is_read'] == 0) { 
									$bk_color = "background-color: #edf2fa;";
									}
else {
$bk_color = "";
}	?>
                                <a href="<?php echo "../../patient_file/summary/demographics.php?set_pid=".$notification['model_id'] ?>" class="single-user" style="<?php echo $bk_color; ?>">
                                        <span class="icon bg-primary">
                                            <i style="padding: 10px;" class="<?php echo $notification['icon_class']; ?>"></i>
                                        </span>
                                    <span class="purple-color"><?php echo $notification['message'] ?> </span>
                                    <p>
                                        <small><?php $datetime1 = new DateTime(); $datetime2 = new DateTime($notification['created_at']);
$interval = $datetime1->diff($datetime2);
echo $interval->format('%D days %H hours %I minutes ago'); ?></small>
                                    </p>
                                        <span class="read tooltips" data-original-title="Mark as Unread" data-toggle="tooltip" data-placement="left">
                                            <i class="fa fa-circle-o"></i>
                                        </span>
                                </a>
							
								<?php  } } } else { ?>
                               <p>You have no new notifications!</p>
								<?php } ?>
                              	  <a href="<?php echo '../notifications-list.php' ?>" class="single-mail text-center">
                                    View All Notification
                                </a>

                            </div>
                            <!--notification info end-->
                        </div>
                    </section>
                </div>
                


                </div>

                <div class="row">
                    <div class="col-md-8">
                        <section class="panel" id="block-panel">
                            <header class="panel-heading head-border">
                                mobile visit
                                <span class="tools pull-right">
                                    <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close fa fa-times" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <ul class="mobile-visit">
                                    <li class="page-view-label">
                                        <span class="page-view-value"> 5,2105</span>
                                        <span>Unique visitors</span>
                                    </li>
                                    <li>
                                        <div class="easy-pie-chart">
                                            <div class="iphone-visitor" data-percent="45"><span>45</span>%</div>
                                        </div>
                                        <div class="visit-title">
                                            <i class="fa fa-apple green-color"></i>
                                            <span>iPhone</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="easy-pie-chart">
                                            <div class="android-visitor" data-percent="40"><span>40</span>%</div>
                                        </div>
                                        <div class="visit-title">
                                            <i class="fa fa-android purple-color"></i>
                                            <span>Android</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-4">
                        <section class="panel">
                            <div class="panel-body- weather-widget">
                                <div class="weather-state">
                                    <span class="weather-icon">
                                        <i class="slicon-weather_downpour_fullmoon"></i>
                                    </span>

                                    <span class="weather-type">Storm</span>
                                </div>
                                <div class="weather-info">
                                    <span class="degree">13</span>
                                    <span class="weather-city">Bangalore</span>
                                    <div class="switch-btn">
                                        <input type="checkbox" class="js-switch-small-green " checked>
                                    </div>
                                    <div class="weather-chart m-t-40" data-type="line" data-resize="true" data-height="65" data-width="100%" data-line-width="1.5" data-line-color="#0bc2af" data-spot-color="#0bc2af" data-fill-color=""  data-highlight-line-color="#0bc2af" data-spot-radius="0" data-data="[1,5,3,6,4,7,9]"></div>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="w-map-size" id="world-map"> </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="sale-monitor">
                                            <div class="title">
                                                <h3>Sales Monitor</h3>
                                                <p>Proper sell monitoring through the world map to plan for the next marketing attempt</p>
                                            </div>
                                            <div class="states">
                                                <div class="info">
                                                    <div class="desc pull-left">Australia</div>
                                                    <div class="percent pull-right">70%</div>
                                                </div>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                                        <span class="sr-only">70% </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="states">
                                                <div class="info">
                                                    <div class="desc pull-left">Europe</div>
                                                    <div class="percent pull-right">45%</div>
                                                </div>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                        <span class="sr-only">45% </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="states">
                                                <div class="info">
                                                    <div class="desc pull-left">Latin America</div>
                                                    <div class="percent pull-right">35%</div>
                                                </div>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 35%">
                                                        <span class="sr-only">35% </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <section class="panel">
                            <div class="panel-body cpu-graph">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="c-info">
                                            <h3>Power Consumption</h3>
                                            <p>Once this tab is open click the CPU button above the list of programs twice</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="easy-pie-chart">
                                            <div class="percentage-light" data-percent="33"><span>33%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6">
                        <section class="panel">
                            <header class="panel-heading">
                                To Do List
                                <span class="tools pull-right">
                                    <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close fa fa-times" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <ul class="todo-list-item" id="todo-list">
                                    <li class="clearfix">
                                        <div class="chk-todo pull-left">
                                            <input type="checkbox" value="0" />
                                        </div>
                                        <p class="todo-title">
                                            Dr Monica has a operation by 2:30pm
                                        </p>
                                        <div class="action-todo pull-right clearfix">
                                            <a href="#" class="todo-edit"><i class="icon-pencil"></i></a>
                                            <a href="#" class="todo-remove"><i class="icon-close"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">

                                        <div class="chk-todo pull-left">
                                            <input type="checkbox" value="0" />

                                        </div>
                                        <p class="todo-title">
                                            Medical Kit have to buy
                                        </p>
                                        <div class="action-todo pull-right clearfix">
                                            <a href="#" class="todo-edit"><i class="icon-pencil"></i></a>
                                            <a href="#" class="todo-remove"><i class="icon-close"></i></a>
                                        </div>
                                    </li>
                                    <li class="clearfix">

                                        <div class="chk-todo pull-left">
                                            <input type="checkbox" value="0" />

                                        </div>
                                        <p class="todo-title">
                                            Aenean eu leo quam. Pellentesque sumon sem venenatis.
                                        </p>
                                        <div class="action-todo pull-right clearfix">
                                            <a href="#" class="todo-edit"><i class="icon-pencil"></i></a>
                                            <a href="#" class="todo-remove"><i class="icon-close"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>


            </div>
            <!--body wrapper end-->
			     <!-- body content end-->
    </section>
</div>
<script>
// When the page finishes loading

    // When the user clicks any <li>, activate script
    function postData(thisBtn) {
        // Assign the value of the data attribute
        //var thisBtn =   $(this).data('id');

        // If an <li> doesn't have this attribute, stop
        if(thisBtn == undefined)
            return false;
        // Start the ajax
        $.ajax({
                // Where to send request
                url: 'ajax.index.php',
                // What to send
                data: { did: thisBtn },
                // How to send
                type: 'post',
                // What to do when request succeeds
                success: function(response) {
                    // Save the contents of the response into
                    // whatever has the id="list"
                    $("#list").html(response);
                }
        });
    }

</script>
<!-- jQuery 2.2.3 -->
<script src="../../../library/dist/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
$(window).load(function(){ 
    // Perform something here...
	        $.ajax({
                // Where to send request
                url: 'ajax.index.php',
                // What to send
                data: { did: 4 },
                // How to send
                type: 'post',
                // What to do when request succeeds
                success: function(response) {
                    // Save the contents of the response into
                    // whatever has the id="list"
                    $("#list").html(response);
                }
        });
});
</script>
<!-- Bootstrap 3.3.6 -->
<script src="../../../library/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="../../../library/slicklab/js/scripts.js"></script>

<!--jquery-ui-->
<script src="../../../library/slicklab/js/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

<script src="../../../library/slicklab/js/jquery-migrate.js"></script>
<script src="../../../library/slicklab/js/bootstrap.min.js"></script>
<script src="../../../library/slicklab/js/modernizr.min.js"></script>

<!--Nice Scroll-->
<script src="../../../library/slicklab/js/jquery.nicescroll.js" type="text/javascript"></script>

<!--right slidebar-->
<script src="../../../library/slicklab/js/slidebars.min.js"></script>

<!--switchery-->
<script src="../../../library/slicklab/js/switchery/switchery.min.js"></script>
<script src="../../../library/slicklab/js/switchery/switchery-init.js"></script>

<!--flot chart -->
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.js"></script>
<script src="../../../library/slicklab/js/flot-chart/flot-spline.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.resize.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.pie.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.selection.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.stack.js"></script>
<script src="../../../library/slicklab/js/flot-chart/jquery.flot.crosshair.js"></script>


<!--earning chart init-->
<script src="../../../library/slicklab/js/earning-chart-init.js"></script>


<!--Sparkline Chart-->
<script src="../../../library/slicklab/js/sparkline/jquery.sparkline.js"></script>
<script src="../../../library/slicklab/js/sparkline/sparkline-init.js"></script>

<!--easy pie chart-->
<script src="../../../library/slicklab/js/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="../../../library/slicklab/js/easy-pie-chart.js"></script>


<!--vectormap-->
<script src="../../../library/slicklab/js/vector-map/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../../library/slicklab/js/vector-map/jquery-jvectormap-world-mill-en.js"></script>
<script src="../../../library/slicklab/js/dashboard-vmap-init.js"></script>

<!--Icheck-->
<script src="../../../library/slicklab/js/icheck/skins/icheck.min.js"></script>
<script src="../../../library/slicklab/js/todo-init.js"></script>

<!--jquery countTo-->
<script src="../../../library/slicklab/js/jquery-countTo/jquery.countTo.js"  type="text/javascript"></script>

<!--owl carousel-->
<script src="../../../library/slicklab/js/owl.carousel.js"></script>


<script type="text/javascript">

    $(document).ready(function() {

        //countTo

        $('.timer').countTo();

        //owl carousel

        $("#news-feed").owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            autoPlay:true
        });
    });

    $(window).on("resize",function(){
        var owl = $("#news-feed").data("owlCarousel");
        owl.reinit();
    });

</script>

</body>
</html>
