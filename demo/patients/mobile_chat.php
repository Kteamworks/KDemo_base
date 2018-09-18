<?php

session_start();

//landing page definition -- where to go if something goes wrong
$landingpage = "index.php?site=".$_SESSION['site_id'];
//

// kick out if patient not authenticated
if ( isset($_SESSION['pid']) && isset($_SESSION['patient_portal_onsite']) ) {
  $pid = $_SESSION['pid'];
}
else {
  session_destroy();
  header('Location: '.$landingpage.'&w');
  exit;
}

$patient  = $_GET['name'];





//

$ignoreAuth = true;
global $ignoreAuth;

 require_once("../interface/globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/classes/Address.class.php");
 require_once("$srcdir/classes/InsuranceCompany.class.php");
 require_once("$srcdir/classes/Document.class.php");
 require_once("$srcdir/options.inc.php");
 require_once("../interface/patient_file/history/history.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/edi.inc");
 include_once("$srcdir/lists.inc");
 // If we are saving, then save and close the window.
  $pn=getPatientData($pid, "phone_cell");
$pn_no=$pn['phone_cell'];

 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Patient | Portal</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../library/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../library/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../library/dist/css/skins/_all-skins.min.css">
<style>
.navbar-nav > .user-menu .user-image {

    background-color: white;
}
.img-circle {
	    background-color: white;
}
.height-dev {
	height: 100%;
}
.logo {
	background: transparent !important;
}

.small {
	font-size: 100%;
}
a.edit_event {
	position: relative;
font-size: 20px;
margin-right: 15px;

}
.section-header-dynamic a.edit_event:before {
	float: right;
content: "\f055";
font-family: FontAwesome;
left: -5px;
position: absolute;
top: 0;
}
</style>
<?php html_header_show(); ?>

<link rel="stylesheet" type="text/css" href="<?php echo $web_root; ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $web_root; ?>/library/dynarch_calendar.css">
<script type="text/javascript" src="<?php echo $web_root; ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $web_root; ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<link rel="stylesheet" href="css/base.css" type="text/css"/>
<link rel="stylesheet" href="css/tables.css" type="text/css"/>


<style type="text/css">
   /* textarea { border: none;
    background-color: transparent;
    resize: none;
    outline: none;
	//margin-top: 50px;
     } */
	 
	input[type=text], select, textarea {
    background-color: transparent;
	border: none;
    width: 100%;
    padding: 12px;
    resize: none;
	outline: none;
}
</style>
  
  <style>
	.main-section{
  //width: 400px;
 // position: fixed;
 // right:50px;
  //bottom: -420px;
  background-color: white;
}

.first-section:hover{
  cursor: pointer;
}
.open-more{
  bottom:0px;
  transition:2s;
}
.border-chat{
  border:1px solid #0078C1;
  margin: 0px;
}
.first-section{
  background-color:#0078C1;
}
.first-section p{
  color:#fff;
  margin:0px;
  padding: 10px 0px;
}
.first-section p:hover{
  color:#fff;
  cursor: pointer;
}
.right-first-section{
   text-align: right;
}
.right-first-section i{
  color:#fff;
  font-size: 15px;
  padding: 12px 3px;
}
.right-first-section i:hover{
  color:#fff;
}
.chat-section ul li{
  list-style: none;
  margin-top:10px;
  position: relative;
}
.chat-section{
  overflow-y:scroll;
  height:300px;
}
.chat-section ul{
  padding: 0px;
}


.left-chat img,.right-chat img{
  width:50px;
  height:50px;
  float:left;
  margin:0px 10px;
}
.right-chat img{
  float:right;
}
.second-section{
  padding: 0px;
  margin: 0px;
  background-color: #F3F3F3;
  height: 300px;
}
.left-chat,.right-chat{
  overflow: hidden;
}
.left-chat p,.right-chat p{
  background-color:#FD8468;
  padding: 10px;
  color:#fff;
  border-radius: 5px; 
  float:left;
  width:60%;
  margin-bottom:20px;
}
.left-chat span,.right-chat span{
  position: absolute;
  left:70px;
  top:60px;
  color:#B7BCC5;
}
.right-chat span{
  left:45px;
}
.right-chat p{
  float:right;
  background-color: #FFFFFF;
  color:#FD8468;
}
.third-section{
  border-top: 1px solid #EEEEEE;
}
.comments:focus{
    border: 0 none #FFF;
    overflow: hidden;
    outline:none;
}

.text-bar input{
  width:90%;
  margin-left:-15px;
  padding:10px 10px;
  border:1px solid #fff;
}
.text-bar a i{
  background-color:#FD8468;
  color:#fff;
  width:30px;
  height:30px;
  padding:7px 0px;
  border-radius: 50%;
  text-align: center;
}
.left-chat:before{
  content: " ";
  position:absolute;
  top:0px;
  left:55px;
  bottom:150px;
  border:15px solid transparent;
  border-top-color:#FD8468; 
}
.right-chat:before{
  content: " ";
  position:absolute;
  top:0px;
  right:55px;
  bottom:150px;
  border:15px solid transparent;
  border-top-color:#fff; 
}

</style>



   
<script type="text/javascript">

var t = setInterval(function(){get_chat_msg()},5000);


//
// General Ajax Call
//
      
var oxmlHttp;
var oxmlHttpSend;
      
function get_chat_msg()
{

    if(typeof XMLHttpRequest != "undefined")
    {
        oxmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
       oxmlHttp = new ActiveXObject("Microsoft.XMLHttp");
    }
    if(oxmlHttp == null)
    {
        alert("Browser does not support XML Http Request");
       return;
    }
    
	if (document.getElementById("txtname") != null)
    {
        strname = document.getElementById("txtname").value;
        document.getElementById("txtname").readOnly=true;
    }
    oxmlHttp.onreadystatechange = get_chat_msg_result;
    oxmlHttp.open("GET","chat_recv_ajax.php?name=" + strname,true);
    oxmlHttp.send(null);
}
     
function get_chat_msg_result()
{
    if(oxmlHttp.readyState==4 || oxmlHttp.readyState=="complete")
    {
        if (document.getElementById("DIV_CHAT") != null)
        {
            document.getElementById("DIV_CHAT").innerHTML =  oxmlHttp.responseText;
            oxmlHttp = null;
        }
        var scrollDiv = document.getElementById("DIV_CHAT");
        scrollDiv.scrollTop = scrollDiv.scrollHeight;
    }
}

      
function set_chat_msg()
{

    if(typeof XMLHttpRequest != "undefined")
    {
        oxmlHttpSend = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
       oxmlHttpSend = new ActiveXObject("Microsoft.XMLHttp");
    }
    if(oxmlHttpSend == null)
    {
       alert("Browser does not support XML Http Request");
       return;
    }
    
    var url = "chat_send_ajax.php";
    var strname="noname";
    var strmsg="";
    if (document.getElementById("txtname") != null)
    {
        strname = document.getElementById("txtname").value;
        document.getElementById("txtname").readOnly=true;
    }
    if (document.getElementById("txtmsg") != null)
    {
        strmsg = document.getElementById("txtmsg").value;
        document.getElementById("txtmsg").value = "";
    }
    
    url += "?name=" + strname + "&msg=" + strmsg;
    oxmlHttpSend.open("GET",url,true);
    oxmlHttpSend.send(null);
}

</script>




</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

<?php
 $result = getPatientData($pid);
?>
  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="summary_pat_portal.php" class="logo"><img src="../images/logo.png" class="img-responsive height-dev"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
           
            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                    <li><!-- start notification -->
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                    <!-- end notification -->
                  </ul>
                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>
           
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="../library/dist/img/male_user.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo htmlspecialchars($result['fname']." ".$result['lname'],ENT_NOQUOTES); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="../library/dist/img/male_user.png" class="img-circle" alt="User Image">

                  <p>
                    <?php echo htmlspecialchars($result['fname']." ".$result['lname'],ENT_NOQUOTES); ?>
                    <small><?php echo htmlspecialchars($result['genericname1'],ENT_NOQUOTES); ?></small>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">

                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
				  
<input type="button" style="text-align: right;" class="btn btn-default btn-flat" value="<?php echo xl('Change Password'); ?>" onclick="window.location = '<?php echo $landingpage."&password_update=1";?>'"/>

                    </div>
                  <div class="pull-right">
				  <input type="button" style="text-align: right;" class="btn btn-default btn-flat" value="<?php echo xl('Log Out'); ?>" onclick="window.location = 'logout.php'"/>

                     </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      
	  <div  class=" main-section">
       
      <div class="row border-chat">
		<div class="col-md-12 col-sm-12 col-xs-12 first-section">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-xs-6 left-first-section">
					<p>Chat</p>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-6 right-first-section">
					<a href="#"><i class="fa fa-minus" aria-hidden="true"></i></a>
					<a href="#"><i class="fa fa-clone" aria-hidden="true"></i></a>
					<a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
	</div>

	   <table class="table">
            
            
                          
         <input id="txtname" style="width: 150px" type="hidden" name="name" value='<?php echo $patient;  ?>' maxlength="15"/>
                       
            <tr>
                <td colspan="2">
                    <div id="DIV_CHAT" class='chat-section'>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
				<div class="row border-chat">
		<div class="col-md-12 col-sm-12 col-xs-12 third-section">
			<div class="text-bar">
                    <!--<input id="txtmsg" type="text" placeholder="Write messege" name="msg" /><a href="#"></td>-->
					<textarea id="txtmsg" cols='35' class='comments' placeholder="Write messege" name="msg"></textarea>
                <td>
                    <!--<input id="Submit2" type="button" value="Send"  onclick="set_chat_msg()"/></td>-->
					<button type="button" class="btn btn-primary" onclick="set_chat_msg()">Send</button> 
            </tr>
		</div>
		</div>
		
			
			
            <tr>
                <td colspan="1" >
                    </td>
                <td colspan="1">
                </td>
            </tr>
        </table>
    </div>

	  
	  
	  <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.3
      </div>
      <strong>Copyright &copy; 2013-2017 <a href="http://medsmart.com">MedSmart</a>.</strong> All rights
      reserved.
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../library/dist/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../library/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../library/dist/js/app.min.js"></script>
