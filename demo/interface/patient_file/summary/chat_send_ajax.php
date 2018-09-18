<?php


  session_start();
		
	

     require_once('dbconnect.php');

     db_connect();

     $msg = $_GET["msg"];
     $dt = date("Y-m-d H:i:s");
     $user = $_GET["user_name"];
	 $pid = $_GET["txtname"];
	 $his = 1 ; 
	 
	 
		 //$f_id = $pid_dr  +  $pid_pt;
	     
	 

     $sql="INSERT INTO chat(USERNAME,CHATDATE,MSG,pid,who) " .
          "values(" . quote($user) . "," . quote($dt) . "," . quote($msg) . "," . quote($pid) . "," . quote($his) . ");";

          //echo $sql;

     $result = mysql_query($sql);
     if(!$result)
     {
        throw new Exception('Query failed: ' . mysql_error());
        exit();
     }

?>





