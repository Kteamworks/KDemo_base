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
require_once("../../controllers/NotificationController.php");
require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");

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
NotificationController::create($pid, $_SESSION['authUserID'], '1');
updatePatientData($pid, $newdata['patient_data'], true);

$user = 'kavaii';
$password = '12345';
$sender_id = 'KAVAII';//helloz welcom FAPcop abhiii'hiiiii
$sender = $_POST['form_phone_cell'];//9673776599 9320491970
$genericid=getPatientData($pid, "genericname1,facility_id");
$facility=sqlStatement("select name from facility where id='".$genericid['facility_id']."'");
$facility1=sqlFetchArray($facility);
$facility_name=$facility1['name'];
$regid=$genericid['genericname1'];
$msg = 'Thank you for Registering with "'.$facility_name.'" Hospital. Your Registration Id is "'.$regid.'" ';
$priority = 'sdnd';
$sms_type = 'normal';
$data='user='.$user.'&pass='.$password.'&sender='.$sender_id.'&phone='.$sender.'&text='.$msg.'&stype='.$sms_type.'&priority=sdnd'; 
$ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
			//echo var_dump($data);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//echo var_dump($ch);
try {
	$response = curl_exec($ch);
			//echo var_dump($ch);
    curl_close($ch);
			//echo var_dump($response);
			//echo 'Message has been sent.';
	}catch(Exception $e){
	echo 'Message: ' .$e->getMessage();
			}
updateEmployerData($pid, $newdata['employer_data'], true);	
if($_POST['form_visit_category']!=null && $_POST['form_doctor']!=null )
{
	$encounter = generate_id();
	$date= date("Y-m-d H:i:s"); 
	$onset_date= date("Y-m-d H:i:s"); 
	$reason='';
	$facility='Demo';
	$pc_catid=$_POST['form_visit_category'];
	$facility_id=4;
	$billing_facility=4;
	$provider_id=$_POST['form_doctor'];
    addForm($encounter, "New Patient Encounter",
    sqlInsert("INSERT INTO form_encounter SET " .
      "date = '" . add_escape_custom($date) . "', " .
      "onset_date = '" . add_escape_custom($onset_date) . "', " .
      "reason = '" . add_escape_custom($reason) . "', " .
      "facility = '" . add_escape_custom($facility) . "', " .
      "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
      "facility_id = '" . add_escape_custom($facility_id) . "', " .
      "billing_facility = '" . add_escape_custom($billing_facility) . "', " .
      "sensitivity = '" . add_escape_custom($sensitivity) . "', " .
      "referral_source = '" . add_escape_custom($referral_source) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "tpa_id = '" . add_escape_custom($tpaid) . "', " .
	  "package = '" . add_escape_custom($package) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'"),
      "newpatient", $pid, $userauthorized, $date);
	$p=sqlQuery("select date from patient_data where pid='$pid'");
	sqlFetchArray($p);
	$regdate=$p['date'];
	sqlInsert("INSERT INTO billing_main_copy SET " .
      "regdate = '" . add_escape_custom($regdate) . "', " .
      "facility_id = '" . add_escape_custom($facility_id) . "', " . 
      "pid = '" . add_escape_custom($pid) . "', " .
	  "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "encounterdt = now() , ".
	  "tpa_id_pri = '" . add_escape_custom($tpaid) . "' ") ;     	
	  //sqlStatement("update insurance_data set encounter=?,provider=? where pid=?",array($encounter,$tpaid,$pid));
    $row1=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price,username from codes a,prices b, users c where a.id=b.pr_id and a.code_text=c.username and b.pr_level='standard'and  c.id='".$provider_id."'");
	$today = date("Y-m-d H:i:s"); 
	$time= date("H:i:s");
	$day=sqlStatement("select dayname('$today') day");
	$days=sqlFetchArray($day);
	$dayy=$days['day'];
	
	
		if(!($pc_catid==5 || $pc_catid==12 || $pc_catid==16 || $pc_catid==17))
	{
	$patient=getPatientData($pid, "rateplan");
    $rate=$patient['rateplan'];
	if($rate=="HosInsurance")
	{
    $inc1=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price,username from codes a,prices b, users c where a.id=b.pr_id and a.code_text=c.username and b.pr_level='HosInsurance' and  c.id='".$provider_id."'");
	$inc2=  sqlFetchArray($inc1);
	$servicegrpid=$inc2['code_type'];
	$serviceid=$inc2['service_id'];
  	$code=$inc2['code'];
	$codetext=$inc2['code_text'];
	$codetype="Doctor Charges";
  	$billed=0;
  	$units=1;
  	$fee=$inc2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="HosInsurance";
	$onset_date1=date('Y-m-d H:i:s');

	if($pc_catid==13)
		 {
			$ct="CASUALTY CONSULTATION (DAY)"; 	 
		    $cas5=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct."' and a.code_type=6 and b.pr_level='HosInsurance'");
			$cas6=sqlFetchArray($cas5);
			$codetype="Services"; 
			$servicegrpid=$cat6['code_type'];
	        $serviceid=$cat6['service_id'];
			$codetext="CASUALTY CONSULTATION (DAY)"; 
			$code=$codetext;
			$fee=$cas6['pr_price'];
		 }
		 if($pc_catid==15)
		 {
			$ct1="CASUALTY CONSULTATION (NIGHT)";
		    $cas7=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct1."' and a.code_type=6 and b.pr_level='HosInsurance'");
			$cas8=sqlFetchArray($cas7);
			$codetype="Services"; 
			$servicegrpid=$cat8['code_type'];
	        $serviceid=$cat8['service_id'];
			$codetext="CASUALTY CONSULTATION (NIGHT)"; 
			$code=$codetext;
			$fee=$cas8['pr_price'];
		 }
	}
	else
    {
		
	$row2=  sqlFetchArray($row1);
  	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$codetype="Doctor Charges";
  	$billed=0;
	$servicegrpid=$row2['code_type'];
	$serviceid=$row2['service_id'];
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Default";
	$onset_date1=date('Y-m-d H:i:s');

	if($pc_catid==13)
		 {
			$ct="CASUALTY CONSULTATION (DAY)"; 	 
		    $cas1=sqlStatement("Select  a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct."' and a.code_type=6 and b.pr_level='standard'");
			$cas2=sqlFetchArray($cas1);
			$codetype="Services"; 
				$servicegrpid=$cas2['code_type'];
	          $serviceid=$cas2['service_id'];
			$codetext="CASUALTY CONSULTATION (DAY)"; 
			$code=$codetext;
			$fee=$cas2['pr_price'];
		 }
		 if($pc_catid==15)
		 {
			$ct1="CASUALTY CONSULTATION (NIGHT)";
		    $cas3=sqlStatement("Select  a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct1."' and a.code_type=6 and b.pr_level='standard'");
			$cas4=sqlFetchArray($cas3);
			$codetype="Services"; 
			$servicegrpid=$cas4['code_type'];
	        $serviceid=$cas4['service_id'];
			$codetext="CASUALTY CONSULTATION (NIGHT)"; 
			$code=$codetext;
			$fee=$cas4['pr_price'];
		 }
	}	
	if($code!=null)
	{
    sqlInsert("INSERT INTO billing SET " .
      "date = '" . add_escape_custom($onset_date1) . "', " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date1) . "', " .
	  "servicegrp_id = '" . add_escape_custom($servicegrpid) . "', " .
      "service_id = '" . add_escape_custom($serviceid) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
      "code = '" . add_escape_custom($code) . "', " .
      "code_text = '" . add_escape_custom($codetext) . "', " .
      "units = '" . add_escape_custom($units) . "', " .
      "billed = '" . add_escape_custom($billed) . "', " .
      "fee = '" . add_escape_custom($fee) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "modifier = '" . add_escape_custom($modif) . "', " .
	  "authorized = '" . add_escape_custom($authrzd) . "', " .
	  "activity = '" . add_escape_custom($act) . "', " .
	  "groupname = '" . add_escape_custom($grpn) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'");
	  
	  sqlQuery("Update billing_main_copy set total_charges=total_charges + ? where encounter=?",array($fee,$encounter));   
	}
	}
}
		
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

