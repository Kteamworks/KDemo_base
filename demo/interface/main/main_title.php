<?php
/**
 * main_title.php - The main titlebar, at the top of the 'concurrent' layout.
 */

include_once('../globals.php');
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../library/dist/css/AdminLTE.css">
<link rel="stylesheet" href="../../library/css/mycss.css">
<style type="text/css">
@font-face {
    font-family: 'digital';
    src: url('../../library/fonts/digital-7.ttf');
}
      .hidden {
        display:none;
      }
      .visible{
        display:block;
      }
	  .main-header .navbar {
		  margin-left: 0px;
		  margin-right: 10px;
	  }
	  #txt {
		      position: absolute;
    left: 119px;
	  top: 8px;
	  }
	  .main-header .sidebar-toggle {
    margin-left: 75px;
}
</style>

<script type="text/javascript" language="javascript">

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
	elem = document.getElementById('txt');
    elem.innerHTML =
    h + ":" + m + ":" + s;
elem.style.fontFamily = "digital";
elem.style.fontSize = "30px";

    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

function toencounter(rawdata) {
//This is called in the on change event of the Encounter list.
//It opens the corresponding pages.
	document.getElementById('EncounterHistory').selectedIndex=0;
	if(rawdata=='')
	 {
		 return false;
	 }
	else if(rawdata=='New Encounter')
	 {
	 	top.window.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=')
		return true;
	 }
	else if(rawdata=='Past Encounter List')
	 {
	 	top.window.parent.left_nav.loadFrame2('pel1','RBot','patient_file/history/encounters.php')
		return true;
	 }
    var parts = rawdata.split("~");
    var enc = parts[0];
    var datestr = parts[1];
    var f = top.window.parent.left_nav.document.forms[0];
	frame = 'RBot';
    if (!f.cb_bot.checked) frame = 'RTop'; else if (!f.cb_top.checked) frame = 'RBot';

    top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
    parent.left_nav.setEncounter(datestr, enc, frame);
    parent.left_nav.setRadio(frame, 'enc');
    top.frames[frame].location.href  = '../patient_file/encounter/encounter_top.php?set_encounter=' + enc;
<?php } else { ?>
    top.Title.location.href = '../patient_file/encounter/encounter_title.php?set_encounter='   + enc;
    top.Main.location.href  = '../patient_file/encounter/patient_encounter.php?set_encounter=' + enc;
<?php } ?>
}
function showdropdownMenu() {
var frameDocument = $('frame[name="RTop"]', top.document)[0].contentDocument;
	var dropdown = $("#cross-frame").html();
        if (! $(frameDocument).find('.navbar-custom-menu')[0]) {
           $(frameDocument).find('body').prepend('<div class="navbar-custom-menu"><div class="nav navbar-nav"><li  class="dropdown notifications-menu">'+ dropdown +'</li></div></div>');
        $(frameDocument).find('.noti_User').hover(function() {
    $(this).siblings("span").css("opacity","1");
}, function() {
    $(this).siblings("span").css("opacity","0");
});
		} else {
$(frameDocument).find('.navbar-custom-menu').remove();

}
}
function showhideMenu() {
	var m = parent.document.getElementById("fsbody");
	var targetWidth = '0,*';
	if (m.cols == targetWidth) {
		m.cols = '<?php echo $GLOBALS['gbl_nav_area_width'] ?>,*';
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Hide Menu'), ENT_QUOTES); ?>';
	} else {
		m.cols = targetWidth;
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Show Menu'), ENT_QUOTES); ?>';
	}
}
</script>
</head>
<body class="body_title" onload="startTime()">
<?php
$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");

?>

<table cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
<td align="left">
<?php if ($GLOBALS['concurrent_layout']) { ?>
	<table cellspacing="0" cellpadding="1" style="margin:0px 0px 0px 3px;">

<?php if (acl_check('patients','demo','',array('write','addonly') )) { ?>
<tr class="main-header"><td style="vertical-align:text-bottom;">
<a  href='main_title.php' onclick="javascript:parent.left_nav.goHome();return false;" class="logo" style="margin:0px">
      <span class="logo-lg"><img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/logo.png" /></span><div id="txt"></div>
    </a>
	<a href="#" class="sidebar-toggle"  href="main_title.php" id='showMenuLink' title="Hide Sidebar" onclick='javascript:showhideMenu();return false;'>
        <span class="sr-only"><?php xl('Hide Menu','e'); ?></span>
      </a>
	  <?php if($res['newcrop_user_role'] == 'erxrep') { ?>
		<a href='' class="css_button_small" style="margin-top:14px;vertical-align:top;" id='new0' onClick=" return top.window.parent.left_nav.loadFrame2('new0','RTop','new/new.php')">
		<span><?php echo htmlspecialchars( xl('NEW PATIENT'), ENT_QUOTES); ?></span></a>
	  <?php } ?>
    </td>
    <td>
            <a href='' class="css_button_small" style="margin:0px;vertical-align:top;display:none;" id='clear_active' onClick="javascript:parent.left_nav.clearactive();return false;">
            <span><?php echo htmlspecialchars( xl('CLEAR ACTIVE PATIENT'), ENT_QUOTES); ?></span></a>
    </td>
</tr>
<?php } //end of acl_check('patients','demo','',array('write','addonly') if ?>

	<!--<tr><td valign="baseline"><B>
		<a class="text" style='vertical-align:text-bottom;' href="main_title.php" id='showMenuLink' onclick='javascript:showhideMenu();return false;'><?php xl('Hide Menu','e'); ?></a></B>
	</td></tr> --></table>
<?php } else { ?>
&nbsp;
<?php } ?>
</td>
 <td style="margin:3px 0px 3px 0px;vertical-align:middle;">
        <div style='margin-left:10px; float:left; display:none' id="current_patient_block">
            <span class='text'><?php xl('Patient','e'); ?>:&nbsp;</span><span class='title_bar_top' id="current_patient"><b><?php xl('None','e'); ?></b></span>
        </div>
</td>
<td style="margin:3px 0px 3px 0px;vertical-align:middle;" align="left">
	<table cellspacing="0" cellpadding="1" ><tr><td>
		<div style='margin-left:5px; float:left; display:none' id="past_encounter_block">
			<span class='title_bar_top' id="past_encounter"><b><?php echo htmlspecialchars( xl('None'), ENT_QUOTES) ?></b></span>
		</div></td></tr>
	<tr><td valign="baseline" align="center">	
        <div style='display:none' class='text' id="current_encounter_block" >
            <span class='text'><?php xl('Selected Visit','e'); ?>:&nbsp;</span><span class='title_bar_top' id="current_encounter"><b><?php xl('None','e'); ?></b></span> 
		</div></td></tr></table>
</td>
<td align="right" style="vertical-align:top;" class="i-pad">
		<a href="../logout.php" target="_top" id="logout_link" onclick="top.restoreSession()" title="Logout"><i class="fa fa-sign-out"></i></a>
		<!--<a href="../logout.php" target="_top" class="css_button_small" style='float:right;' id="logout_link" onclick="top.restoreSession()" >
			<span><?php echo htmlspecialchars( xl('Logout'), ENT_QUOTES) ?></span></a> -->
			</td>
<td align="right" class="non-pad">
	<table cellspacing="0" cellpadding="1" style="margin:0px 3px 0px 0px;">    
		<?php

		$noti_qry = "select a.model_id,notification_id,b.message,b.type, b.icon_class,c.user_id,is_read
from notifications a, notification_types b, user_notification c
where a.type_id=b.id and a.id=c.notification_id and c.user_id=".$_SESSION['authUserID']." ORDER BY c.created_at desc";
			$count_noti_qry = "select a.model_id,notification_id,b.message,b.type, b.icon_class,c.user_id,is_read
from notifications a, notification_types b, user_notification c
where a.type_id=b.id and a.id=c.notification_id and c.user_id=".$_SESSION['authUserID']." and c.is_read='0'";
				$notification_count = sqlStatement($count_noti_qry);
				$notification_arr = sqlStatement($noti_qry);
					 
					 ?>
	<tr class="dropdown user user-menu">
	<td>   <header class="main-header" > <nav class="navbar navbar-static-top">    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav"> <li class="dropdown notifications-menu"  onclick='javascript:showdropdownMenu();return false;'>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo sqlNumRows($notification_count); ?></span>
            </a></li>
			<div  id="cross-frame">
			<style>	  ._55m9:focus, ._55m9:hover {
		  opacity: 1 !important;
	  }</style>
			<ul class="dropdown-menu" style="display:block;position: absolute;
left: 606px;
width: 280px;
padding: 0 0 0 0;
margin: 0;
top: 100%;
box-shadow: none;
border-color: #eee;
border: 1px solid rgba(0,0,0,.15);
float: left;
min-width: 160px;
z-index: 1000;
font-size: 14px;
text-align: left;
list-style: none;
background-color: #fff;

border-radius: 4px;" >
              <li class="header" style="position: relative;border-top-left-radius: 4px;
border-top-right-radius: 4px;
border-bottom-right-radius: 0;
border-bottom-left-radius: 0;
background-color: #ffffff;
padding: 7px 10px;
border-bottom: 1px solid #f4f4f4;
color: #444444;
font-size: 14px;
">You have <?php echo sqlNumRows($notification_count); ?> unread notifications</li>
<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                <!-- inner menu: contains the actual data -->
                <ul class="menu" style="overflow: hidden;
width: 100%;
height: 200px;
max-height: 200px;
margin: 0;
padding: 0;
list-style: none;
">
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
                  <li style="<?php echo $bk_color; ?>border-bottom: 1px solid #dddfe2;" id="noti_li<?php echo $notification['notification_id']; ?>">
                   <a href="<?php echo "../../patient_file/summary/demographics.php?set_pid=".$notification['model_id'] ?>" style="color: #444444;
overflow: hidden;
text-overflow: ellipsis;
padding: 10px;

display: inline-block;
white-space: nowrap;

"  id="<?php echo $notification['notification_id']; ?>" class='noti_User' onClick="var id = this.id;var dataString = 'id=' + id; 
                    $.ajax
                    ({
                    type: 'POST',
                            url: '../notificationHandler.php',
                            data: dataString,
                            cache: false,
                            success: function (html)
                            {
							
                            }
                    });">
                      <i class="<?php echo $notification['icon_class']; ?> text-aqua" style="width: 20px;color: #00c0ef !important;"></i> <?php echo $notification['message'] ?>
                    </a>
					<span  aria-label="Mark as Read" class="_55m9"  id="<?php echo $notification['notification_id']; ?>" style="opacity: 0;background-image: url(../../../images/fb_notification.png);
    background-repeat: no-repeat;
    background-size: auto;
    background-position: 0 -76px;
    cursor: pointer;
	    display: inline-block;
    -webkit-filter: grayscale(100%);
    height: 18px;
    margin-top: -4px;
    right: 8px;
    top: 8px;
    width: 18px;
	margin-top: 0;
    position: relative;
    right: -2px;
    top: -9px;"  onClick="var id = this.id;var dataString = 'id=' + id; 
                    $.ajax
                    ({
                    type: 'POST',
                            url: '../notificationHandler.php',
                            data: dataString,
                            cache: false,
                            success: function (html)
                            {
							if(html == 1) {
								$('#noti_li'+<?php echo $notification['notification_id']; ?>).animate({backgroundColor:'white'},'slow');
							}
                            }
                    });"  data-hover="tooltip" data-tooltip-alignh="center" title="Mark as Read" role="button" tabindex="0"></span>
                  </li>
                <?php  } } } else { ?>
                               <p>You have no new notifications!</p>
								<?php } ?>
                </ul>
				</div>
              </li>
			  
              <li style="position: relative;
			  border-top-left-radius: 0;
border-top-right-radius: 0;
border-bottom-right-radius: 4px;
border-bottom-left-radius: 4px;
font-size: 12px;
background-color: #fff;
padding: 7px 10px;
border-bottom: 1px solid #eeeeee;
color: #444 !important;
text-align: center;
display: block;
clear: both;
font-weight: 400;
line-height: 1.42857143;
white-space: nowrap;
"><a href="<?php echo 'notifications-list.php' ?>">View all</a></li>
            </ul>
						 <script>
                    $(document).ready(function () {
					function a(da) {
						alert('1');
					}
            $('.noti_User').click(function () {
				alert('hi');
            var id = this.id;
                    var dataString = 'id=' + id;
                    $.ajax
                    ({
                    type: "POST",
                            url: 'notificationHandler.php',
                            data: dataString,
                            cache: false,
                            success: function (html)
                            {
                            }
                    });
            });
            });
</script>
			</div>
			</ul>

			</div></nav></header></td>
<td>

<form action="upload.php" method="post" id="form" enctype="multipart/form-data">
<input type="file" id="imgupload" name="fileToUpload" style="visibility:hidden;position:absolute;opacity:0"/> 
</form>
            <a href="#" class="dropdown-toggle" title="Upload Image" id="OpenImgUpload" data-toggle="dropdown">
			<?php if($res['user_image']) { ?>
              <img src="<?php echo $res['user_image']; ?>" class="user-image" alt="User Image">
			<?php } elseif($res['newcrop_user_role'] == 'erxdoctor') { ?>
			<img src="../../library/dist/img/doctor.png" class="user-image" alt="User Image">
			<?php } elseif($res['newcrop_user_role'] == 'erxrep') { ?>
			<img src="../../library/dist/img/receptionist.png" class="user-image" alt="User Image">
			<?php } elseif($res['newcrop_user_role'] == 'erxcash') { ?>
			<img src="../../library/dist/img/cashier.png" class="user-image" alt="User Image">
			<?php } elseif($res['newcrop_user_role'] == 'erxnurse') { ?>
			<img src="../../library/dist/img/nurse.png" class="user-image" alt="User Image">
			<?php } elseif($res['newcrop_user_role'] == 'erxlab') { ?>
			<img src="../../library/dist/img/lab.png" class="user-image" alt="User Image">
			<?php } else { ?>
			<img src="../../library/dist/img/user.jpg" class="user-image" alt="User Image">
			<?php } ?>
              <span class="hidden-xs" title="<?php echo htmlspecialchars( xl('Authorization group') .': '.$_SESSION['authGroup'], ENT_QUOTES); ?>"><?php echo htmlspecialchars($res{"fname"}.' '.$res{"lname"},ENT_NOQUOTES); ?></span>
            </a></td>
		<!-- <td align="right" class="text" style="vertical-align:text-bottom;"><a href='main_title.php' onclick="javascript:parent.left_nav.goHome();return false;" ><?php xl('Home','e'); ?></a>
		&nbsp;|&nbsp;
		<a href="http://open-emr.org/wiki/index.php/OpenEMR_4.2.0_Users_Guide" target="_blank" id="help_link" >
			<?php xl('Manual','e'); ?></a>&nbsp;</td> -->
		<td align="right" style="vertical-align:top;">
		<a href="../logout.php" target="_top" id="logout_link" onclick="top.restoreSession()" title="Logout"><i class="fa fa-sign-out"></i></a>
		<!--<a href="../logout.php" target="_top" class="css_button_small" style='float:right;' id="logout_link" onclick="top.restoreSession()" >
			<span><?php echo htmlspecialchars( xl('Logout'), ENT_QUOTES) ?></span></a> -->
			</td>
	</tr>
	<!--<tr>
		<td colspan='2' valign="baseline" align='right'><B>
			<span class="text title_bar_top" title="<?php echo htmlspecialchars( xl('Authorization group') .': '.$_SESSION['authGroup'], ENT_QUOTES); ?>"><?php echo htmlspecialchars($res{"fname"}.' '.$res{"lname"},ENT_NOQUOTES); ?></span></span></td>
    	</tr> -->
		</table>
</td>
</tr>
</table>
<script src="../../library/dist/jQuery/jquery-2.2.3.min.js"></script>
<script src="../../library/js/bootstrap.min.js"></script>
<script src="../../library/dist/js/app.min.js"></script>

<script type="text/javascript" language="javascript">
parent.loadedFrameCount += 1;
$('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
document.getElementById("imgupload").onchange = function() {
    document.getElementById("form").submit(function(e){
e.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: 'upload.php',
        type: 'POST',
        data: formData,
        async: false,
        success: function (data) {
            alert(data)
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});
};
</script>

</body>
</html>
