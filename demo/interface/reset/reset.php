<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

$ignoreAuth=true;
include_once("../globals.php");
include_once("$srcdir/sql.inc");
require_once("$srcdir/authentication/common_operations.php");  
	
if(!empty($_POST['token'])) {
	session_destroy();
	    $email = $_POST['user_email'];
        $password = $_POST['user_password'];
        $token = $_POST['token'];
		$response = 'fails';
		if(strlen($token) == 6) {
		$password_tokens = sqlQuery("SELECT * from otp_expiry where otp='$token'");
		        if ($password_tokens) {
		$mob_no = $password_tokens['mobile_no'];
            if ($password_tokens['otp'] == $token) {
				
                $user = sqlQuery("SELECT * from users where phonecell='$mob_no'");
                if ($user) {
					        require_once("$srcdir/authentication/password_change.php");
        $password_err_msg="";
		$adminID = '1';
		$adminpass = 'pass';
        $success=update_password($adminID,$user['id'],$adminpass,$password,$password_err_msg);

        if(!$success)
        {
			$response = 'fails';
            error_log($password_err_msg);    
        }
                    $response = 'success';
                } else {
                    $response = 'fails';
                }
            }
        }
		}
        else {
        $password_tokens = sqlQuery("SELECT * from password_resets where email='$email'");
		
        if ($password_tokens) {
		
            if ($password_tokens['token'] == $token) {
				
                $user = sqlQuery("SELECT * from users where username='$email'");
                if ($user) {
					        require_once("$srcdir/authentication/password_change.php");
        $password_err_msg="";
		$adminID = '1';
		$adminpass = 'pass12';
        $success=update_password($adminID,$user['id'],$adminpass,$password,$password_err_msg);

        if(!$success)
        {
			$response = 'fails';
            error_log($password_err_msg);    
        }
                    $response = 'success';
                } else {
                    $response = 'fails';
                }
            }
        }
		}
        if ($response == 'success') {
            $_SESSION["success"] = "You have successfully changed your password.Click <a href='../login/login.php'>here</a> to login.";
        } else {
            $_SESSION["failed"] = "Due to some mismatch in credentials your password could not be reset";
        }
}
?>
<!DOCTYPE html>
<html  ng-app="app">
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
        <link href="ghpages-materialize.css" rel="stylesheet" type="text/css" />
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
.f, .btn-large {
    font-weight: 300;
}
.btn-large {
    height: 54px;
    line-height: 54px;
}
.waves-effect {
    position: relative;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    vertical-align: middle;
    z-index: 1;
    transition: .3s ease-out;
}
.f, .btn-large {
    text-decoration: none;
    color: #fff;
    background-color: #26a69a;
    text-align: center;
    letter-spacing: .5px;
    transition: .2s ease-out;
    cursor: pointer;
}
.f, .btn-large, .btn-floating {
    position: relative;
    overflow: hidden;
    background-color: #42c4ac;
    box-shadow: none;
    border: 2px solid #eee;
    box-sizing: content-box;
    color: #eee;
    transition: color .2s, background-color .2s;
}
.f:hover, .btn-large:hover, .btn-floating:hover {
    box-shadow: none;
    background-color: #eee;
    color: #444;
}
a:active, a:hover {
    outline: 0;
}
.btn-large:hover::before {
    transform: skewX(-45deg) translateX(-30px);
}

.f:hover::before, .btn-large:hover::before, .btn-floating:hover::before {
    transform: skewX(-45deg) translateX(-20px);
}

.btn-large::before {
    width: calc(100% + 60px);
    transform: skewX(-45deg) translateX(calc(-100% - 30px));
}

.f::before, .btn-large::before, .btn-floating::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100% + 40px);
    height: 100%;
    background-color: #eee;
    transform: skewX(-45deg) translateX(calc(-100% - 20px));
    transition: transform .2s;
    z-index: -1;
}

*, *:before, *:after {
    box-sizing: inherit;
}
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
input:focus ~ .floating-label,
input:not(:focus):valid ~ .floating-label{
  top: 8px;
  bottom: 10px;

  font-size: 11px;
  opacity: 1;
}

.inputText {
  font-size: 14px;
  width: 200px;
  height: 35px;
}

.floating-label {
  position: absolute;
  pointer-events: none;
  left: 20px;
  top: 18px;
  transition: 0.2s ease all;
}
.input-field {
	margin: 0px;
}	
</style>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.7/angular.min.js"></script>
 
<script language='JavaScript' src="../../library/js/jquery-1.4.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-messages/1.5.7/angular-messages.min.js"></script>
</head>
<body ng-controller="mainCtrl">
<div class="logobar" style="height: 110px">
<img style="position:absolute;top:0;left:0;max-width: none" src="../pic/logo.gif">


</div>
<div class="title_bar" style="    position: sticky;
    padding: 10px;
    margin-top: 8px;
    background: black;">
<div class="">MedSMART hOS<a href="#" onclick="window.top.location.href = '../../patients'; " style="float:right;color:#ddd">Login as Patient</a></div>
</div>
<div id="loading"></div>
        <div id="page" class="hfeed site">
            <!-- Main content -->
            <div id="main" style="background:url('../login/hd.jpg');    background-position: 75% 14%;
    min-height: 432px;">
                <div class="container">
                    <div class="content-area">
                        <div class="row">
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissable">
    <i class="fa  fa-check"> </i> <b> Alert </b>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    

<?php echo $_SESSION['success']; ?>
</div>
<?php endif; ?>
						
<?php if (isset($_SESSION['failed'])): ?>
<div class="alert alert-danger alert-dismissable">
    <i class="fa  fa-ban"> </i> <b> Alert </b>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    

<?php echo $_SESSION['failed']; ?>
</div>
<?php endif; ?>

 <div class="login-box" style=" width: 500px;"  valign = "center">

 <div class="form-border" id='reset-form'>

<h5 class="login-box-msg">Reset your password</h5>
<div class="panel-body">
    <form class="col s12 form-horizontal" role="form" method="POST" action="" name="add_user_form">
        <input type="hidden" name="token" value="<?php if(isset($_GET['token'])) { echo $_GET['token']; } ?>">
        <!-- Email -->  <div class="fixed-action-btn horizontal click-to-toggle">
    <a class="btn-floating btn-large red">
      <i class="material-icons">menu</i>
    </a>
    <ul>
      <li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>
      <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
      <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
      <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
    </ul>
  </div>
		<div class="row">
  
        <div class="form-group has-feedback input-field col s12" ng-class="{ 'has-error' : add_user_form.user_email.$dirty && add_user_form.user_email.$invalid }">
            <input type="text" class="form-control validate"  id="user_email" name="user_email" ng-model="email" required>
            <label for="email" data-error="wrong" data-success="right" class="active">User ID</label>
			<span class="glyphicon glyphicon-envelope form-control-feedback" style="top: 10px;"></span>
			         <div class="help-block" ng-messages="add_user_form.user_email.$error" ng-if="add_user_form.user_email.$dirty">
          <p ng-message="required">This field is required</p>
		  </div>
        </div>
		</div>
        <!-- password -->
				<div class="row">
        <div class="form-group has-feedback input-field col s12" ng-class="{ 'has-error' : add_user_form.user_password.$dirty && add_user_form.user_password.$invalid }">
      
			
			<input type="password" class="form-control validate"  id="user_password" name="user_password"  ng-minlength="6" required ng-model="user.user_password" password-verify="{{user.confirm_password}}">
			<label for="password" data-error="wrong" data-success="right" class="active">Password</label>
            <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 10px;"></span>
			<div class="help-block" ng-messages="add_user_form.user_password.$error" ng-if="add_user_form.user_password.$dirty">
          <p ng-message="required">This field is required</p>
          <p ng-message="minlength" ng-if="!add_user_form.user_password.$valid">This field is too short</p>
          <p ng-message="maxlength">This field is too long</p>
          <p ng-message="required">This field is required</p>
          <p ng-message="passwordVerify">No match!</p>
        </div>
        </div>
		</div>
				<div class="row">
        <!-- confirm password -->
        <div class="form-group has-feedback input-field col s12" ng-class="{ 'has-error' : add_user_form.confirm_password.$dirty && add_user_form.confirm_password.$invalid }">

			<input type="password" class="form-control validate" id="confirm_password" ng-model="user.confirm_password" name="confirm_password" required password-verify="{{user.user_password}}">
            <label for="confirm_password" data-error="wrong" data-success="right" class="active">Confirm Password</label>
			<span class="glyphicon glyphicon-log-in form-control-feedback" style="top: 10px;"></span>
        </div>
		</div>
        <!-- Confirm password -->
        <div class="form-group">
            <div class="col-md-3"><!--<a class="waves-effect waves-light btn-large">Button</a> --></div>
            <div class="col-md-3">
                <button type="submit" class="waves-effect waves-light btn-large" ng-disabled="add_user_form.$invalid">
                    Reset Password
                </button>
            </div>
        </div>
    </form>
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
			<script src="material.js" type="text/javascript"></script>
			
            <!-- Bootstrap 3.3.2 JS -->
            <script src="../../library/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- Slimscroll -->
<script>
(function() {
  "use strict";
  angular
    .module('app', ['ngMessages'])
    .controller('mainCtrl', mainCtrl)
    .directive('passwordVerify', passwordVerify);

  function mainCtrl($scope) {
    // Some code
  }

  function passwordVerify() {
    return {
      restrict: 'A', // only activate on element attribute
      require: '?ngModel', // get a hold of NgModelController
      link: function(scope, elem, attrs, ngModel) {
        if (!ngModel) return; // do nothing if no ng-model

        // watch own value and re-validate on change
        scope.$watch(attrs.ngModel, function() {
          validate();
        });

        // observe the other value and re-validate on change
        attrs.$observe('passwordVerify', function(val) {
          validate();
        });

        var validate = function() {
          // values
          var val1 = ngModel.$viewValue;
          var val2 = attrs.passwordVerify;

          // set validity
          ngModel.$setValidity('passwordVerify', val1 === val2);
        };
      }
    }
  }
})();
</script>
</body>
</html>
