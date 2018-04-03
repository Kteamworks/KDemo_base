<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

$ignoreAuth=true;
include_once("../globals.php");
include_once("$srcdir/sql.inc");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> MedSmart </title>
        <link href="../../images/logo.png"  rel="shortcut icon" >
        
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="../../library/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Admin LTE CSS -->
        <link href="../../library/css/AdminLTEsemi.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

        <link href="../../library/css/app.css" rel="stylesheet" type="text/css" />
        
       <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		    <link href="../../library/css/widgetbox.css" rel="stylesheet" type="text/css" />
        <link href="../../library/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <link href="../../library/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        
        
<?php html_header_show();?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">
<style>
#loading {
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(0,0,0,.5);
	background-image: url('gifloader.gif');
	background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; 
    -webkit-transition: all .5s ease;
    z-index: 1000;
    display:none;
}
</style>
<script language='JavaScript' src="../../library/js/jquery-1.4.3.min.js"></script>
</head>
<body>
<div id="loading"></div>
        <div id="page" class="hfeed site">
            <!-- Main content -->
            <div id="main" style="background:url('../login/hd.jpg');    background-position: 75% 14%;
    min-height: 432px;">
                <div class="container">
                    <div class="content-area">
                        <div class="row">
						

<script type="text/javascript"> $(function(){ $('.dialogerror, .dialoginfo, .dialogalert').fadeIn('slow');$("form").bind("submit", function(e){$(this).find("input:submit").attr("disabled", "disabled");});});</script>
<script type="text/javascript" >try {if (top.location.hostname != self.location.hostname) { throw 1; }} catch (e) { top.location.href = self.location.href; }</script>

 <div class="login-box" style=" width: 500px;"  valign = "center">
 	   <div style="display:none" id='response'>
	   <div class="alert alert-success alert-dismissable">
    <i class="fa  fa-check-circle"> </i> <b> Success! </b>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    An email with reset password link has been sent to your registered email address. Please check your email to reset the password. <a href='../login/login_frame.php'>Login Page</a>
</div>
	   </div>
 <div class="form-border" id='reset-form'>

 <h3 class="box-title" align="center">Forgot your password</h3>

                <br>  
                       
<!-- form open -->
<form role="form" method="POST" action="" id='password-reset-form'>
    <!-- Email -->
    <input type="hidden" name="_token" value="<?php echo md5(uniqid(rand(), TRUE)); ?>">
    <!-- Email -->
    <!-- <div class="input-group margin"> -->
	<div class="form-group has-feedback otp_form" id="otp">
			<label>Enter OTP</label>
		<p style="color:#31ab00;">Check your mobile for the OTP</p>
			
		<div class="tablerow">
			<input type="text" name="otp" placeholder="One Time Password" class="form-control" required>
		</div>
		<!--<div class="tableheader">
		<input type="submit" name="submit_otp" value="Submit"  onclick="email()" class="btnSubmit">
		<button type="button" style="margin-left: 10px;" class="btn btn-primary btn-block btn-flat" onclick="sendOTP()">Submit OTP</button>
		</div>-->
		</div>
		
    <div class="form-group has-feedback" id="user_details">
            <label>Enter your User ID/Mobile No:</label>

        <input type="email" class="form-control" id="email" name="email" placeholder="User ID / Mobile No:" >
         <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    


	</form>
	    <div class="row">
    <div class="col-xs-4">

    <a href="../login/login.php" class="text-center">I know my password</a>    
    </div>
    <div class="col-xs-8">
        <!--<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block btn-flat" onclick="otp()">Send OTP</button></span>-->
        <span class="input-group-btn">
		<button type="button" style="margin-left: 10px;"id="otp_btn"  class="btn btn-primary btn-block btn-flat" onclick="sendOTP()">Submit OTP</button>
		<button type="button" id="email_btn" style="margin-left: 10px;" class="btn btn-primary btn-block btn-flat" onclick="email()">Send Email / OTP</button></span>
        <br/>
    </div>
    </div> 
       </div>

    </div>      
    <!-- </div> -->
</div>
</div>
</div>
</div>
 <footer id="colophon" class="site-footer" role="contentinfo" style="
    padding: 0;
    padding-top: 15px;">
                <div class="container">

                    <div class="row">
                        <div class="site-info col-md-6">
                            <p class="text-muted">Copyright &copy; <?php echo date('Y') ?>  <a href="www.kavaii.com" target="_blank"><b>Kavaii</b></a>. All rights reserved. Powered by <a href="http://www.medsmart.co,in/"  target="_blank"><b>MedSmart</b></a></p>
                        </div>
                        <div class="site-social text-right col-md-6">

                            <ul class="list-inline hidden-print">

                                <li><a href="" class="btn btn-social btn-facebook" target="_blank"><i class="fa fa-facebook fa-fw"></i></a></li>

                                <li><a href="" class="btn btn-social btn-twitter" target="_blank"><i class="fa fa-twitter fa-fw"></i></a></li>

                                <li><a href="" class="btn btn-social btn-google-plus" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a></li>

                                <li><a href="" class="btn btn-social btn-linkedin" target="_blank"><i class="fa fa-linkedin fa-fw"></i></a></li>

                            </ul>
                        </div>
                    </div>
					</div>
            </footer><!-- #colophon -->
			</div>
            <script src="../../library/js/jquery2.1.1.min.js" type="text/javascript"></script>
            <!-- Bootstrap 3.3.2 JS -->
            <script src="../../library/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- Slimscroll -->
		            
			    <script type="text/javascript">
				$("#otp").hide();
				$("#otp_btn").hide();
				    function otp() {
		  var f = document.getElementById('password-reset-form');
  if (!f.email.value) {
   alert('Please enter Email / Mobile No.');
   return false;
  }
  else {
	  		  var data = $("#password-reset-form").serialize();
 // var dataString = 'form_category='+form_category+'&form_date='+form_date+'&form_title='+form_title+'&form_minute='+form_minute+'&form_ampm='+form_ampm+'&form_hour='+form_hour;
  	 		        $.ajax({
                // Where to send request
                url: 'passwordHandler.php',
                // What to send
                data: data,
                // How to send
                type: 'post',
                // What to do when request succeeds
						beforeSend: function(){
			$("#loading").show();
		},
                success: function(response) {
				//	var ords = 'ORDS00000'+response;
        $("#EIDSAV").val(response);
		$("#loading").hide();
                       $('#form2').submit();
		
				}
        });

  }
    }
	  function email() {
		  var f = document.getElementById('password-reset-form');
  if (!f.email.value) {
   alert('Please enter Email / Mobile No.');
   return false;
  }
  else {
	  		  var data = $("#password-reset-form").serialize();
 // var dataString = 'form_category='+form_category+'&form_date='+form_date+'&form_title='+form_title+'&form_minute='+form_minute+'&form_ampm='+form_ampm+'&form_hour='+form_hour;
  	 		        $.ajax({
                // Where to send request
                url: 'passwordHandler.php',
                // What to send
                data: data,
                // How to send
                type: 'post',
                // What to do when request succeeds
						beforeSend: function(){
			$("#loading").show();
		},
                success: function(response) {
					
if(response == 1) {
		$("#loading").hide();
		$("#reset-form").hide();
        $("#response").show();
} 
else if(response == 123) {
	$("#loading").hide();
		$("#user_details").remove();
        $("#otp").show();
		$("#otp_btn").show();
			$("#email_btn").hide();
}
else {
alert(response);
}
		
				}
        });

  }
    }
	function sendOTP() {
		  var f = document.getElementById('password-reset-form');

	  		  var data = $("#password-reset-form").serialize();
 // var dataString = 'form_category='+form_category+'&form_date='+form_date+'&form_title='+form_title+'&form_minute='+form_minute+'&form_ampm='+form_ampm+'&form_hour='+form_hour;
  	 		        $.ajax({
                // Where to send request
                url: 'passwordHandler.php',
                // What to send
                data: data,
                // How to send
                type: 'post',
                // What to do when request succeeds
						beforeSend: function(){
			$("#loading").show();
		},
                success: function(response) {
					var myObj = jQuery.parseJSON(response);
					
if(myObj.result === '11') {
		$("#user_details").hide();
        $("#otp").show();
		$("#loading").hide();
} 
else if(myObj.result === '12') {
			$("#loading").hide();
	window.top.location = "reset.php?token="+myObj.otp;
		//alert("OTP Authenticated");
}
else {
	alert(myObj.message);
		$("#loading").hide();
}
		
				}
        });

  
    }
	</script>
</body>
</html>


