<?php
// Copyright (C) 2009 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/sql.inc");

// Validation for non-unique external patient identifier.
$alertmsg = '';
if (!empty($_POST["form_pubpid"])) {
  $form_pubpid = trim($_POST["form_pubpid"]);
  $result = sqlQuery("SELECT count(*) AS count FROM patient_data WHERE " .
    "pubpid = '" . formDataCore($form_pubpid) . "'");
  if ($result['count']) {
    // Error, not unique.
    $alertmsg = xl('Warning: Patient ID is not unique!');
  }
}

require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

// here, we lock the patient data table while we find the most recent max PID
// other interfaces can still read the data during this lock, however
// sqlStatement("lock tables patient_data read");
if($_POST['form_rateplan']=="HAIYATH")
{
$result = sqlQuery("SELECT MAX(pid)+1 AS pid FROM patient_camp");
$newpid = 1;
if ($result['pid'] > 1) $newpid = $result['pid'];
setpid($newpid);
}else
{
$result = sqlQuery("SELECT MAX(pid)+1 AS pid FROM patient_data");
$newpid = 1;
if ($result['pid'] > 1) $newpid = $result['pid'];
setpid($newpid);
}
if (empty($pid)) {
  // sqlStatement("unlock tables");
  die("Internal error: setpid($newpid) failed!");
}

// Update patient_data and employer_data:
//
$newdata = array();
if($_POST['form_rateplan']=="HAIYATH")
{
$newdata['patient_camp' ] = array();
}else
{
$newdata['patient_data'] = array();	
}
if($_POST['form_rateplan']=="HAIYATH")
{
$newdata['employer_camp'] = array();
}else
{
	$newdata['employer_data'] = array();	
}
$fres = sqlStatement("SELECT * FROM layout_options " .
  "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' " .
  "ORDER BY group_name, seq");
  if($_POST['form_rateplan']=="HAIYATH")
{
while ($frow = sqlFetchArray($fres)) {
  $data_type = $frow['data_type'];
  $field_id  = $frow['field_id'];
  // $value     = '';
  $colname   = $field_id;
  $tblname   = 'patient_camp';
  if (strpos($field_id, 'em_') === 0) {
    $colname = substr($field_id, 3);
    $tblname = 'employer_camp';
  }

  $value = get_layout_form_value($frow);

  if ($field_id == 'pubpid' && empty($value)) $value = $pid;
  $newdata[$tblname][$colname] = $value;
}
updatePatientData1($pid, $newdata['patient_camp'], true);
updateEmployerData1($pid, $newdata['employer_camp'], true);
}else
{
	while ($frow = sqlFetchArray($fres)) {
  $data_type = $frow['data_type'];
  $field_id  = $frow['field_id'];
  // $value     = '';
  $colname   = $field_id;
  $tblname   = 'patient_data';
  if (strpos($field_id, 'em_') === 0) {
    $colname = substr($field_id, 3);
    $tblname = 'employer_data';
  }

  $value = get_layout_form_value($frow);

  if ($field_id == 'pubpid' && empty($value)) $value = $pid;
  $newdata[$tblname][$colname] = $value;
}
updatePatientData($pid, $newdata['patient_data'], true);
updateEmployerData($pid, $newdata['employer_data'], true);	
		
}

$i1dob = fixDate(formData("i1subscriber_DOB"));
$i1date = fixDate(formData("i1effective_date"));

// sqlStatement("unlock tables");
// end table lock

newHistoryData($pid);
 if($_POST['form_rateplan']=="HAIYATH")
{
newInsuranceData1(
  $pid,
  "primary",
  formData("i1provider"),
  formData("i1policy_number"),
  formData("i1group_number"),
  formData("i1plan_name"),
  formData("i1subscriber_lname"),
  formData("i1subscriber_mname"),
  formData("i1subscriber_fname"),
  formData("form_i1subscriber_relationship"),
  formData("i1subscriber_ss"),
  $i1dob,
  formData("i1subscriber_street"),
  formData("i1subscriber_postal_code"),
  formData("i1subscriber_city"),
  formData("form_i1subscriber_state"),
  formData("form_i1subscriber_country"),
  formData("i1subscriber_phone"),
  formData("i1subscriber_employer"),
  formData("i1subscriber_employer_street"),
  formData("i1subscriber_employer_city"),
  formData("i1subscriber_employer_postal_code"),
  formData("form_i1subscriber_employer_state"),
  formData("form_i1subscriber_employer_country"),
  formData('i1copay'),
  formData('form_i1subscriber_sex'),
  $i1date,
  formData('i1accept_assignment')
);
}else
{
	newInsuranceData(
  $pid,
  "primary",
  formData("i1provider"),
  formData("i1policy_number"),
  formData("i1group_number"),
  formData("i1plan_name"),
  formData("i1subscriber_lname"),
  formData("i1subscriber_mname"),
  formData("i1subscriber_fname"),
  formData("form_i1subscriber_relationship"),
  formData("i1subscriber_ss"),
  $i1dob,
  formData("i1subscriber_street"),
  formData("i1subscriber_postal_code"),
  formData("i1subscriber_city"),
  formData("form_i1subscriber_state"),
  formData("form_i1subscriber_country"),
  formData("i1subscriber_phone"),
  formData("i1subscriber_employer"),
  formData("i1subscriber_employer_street"),
  formData("i1subscriber_employer_city"),
  formData("i1subscriber_employer_postal_code"),
  formData("form_i1subscriber_employer_state"),
  formData("form_i1subscriber_employer_country"),
  formData('i1copay'),
  formData('form_i1subscriber_sex'),
  $i1date,
  formData('i1accept_assignment')
);
}
?>
<html>
<body>
<script language="Javascript">
<?php
if ($alertmsg) {
  echo "alert('$alertmsg');\n";
}
if ($GLOBALS['concurrent_layout']) {
	if($_POST['form_rateplan']=="HAIYATH"){
		echo "window.location='$rootdir/new/new.php';\n";
		}else
	{
  echo "window.location='$rootdir/patient_file/summary/demographics.php?" .
    "set_pid=$pid&is_new=1';\n";
	}
} else {
	if($_POST['form_rateplan']=="HAIYATH"){
		echo "window.location='$rootdir/new/new.php';\n";
	}else
	{
  echo "window.location='$rootdir/patient_file/patient_file.php?set_pid=$pid';\n";
	}
}
?>
</script>

</body>
</html>

