<?php
 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
 if ($GLOBALS['concurrent_layout'] && isset($_GET['mrn'])) {
  include_once("$srcdir/pid.inc");
  $mrn = $_GET['mrn'];
  $qry = sqlQuery("select * from patient_data where genericname1='$mrn'");
  setpid($qry['pid']);
  $pid = $qry['pid'];
  $fname = $qry['fname'];
  $lname = $qry['lname'];
  $dob = $qry['DOB'];
  $gname = $qry['genericname1'];
  $languange=$qry['language'];
  $rows = array();

    $result4 = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));

	  while($rowresult4 = sqlFetchArray($result4)) {
    $rows[] = $rowresult4;
}
if(sqlNumRows($result4)>0) {
$data = array();
$data['fruits'] = array($pid,$fname,$lname,$gname,$dob,$language);
$data['animals'] = $rows;
echo json_encode($data);


  }
 }
 ?>