<?php
/**
 * main_title.php - The main titlebar, at the top of the 'concurrent' layout.
 */

include_once('../globals.php');
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
        <title>MedSmart</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../library/dist/css/AdminLTE.css">
<link rel="stylesheet" href="../../library/css/mycss.css">
        <link href="../../library/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
<style>
.task {
    opacity: 0.5;
}
.task a {
    text-decoration: line-through;
}
.tools i {
color: black;
}
    h1 {
        color: #f2f2f2;
        font-family: Arial;
        font-size: 14px;
        margin: 0 0 20px;
        padding: 0;
        text-align: center;
    }
		.textcontent {
		display: inline;
	}
	.textcontent a {
		color: #0078C1;
		font-weight: bold;
	}
    input[type="checkbox"]:not(old) {
        width   : 28px;
        margin  : 0;
        padding : 0;
        opacity : 0;
    }
    input[type="checkbox"]:not(old) + label {
        color: #f2f2f2;
        font-family: Arial,sans-serif;
        font-size: 14px;
    }
    input[type="checkbox"]:not(old) + label span {
        background: rgba(0, 0, 0, 0) url("../../images/check_radio_sheet.png") no-repeat scroll left top;
        cursor: pointer;
        display: inline-block;
        height: 19px;
        margin-left  : -28px;
        vertical-align: middle;
        width: 19px;
    }
    input[type="checkbox"]:checked + label span {
        background: rgba(0, 0, 0, 0) url("../../images/check_radio_sheet.png") no-repeat scroll -19px top;
    }
</style>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body class="body_title">
<div class="wrapper" style="background-color: #ecf0f5">

<section class="content" style="min-height: 537px">

    <div id="alert21" class="alert alert-success alert-dismissable" style="display:none;">
        <button id="dismiss11" type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
        <h4><i class="icon fa fa-check"></i>Alert!</h4>
        <div id="message-success2"></div>
    </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Notifications</h3>
                    <div class="pull-right">
                        <a href="#" onclick="deleteAll()" class="btn btn-danger">Delete all</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-12">
                        <!-- Custom tabs (Charts with tabs)-->




                        <!-- TO DO List -->
                        <div class="box box-default">
                            <!--
<div class="box-header">
<i class="ion ion-clipboard"></i>
<h3 class="box-title">Task</h3>
<div class="box-tools pull-right">
<ul class="pagination pagination-sm inline">
<li><a href="#">&laquo;</a>
</li>
<li><a href="#">1</a>
</li>
<li><a href="#">2</a>
</li>
<li><a href="#">3</a>
</li>
<li><a href="#">&raquo;</a>
</li>
</ul>
</div>
</div>
                            -->
                            		<?php

		$noti_qry = "select a.model_id,notification_id,b.message,b.type, b.icon_class,c.user_id,is_read,c.created_at
from notifications a, notification_types b, user_notification c
where a.type_id=b.id and a.id=c.notification_id and c.user_id=".$_SESSION['authUserID']." ORDER BY c.created_at desc";
			$notification_arr = sqlStatement($noti_qry);
					 
					 ?>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <ul class="todo-list">
                                   <?php
							
								if(sqlNumRows($notification_arr)) {
									
								while($notification = sqlFetchArray($notification_arr)) { 
								
							if($notification['type'] == 'registration') {
									if($notification['is_read'] == 0) {
$bk_color = "";
									}
else {
$bk_color = "task";
}	?>
                                    <li class="<?php echo $bk_color ?>">
                                        <!-- drag handle -->

                                        <!-- checkbox -->
                                        <input type="checkbox" value="" name="cc" class="noti_User clickfun" id="<?php echo $notification['notification_id']; ?>">
                                        <label for='cl'  data-toggle="tooltip"  data-placement="top" title="Mark Read"><span></span>&nbsp<img src="../../images/patient-icon-6.png" class="img-circle" style="width:25px;height: 25px" alt="User Image" />
                                            <!-- todo text -->
                                            <h6 class="textcontent marginzero"><a href="<?php echo "../../patient_file/summary/demographics.php?set_pid=".$notification['model_id'] ?>" id="<?php echo $notification['notification_id']; ?>" class='noti_User'><?php echo $notification['message'] ?></a></h6>
                                            <small class="label label-danger"><i class="fa fa-clock-o"></i> <?php $datetime1 = new DateTime(); $datetime2 = new DateTime($notification['created_at']);
$interval = $datetime1->diff($datetime2);
echo $interval->format('%D days %H hours %I minutes ago'); ?></small></label> <!-- Emphasis label -->

                                        <!-- General tools such as edit or delete-->
                                        <div class="tools">
                                            <a href="<?php echo "../../patient_file/summary/demographics.php?set_pid=".$notification['model_id'] ?>"  data-toggle="tooltip"  data-placement="top" title="View" id="<?php echo $notification['notification_id']; ?>" class='noti_User'><i class="fa fa-eye"></i></a>
                                            <a href="#" id='<?php echo $notification['notification_id']; ?>' data-toggle="tooltip"  data-placement="top" title="Delete" class='notification-delete clickfun'><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </li>
                                   <?php  } } } else { ?>
                               
								
                                    <li>

                                        <h6 class="textcontent marginzero">No notifications available</h6>
                                        <small class="label label-warning" ><i class="fa fa-bell-slash-o"></i></small>

                                    </li>
                                   <?php } ?>

                                </ul>
                            </div>

                        </div>
                        <!-- /.box -->


                    </section>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
			
			</section>
		
			<footer class="main-footer" style="margin-left: 0px">
                            <div class="pull-right hidden-xs">
                                <b>Version</b> 0.1
                            </div>
            <strong>Copyright &copy; <?php echo date('Y'); ?>  <a href="kavaii.com" style="color: black">Kavaii</a>.</strong> All rights reserved. Powered by <a href="http://www.medsmart.co.in/"  style="color: black">MedSmart</a>
                        </footer>
    </div>    
<script>
    $(document).ready(function() {
        $(".clickfun").click(function() {
            $(this).closest("li").toggleClass("task");
        });
    });
			function deleteAll() {
    var cnf = confirm("Are you sure you want to clear notifications?");
if(cnf == true) {
            var id = 1;
            var dataString = 'del-all=' + id;
            $.ajax
                    ({
                        type: "POST",
                        url: "notificationHandler.php",
                        data: dataString,
                        cache: false,
                        success: function(response)
                        {
                            if (response == 1)
                            {

                                var message = "Success! You have cleared all notifications successfully!";
                                $("#alert21").show();
                                $('#message-success2').html(message);
                            }
                        }
                    });
} else {

}
        }
</script>
<script>
    $(document).ready(function() {
        $('.noti_User').click(function() {
			var id = this.id;
			var dataString = 'id=' + id; 
                    $.ajax
                    ({
                    type: 'POST',
                            url: 'notificationHandler.php',
                            data: dataString,
                            cache: false,
                            success: function (html)
                            {
							                          if (html == 1)
                            {

                            }
                            }
                    });
					   });
        $('.notification-delete').click(function() {
			
            var id = this.id;
            var dataString = 'del-id=' + id;
            $.ajax
                    ({
                        type: "POST",
                        url: "notificationHandler.php",
                        data: dataString,
                        cache: false,
                        success: function(response)
                        {
                            if (response == 1)
                            {

                                var message = "Success! You have deleted this notification successfully!";
                                $("#alert21").show();
                                $('#message-success2').html(message);
                            }
                        }
                    });
        });

    });
</script>
<script src="../../library/dist/jQuery/jquery-2.2.3.min.js"></script>
<script src="../../library/js/bootstrap.min.js"></script>
<script src="../../library/dist/js/app.min.js"></script>
<script src="../../library/iCheck/icheck.min.js" type="text/javascript"></script>
                    
</body>
</html>