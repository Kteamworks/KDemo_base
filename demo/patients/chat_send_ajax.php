<?php

      session_start();

//landing page definition -- where to go if something goes wrong
$landingpage = "index.php?site=".$_SESSION['site_id'];
//

// kick out if patient not authenticated
if ( isset($_SESSION['pid']) ) {
  $pid_pt = $_SESSION['pid'];
}

   
    
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
 
     $result = sqlQuery("select fname from patient_data where pid = $pid");
	 $fname = $result['fname'];
	 if($fname == ''){
		 $fname = 'Health-Care';
	 }
 
     require_once('dbconnect.php');

     db_connect();

     $msg = $_GET["msg"];
     $dt = date("Y-m-d H:i:s");
     $pid_dr = $_GET["name"];
	 
	 
		 $f_id = $pid_dr  +  $pid_pt;
	     
	 

     $sql="INSERT INTO chat(USERNAME,CHATDATE,MSG,pid) " .
          "values(" . quote($fname) . "," . quote($dt) . "," . quote($msg) . "," . quote($f_id) . ");";

          //echo $sql;

     $result = mysql_query($sql);
     if(!$result)
     {
        throw new Exception('Query failed: ' . mysql_error());
        exit();
     }

?>





