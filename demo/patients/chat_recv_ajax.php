<?php

     session_start();

//landing page definition -- where to go if something goes wrong
$landingpage = "index.php?site=".$_SESSION['site_id'];
//

// kick out if patient not authenticated
if ( isset($_SESSION['pid'])) {
  $pid = $_SESSION['pid'];
}

    $pid_dr = $_GET["name"];
	
	$idd = $pid_dr  +  $pid ; 

     require_once('dbconnect.php');

     db_connect();
     
     //$sql = "SELECT *, date_format(chatdate,'%d-%m-%Y %r') as cdt  from chat where pid ='$idd'  order by ID desc limit 200";
	 $sql = "SELECT *  from chat where pid ='$idd'  order by ID desc limit 200";
     $sql = "SELECT * FROM (" . $sql . ") as ch order by ID";
     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
     
     // Update Row Information
     $msg="<table border='0' style='font-size: 10pt; color: blue; font-family: verdana, arial;'>";
     while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
     {
               if($line["username"]=='Health-Care'){
				   $color = 'red';
			   }
			   else{ $color = 'blue';}
			   $msg = $msg . "<tr><td>" . $line["cdt"] . "&nbsp;</td>" .
                "<th align='left'> <font color='".$color . "'>" . $line["username"] . " :</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>" .
				
                "<td>"  . $line["msg"] . "</td></tr>";
     }
     $msg=$msg . "</table>";
     
     echo $msg;

?>





