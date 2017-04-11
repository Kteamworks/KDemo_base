<?php
/**
 *
 * Copyright (C) 2012-2013 Naina Mohamed <naina@capminds.com> CapMinds Technologies
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Naina Mohamed <naina@capminds.com>
 * @link    http://www.open-emr.org
 */
 
  //SANITIZE ALL ESCAPES
 $sanitize_all_escapes=$_POST['true'];

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=$_POST['false'];
  
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}


$id = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$pid=$_POST['pid'];
$encounter=$_POST['encounter'];
$sets = "pid = '" . add_escape_custom($_POST["pid"]) . "',
  groupname = '" . $_SESSION["authProvider"] . "',
  user = '" . $_SESSION["authUser"] . "',
  authorized = $userauthorized, activity=1, date = NOW(),
  provider          = '" . add_escape_custom($_POST["provider"]) . "',
  client_name          = '" . add_escape_custom($_POST["client_name"]) . "',
  admit_to_ward          = '" . add_escape_custom($_POST["admit_to_ward"]) . "',
  admit_to_bed          = '" . add_escape_custom($_POST["admit_to_bed"]) . "',
  admit_date          = '" . add_escape_custom(date('Y-m-d H:i:s', strtotime($_POST['admit_date']))) . "',
  discharge_date          = '" . add_escape_custom(date('Y-m-d H:i:s', strtotime($_POST['discharge_date']))) . "',
  status          = '" . add_escape_custom("admit") . "',
   encounter          = '" . add_escape_custom($_POST["encounter"]) . "',
diagnosis                   = '" . add_escape_custom($_POST["diagnosis"]) ."'";


  if (empty($id)) {
	 if($admit_to_ward=='Private')
	 {
		  $pr=sqlStatement("select option_id, concat('SP',substring(option_id,2,instr(option_id,',')-2)) Bed1,concat('SP-',substring(option_id,instr(option_id,',')+1)) Bed2 from list_options where option_id like '".$adm_to. "'");
	  $pr1=sqlFetchArray($pr);
	  
	  $bed1=$pr1['Bed1'];
	  $bed2=$pr1['Bed2'];
	  $bbed1=sqlStatement("SELECT is_default from list_options where option_id= '".$bed1. "'");
	  $bbbed1=sqlFetchArray($bbed1);
	  $status1=$bbbed1['is_default'];
	  $bbed2=sqlStatement("SELECT is_default from list_options where option_id= '".$bed2. "'");
	  $bbbed2=sqlFetchArray($bbed2);
	  $status2=$bbbed2['is_default'];
	  if($status1==1)
		{
			   $address = "{$GLOBALS['rootdir']}/forms/admit/new.php";
			  $message="The Bed $bed1 which is Selected has already been occupied";
			  echo"<script type='text/javascript'>alert('$message');top.restoreSession();window.location='$address';</script>";
			  
			 
		}else if($status2==1)
		{
			 $address = "{$GLOBALS['rootdir']}/forms/admit/new.php";
			$message="The Bed $bed2 which is Selected has already been occupied";
			echo"<script type='text/javascript'>alert('$message');top.restoreSession();window.location='$address';</script>";
			
		}else
		{
      sqlStatement("UPDATE list_options SET is_default=1 WHERE option_id= '".$adm_to. "'");
	  sqlStatement("UPDATE list_options SET is_default=1 WHERE option_id= '".$bed1. "'");
	  sqlStatement("UPDATE list_options SET is_default=1 WHERE option_id= '".$bed2. "'");
	  $adm_to=add_escape_custom($_POST["adm_to"]);
      $newid = sqlInsert("INSERT INTO t_form_admit SET $sets");
      sqlStatement("UPDATE list_options SET is_default=1 WHERE option_id= '".$adm_to. "'");
	  $pid=$_POST['pid'];
      $encounter=$_POST['encounter'];
      addForm($encounter, "Admission", $newid, "admit", $pid, $userauthorized);
	  $patient=getPatientData($pid, "rateplan");
    $rate=$patient['rateplan'];
    $codetype=9;
    $admit_to_ward=$_POST['admit_to_ward'];
   if($rate=="TPAInsurance")
	{
	$row1=sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='Insurance' and a.code like '".$admit_to_ward."%' and b.pr_price!=0");
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
  	$codetype="Ward Charges";
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Insurance";
	$pid=$_POST['pid'];
    $encounter=$_POST['encounter'];
	$onset_date=date('Y-m-d H:i:s');
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	  "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
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
	else{
    $row1=sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='standard' and a.code like '".$admit_to_ward."%' and b.pr_price!=0");
	
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$codetype="Ward Charges";
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Default";
	$onset_date=date('Y-m-d H:i:s');
	$pid=$_POST['pid'];
    $encounter=$_POST['encounter'];
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	   "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
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
		 
	 }	else
	 {		 
   $pid=$_POST['pid'];
   $encounter=$_POST['encounter'];
    $patient=getPatientData($pid, "rateplan");
    $rate=$patient['rateplan'];
    $codetype=9;
    $admit_to_ward=$_POST['admit_to_ward'];
   if($rate=="TPAInsurance")
	{
	$row1=sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='Insurance' and a.code like '".$admit_to_ward."%' and b.pr_price!=0");
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$codetype="Ward Charges";
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$pid=$_POST['pid'];
$encounter=$_POST['encounter'];
	$modif="";
	$act=1;
	$grpn="Insurance";
	$onset_date=date('Y-m-d H:i:s');
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	   "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
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
	else{
    $row1=sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='standard' and a.code like '".$admit_to_ward."%' and b.pr_price!=0");
	
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
  	$codetype="Ward Charges";
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Default";
	$onset_date=date('Y-m-d H:i:s');
	$pid=$_POST['pid'];
    $encounter=$_POST['encounter'];
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	   "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
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
	  
  $adm_to=add_escape_custom($_POST["adm_to"]);
  $newid = sqlInsert("INSERT INTO t_form_admit SET $sets");
  
  sqlStatement("UPDATE list_options SET is_default=1 WHERE option_id= '".$adm_to. "'");
  $pid=$_POST['pid'];
  $encounter=$_POST['encounter'];
 addForm($encounter, "Admission", $newid, "admit", $pid, $userauthorized);
}
  }
else {
  sqlStatement("UPDATE form_transfer_summary SET $sets WHERE id = '". add_escape_custom("$id"). "'");
}

$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>

