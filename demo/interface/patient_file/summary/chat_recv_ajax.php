<?php

     session_start();

//landing page definition -- where to go if something goes wrong
//$landingpage = "index.php?site=".$_SESSION['site_id'];
//

// kick out if patient not authenticated
/*
if ( isset($_SESSION['pid'])) {
  $pid = $_SESSION['pid'];
} */

    $username = $_GET["name"];
	$pid = $_GET["txtid"];
	
	//$idd = $pid_dr  +  $pid ; 

     require_once('dbconnect.php');

     db_connect();
     
     //$sql = "SELECT *, date_format(chatdate,'%d-%m-%Y %r') as cdt  from chat where pid ='$idd'  order by ID desc limit 200";
	 $sql = "SELECT *  from chat where  pid='$pid' order by ID desc limit 200";
     $sql = "SELECT * FROM (" . $sql . ") as ch order by ID";
     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
     
     // Update Row Information
     $msg="<table border='0' style='font-size: 10pt; color: blue; font-family: verdana, arial;width:100%;'>";
     while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
     {
               if($line["who"]==1){
				   $color = 'blue';
				   //$align = 'right'
			   }
			   else{ $color = 'orange';}
			   	   if(strpos($line['msg'],'https')!== false) {
				   $msg = $msg . "<tr bgcolor='white'><td>" . $line["cdt"] . "&nbsp;</td></tr>" .
                "<tr><th align='left'> <font color='".$color . "'>" . $line["username"] . " </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>".
				"<tr><td><a href =".$line["msg"]." target='_blank'>  <textarea rows='2' cols='50' style='color:". $color ." ' >"  . $line["msg"] . "</textarea><a></td></tr></tr>";
				   
			   } 
			    else {
				
				$msg = $msg . "<tr bgcolor='white'><td>" . $line["cdt"] . "&nbsp;</td></tr>" .
                "<tr><th align='left'> <font color='".$color . "'>" . $line["username"] . " </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>".
				"<tr><td><textarea rows='2' cols='50' style='color:". $color ." ' >"  . $line["msg"] . "</textarea></td></tr>";
				
     }
     }
     $msg=$msg . "</table>";
     
     echo $msg;

?>





