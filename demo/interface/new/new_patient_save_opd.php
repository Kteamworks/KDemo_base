<?php
require_once("../globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/forms.inc");

require_once("$srcdir/encounter.inc");

// Validation for non-unique external patient identifier.
if (!empty($_POST["pubpid"])) {
  $form_pubpid = trim($_POST["pubpid"]);
  $result = sqlQuery("SELECT count(*) AS count FROM patient_data WHERE " .
    "pubpid = '$form_pubpid'");
  if ($result['count']) {
    // Error, not unique.
    require_once("newopd.php");
    exit();
  }
}

require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");

//here, we lock the patient data table while we find the most recent max PID
//other interfaces can still read the data during this lock, however
sqlStatement("lock tables patient_data read");

$result = sqlQuery("select max(pid)+1 as pid from patient_data");

// TBD: This looks wrong to unlock the table before we have added our
// patient with its newly allocated pid!
//
sqlStatement("unlock tables");
//end table lock
$newpid = 1;

if ($result['pid'] > 1)
  $newpid = $result['pid'];

setpid($newpid);

if($pid == NULL) {
  $pid = 0;
}

// what do we set for the public pid?
if (isset($_POST["pubpid"]) && ($_POST["pubpid"] != "")) {
  $mypubpid = $_POST["pubpid"];
} else {
  $mypubpid = $pid;
}

if ($_POST['form_create']) {
	
	
 
	
	
  $form_fname = ucwords(trim($_POST["fname"]));
  $form_lname = ucwords(trim(''));
  $form_mname = ucwords(trim(''));
  $form_mb = ucwords(trim($_POST["mb"]));
  $form_age=trim($_POST['age']);
  //$form_sex = trim($_POST["sex"]);
  // ===================
  // DBC SYSTEM WAS REMOVED
  $form_sex               = trim($_POST["sex"]);
  $form_dob               = trim($_POST["DOB"]) ;
  $form_street            = '' ;
  $form_city              = 'Bangalore' ;
  $form_postcode          = '' ;
  $form_countrycode       = '' ;
  // EOS DBC
  // ===================

$date1 = strtotime($form_dob); // date when age was recorded 
$time1 = $form_age*31556926; // calculating age in seconds 
$dob1 = $date1 - $time1; // getting the timestamp for his / her date of birth 
$form_dob = date("Y-m-d",$dob1); // getting the date of birth here  
  newPatientData(
    $_POST["db_id"],
    $_POST["title"],
    $form_fname,
    $form_lname,
    $form_mname,
    $form_sex, // sex
    $form_dob, // dob
    $form_street, // street
    $form_postcode, // postal_code
    $form_city, // city
    "", // state
    $form_countrycode, // country_code
    "", // ss
    "", // occupation
    "", // phone_home
    "", // phone_biz
    "", // phone_contact
    "", // status
    "", // contact_relationship
    "", // referrer
    "", // referrerID
    "", // email
    "", // language
    "", // ethnoracial
    "", // interpreter
    "", // migrantseasonal
    "", // family_size
    "", // monthly_income
    "", // homeless
    "", // financial_review
    "$mypubpid",
    $pid,
    "", // providerID
    "", // genericname1
    "", // genericval1
    "", // genericname2
    "", // genericval2
    $form_mb, // phone_cell
    "", // hipaa_mail
    "", // hipaa_voice
    0,  // squad
    0,  // $pharmacy_id = 0,
    "", // $drivers_license = "",
    "", // $hipaa_notice = "",
    "", // $hipaa_message = "",
    $_POST['regdate']
  );

  newEmployerData($pid);
  newHistoryData($pid);
  newInsuranceData($pid, "primary");
  newInsuranceData($pid, "secondary");
  newInsuranceData($pid, "tertiary");

  // Set referral source separately because we don't want it messed
  // with later by newPatientData().
  if ($refsource = trim($_POST["refsource"])) {
    sqlQuery("UPDATE patient_data SET referral_source = '$refsource' " .
      "WHERE pid = '$pid'");
  }
 sqlQuery("UPDATE patient_data SET opd = 1 where pid='$pid'");
 $generic = sqlQuery("select genericname1 from patient_data where pid='$pid'");
  $gch_id = $generic['genericname1'];
   $provider_id = $_SESSION['authUserID'] ;
  $encounter = generate_id();
  $pos_code='';
  $date=date("Y-m-d");
  $pc_catid=11;
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
      "pos_code = '" . add_escape_custom($pos_code) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'"),
    "newpatient", $pid, $userauthorized, $date);
/*	
  //function addBilling($encounter_id, $code_type, $code, $code_text, $pid,
  $authorized="0"; $modifier=""; $units=""; $fee="0.00";
  $ndc_info=''; $justify=''; $billed=0; $notecodes=''; $pricelevel='';
  $encounter_id=0;
  $pid=0;
  $code_type='';
  $code='';
  $code_text='';
  $provider='';
  $sql = "insert into billing (date, encounter, code_type, code, code_text, " .
    "pid, authorized, user, groupname, activity, billed, provider_id, " .
    "modifier, units, fee, ndc_info, justify, notecodes) values (" .
    "NOW(), ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?)";
  sqlInsert($sql, array($encounter_id,$code_type,$code,$code_text,$pid,$authorized,
    $_SESSION['authId'],$_SESSION['authProvider'],$billed,$provider,$modifier,$units,$fee,
    $ndc_info,$justify,$notecodes )); */


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
  //echo "window.location='a.php";
//header('location:../main/finder/dynamic_finder_ph.php');
//header('location:../forms/fee_sheet_ph/medSale.php');
 echo "window.location='../forms/fee_sheet_ph/other_medSale.php?set_pid=$gch_id';\n";

   // "set_pid=$pid&is_new=1';\n";
} else {
  echo "window.location='$rootdir/patient_file/patient_file.php?set_pid=$pid';\n";
}
?>
</script>

</body>
</html>
