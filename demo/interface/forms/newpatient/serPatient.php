<?php
 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
 if ($GLOBALS['concurrent_layout'] && isset($_GET['mrn'])) {
  include_once("$srcdir/pid.inc");
  $mrn = $_GET['mrn'];
  $qry = sqlQuery("select * from patient_data where genericname1='$mrn'");
  setpid($qry['pid']);
echo json_encode(array($qry['pid'], $qry['fname'],$qry['lname'],$qry['genericname1'],$qry['DOB']));
 }
 ?>