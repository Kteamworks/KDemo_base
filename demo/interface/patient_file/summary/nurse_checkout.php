<?php
 require_once("../../globals.php");
$user =$_SESSION["authUser"];  
$enc=$_SESSION['encounter'];
$s=sqlStatement("select username,newcrop_user_role from users where username='".$user."'");
$s1=  sqlFetchArray($s);

sqlStatement("UPDATE form_encounter SET nurse_out_time=NOW() where encounter= '".$enc."'");
header("location:../../main/finder/p_dynamic_finder.php");
?>