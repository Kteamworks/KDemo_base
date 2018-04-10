<?php
/**
 * Encounter form save script.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/payment.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sl_eob.inc.php");
require_once("$srcdir/invoice_summary.inc.php");
//require_once("../../custom/code_types.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/encounter_events.inc.php");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");

if ($mode == 'new' && empty($pid))
{
	 die(xl("Internal error: Please select the patient"));
}

$date             = (isset($_POST['form_date']))            ? $_POST['form_date'] : '';
if($date) {
$date = date("Y-m-d h:i:s", strtotime($_POST['form_date']));
}
$onset_date       = (isset($_POST['form_onset_date']))      ? $_POST['form_onset_date'] : '';
$sensitivity      = (isset($_POST['form_sensitivity']))     ? $_POST['form_sensitivity'] : '';
$pc_catid         = (isset($_POST['pc_catid']))             ? $_POST['pc_catid'] : '';
$facility_id      = (isset($_POST['facility_id']))          ? $_POST['facility_id'] : '';
$billing_facility = (isset($_POST['billing_facility']))     ? $_POST['billing_facility'] : '';
$reason           = (isset($_POST['reason']))               ? $_POST['reason'] : '';
$mode             = (isset($_POST['mode']))                 ? $_POST['mode'] : '';
$provider_id      = (isset($_POST['form_provider'])) ? $_POST['form_provider'] : '';
$referral_source  = (isset($_POST['form_referral_source'])) ? $_POST['form_referral_source'] : '';
$tpaid            = (isset($_POST['instpa'])) ? $_POST['instpa'] : '';
$rateplan           = (isset($_POST['rateplan'])) ? $_POST['rateplan'] : '';
$package          = (isset($_POST['package'])) ? $_POST['package'] : '';

$facilityresult = sqlQuery("select name FROM facility WHERE id = ?", array($facility_id));
$facility = $facilityresult['name'];


if ($GLOBALS['concurrent_layout'])
  $normalurl = "patient_file/encounter/encounter_top.php";
else
  $normalurl = "$rootdir/patient_file/encounter/patient_encounter.php";

$newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 if($newcrop_user_role['newcrop_user_role']=='erxdoctor') {
	
	 $normalurl = "patient_file/encounter/load_form.php?formname=procedure_order";
 }
  if($newcrop_user_role['newcrop_user_role']=='erxrep') {
	  
	 $normalurl = "main/finder/dynamic_finder.php";
	 
 }
 
 if($newcrop_user_role['newcrop_user_role'] == 'erxnurse') { 
sqlStatement("UPDATE form_encounter SET nurse_out_time=NOW() where encounter= '".$encounter."'");
$url="../../main/finder/p_dynamic_finder.php";
header('Location: '.$url);
 $_SESSION['nurseVisit'] = $encounter; $_SESSION['LAST_ACTIVITY_nurse'] = time();
}

if($pc_catid==16)
{
	
	$normalurl = "patient_file/encounter/load_form.php?formname=procedure_order";

}
if($pc_catid==12 && ($newcrop_user_role['newcrop_user_role']!='erxrep'))
{
	
	$normalurl = "patient_file/encounter/load_form.php?formname=admit";

}

$nexturl = $normalurl;

if ($mode == 'new')
{
 
	$encounter = generate_id();
    addForm($encounter, "New Patient Encounter",
    sqlInsert("INSERT INTO form_encounter SET " .
      "date = '" . add_escape_custom($date) . "', " .
      "onset_date = '" . add_escape_custom($onset_date) . "', " .
      "reason = '" . add_escape_custom($reason) . "', " .
	  "user = '" . add_escape_custom($_SESSION['authUser']) . "', " .
      "facility = '" . add_escape_custom($facility) . "', " .
      "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
      "facility_id = '" . add_escape_custom($facility_id) . "', " .
      "billing_facility = '" . add_escape_custom($billing_facility) . "', " .
      "sensitivity = '" . add_escape_custom($sensitivity) . "', " .
      "referral_source = '" . add_escape_custom($referral_source) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "tpa_id = '" . add_escape_custom($tpaid) . "', " .
	  "rateplan = '" . add_escape_custom($rateplan) . "', " .
	  "package = '" . add_escape_custom($package) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'"),
      "newpatient", $pid, $userauthorized, $date);
	   sqlQuery("Update patient_data set rateplan=? where pid=?",array($rateplan,$pid)); 
	   sqlQuery("Update insurance_data set provider=? where pid=?",array($tpaid,$pid));
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
	if(isset($_POST['pay']))
	{
		$billed=1;
	}else
	{
  	$billed=0;
	}
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
	if(isset($_POST['pay']))
	{
  	$billed=1;
	}else
	{
		$billed=0;
	}
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
	  
if (acl_check('acct', 'rep')) {	
if(isset($_POST['pay'])){
  $time = date('Y-m-d H:i:s');
  $payment_id = idSqlStatement("insert into ar_session set "    .
					"payer_id = ?"       .
					", patient_id = ?"   .
					", user_id = ?"     .
					", closed = ?"      .
					", reference = ?"   .
					", check_date =  now() , deposit_date = now() "	.
					",  pay_total = ?"    .
					", payment_type = 'patient'" .					
					", adjustment_code = 'patient_payment'" .
					", post_to_date = now() " .
					", payment_method = ?",
					array(0,$pid,$_SESSION['authUserID'],0,'',$fee,'cash'));
					
      $query = "INSERT INTO ar_activity ( " .
        "pid, encounter, sequence_no,code_type,code, modifier, payer_type, post_user, post_time, " .
        "session_id, pay_amount, account_code " .
        ") VALUES ( " .
        "?, " .
        "?, " .
		"'1', " .
		"?, " .
        "?, " .
        "'', " .
        "'0', " .
        "?, " .
        "?, " .
        "?, " .
        "?, " .
        "'PP' " .
        ")";
      sqlStatement($query, array($pid,$encounter,$codetype,$code,$_SESSION['authUserID'],$time,$payment_id,$fee) );
	  
    
	  $query1 = "INSERT INTO payments ( " .
        "pid, encounter, dtime, user, method, source, amount1,amount2, " .
        "towards " .
        ") VALUES ( " .
        "?, " .
        "?, " .
        "?, " .
        "?, " .
        "?, " .
        "?, " .
        "?, " .
		 "?, " .
        "?" .
         ")";
      sqlStatement($query1, array($pid,$encounter,$time ,$_SESSION['authUser'],'cash','',$fee,0,2) );
	  
	    // Re-fetch payment info.
  $payrow = sqlQuery("SELECT " .
    "SUM(amount1) AS amount1, " .
    "SUM(amount2) AS amount2, " .
	"receipt_id,".
    "MAX(method) AS method, " .
    "MAX(source) AS source, " .
    "MAX(dtime) AS dtime, " .
    // "MAX(user) AS user " .
    "MAX(user) AS user, " .
	"MAX(towards) AS towards, " .
    "MAX(encounter) as encounter ".
    "FROM payments WHERE " .
    "pid = ? AND dtime = ?", array($pid,$time) );

  $billid=sqlQuery("SELECT * from billing where pid=? and encounter=?",array($pid,$payrow['encounter']));

  // Now proceed with printing the receipt.
?>

<title><?php echo xlt('Receipt for Payment'); ?></title>
<script type="text/javascript" src="../../library/dialog.js"></script>
<style  type="text/css">
table {
  border-collapse: collapse;
page-break-after:auto;
  
  }


@media print {
	.title {
		visibility: hidden;
	}
	#margindiv{
		margin:0px;
		width:0px;
		}
    .pagebreak {
        page-break-after: always;
        border: none;
        visibility: hidden;
    }

	#superbill_description {
		visibility: hidden;
	}

	#report_parameters {
		visibility: hidden;
	}
    #superbill_results {
       margin-top: 0px;
    }
}

@media screen {
	.title {
		visibility: visible;
	}
	#superbill_description {
		visibility: visible;
	}
    .pagebreak {
        width: 100%;
        border: 2px dashed black;
    }
	#report_parameters {
		visibility: visible;
	}
}
#superbill_description {
   margin: 10px;
}
#superbill_startingdate {
    margin: 0px;
}
#superbill_endingdate {
    margin: 0px;
}

#superbill_patientdata {
}
#superbill_patientdata h1 {
    font-weight: bold;
    font-size: 0.4em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata {
    margin-top: 10px;
}
#superbill_insurancedata h1 {
    font-weight: bold;
    font-size: 0.4em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata h2 {
    font-weight: bold;
    font-size: 0.8em;
    margin: 0px;
    padding: 0px;
    width: 100%;
    background-color: #eee;
}
#superbill_billingdata {
    margin-top: 3px;
}
#superbill_billingdata h1 {
    font-weight: bold;
    font-size: 0.4em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_signature {
}
#superbill_logo {
}

@page  
{ 
    size: auto;   /* auto is the initial value */ 

    /* this affects the margin in the printer settings */ 
    margin: 3mm 5mm 10mm 10mm;  
} 

body  
{ 
    /* this affects the margin on the content before sending to printer */ 
    margin: 0px;  
} 
@media print {
  #printPageButton {
    display: none;
  }
}
</style>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
 // Process click on Print button.
 function printme() {
	 var restoreoriginalpage = document.body.innerHTML;
  var divstyle = document.getElementById('hideonprint').innerHTML;
  divstyle.display = 'none';
  document.body.innerHTML = restoreoriginalpage;
 window.print();
  
 }
 // Process click on Delete button.
 function deleteme() {
  dlgopen('deleter.php?payment=<?php echo $payment_key ?>', '_blank', 500, 450);
  return false;
 }
 // Called by the deleteme.php window on a successful delete.
 function imdeleted() {
  window.close();
 }

 // Called to switch to the specified encounter having the specified DOS.
 // This also closes the popup window.
 function toencounter(enc, datestr, topframe) {
  topframe.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
  // Hard-coding of RBot for this purpose is awkward, but since this is a
  // pop-up and our openemr is left_nav, we have no good clue as to whether
  // the top frame is more appropriate.
  topframe.left_nav.forceDual();
  topframe.left_nav.setEncounter(datestr, enc, '');
  topframe.left_nav.setRadio('RBot', 'enc');
  topframe.left_nav.loadFrame('enc2', 'RBot', 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
<?php } else { ?>
  topframe.Title.location.href = 'encounter/encounter_title.php?set_encounter='   + enc;
  topframe.Main.location.href  = 'encounter/patient_encounter.php?set_encounter=' + enc;
<?php } ?>
  window.close();
 }

</script>
</head>
<!--<p style="margin-top:110px"></p>-->
<body class="body_top"  bgcolor='#ffffff'>
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>
<center>
<p><h4><?php echo xlt('Receipt for Payment'); ?></h4>

<?php 
function ageCalculator($dob){
	if(!empty($dob)){
		$birthdate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthdate->diff($today)->y;
		return $age;
	}else{
		return 0;
	}
}
$patdata = getPatientData($pid, 'phone_cell,title,age,age_days,age_months,rateplan,date,sex,DOB,genericname1,fname,mname,lname,pubpid,street,city,state,postal_code,providerID');
$dob =text($patdata['DOB']) ;
$enc=sqlStatement("select * from form_encounter where encounter='".$encounter."'");
$enc1=sqlFetchArray($enc);
$provider=$enc1['provider_id'];
$row1 = sqlStatement("SELECT * from users where id='".$provider."'");
$row2=  sqlFetchArray($row1);
$dob = strtotime($patdata['DOB']);
$current_time = time();
$age_years = date('Y',$current_time) - date('Y',$dob);
$age_months = date('m',$current_time) - date('m',$dob);
$age_days = date('d',$current_time) - date('d',$dob);
if ($age_days<0) {
    $days_in_month = date('t',$current_time);
    $age_months--;
    $age_days= $days_in_month+$age_days;
}

if ($age_months<0) {
    $age_years--;
    $age_months = 12+$age_months;
}
$age=$age_years;
$age_months=$age_months;
$age_days=$age_days;
?>
<table border='0' style="width:100%">
 <tr>
  <td><?php echo xlt('Date'); ?> &nbsp:&nbsp<?php echo text(date('d/M/y',strtotime($payrow['dtime']))) ?>
  <td><?php echo xlt('Patient'); ?> &nbsp:&nbsp<b><?php echo text($patdata['title']) ."  " . text($patdata['fname']) . " " . text($patdata['mname']) . " " .
       text($patdata['lname'])?></b></td></tr>
 <tr>
 <td><?php echo xlt('Patient ID'); ?>&nbsp:&nbsp<?php echo text($patdata['genericname1']) ?></td>
 <td><?php echo xlt('Patient Visit ID'); ?> &nbsp:&nbsp<?php echo text($enc1['encounter_ipop']) ?></td>
 </tr>
 <tr>
 <td><?php echo xlt('Registration Date'); ?>&nbsp:&nbsp<?php echo text(date('d/M/y',strtotime($patdata['date']))) ?></td>
 <?php
if($age!=0)
	{
	echo "<td>" . xlt('Age/Gender') . ": " . text($age) ." ".xlt('Years')." , ".text($patdata['sex']). "</td>";
	}else
	if($age_months!=0)
	{
	echo "<td>" . xlt('Age/Gender') . ": " . text($age_months) ." ".xlt('Months')." , ".text($patdata['sex']). "</td>";
	}else
	{
		echo "<td >" . xlt('Age/Gender') . ": " . text($age_days) ." ".xlt('Days')." , ".text($patdata['sex']). "</td>";
	}?>
 </tr>
 <tr>
 <td><?php echo xlt('Doctor'); ?>&nbsp:&nbsp<?php echo text($row2['username']) ?></td>
 <td><?php echo xlt('Department'); ?> &nbsp:&nbsp<?php echo text($row2['specialty']) ?></td>
 </tr>
 <tr>
 <td><?php echo xlt('Receipt ID'); ?>&nbsp:&nbsp<?php echo text($payrow['receipt_id']) ?></td>
 <td><?php echo xlt('Receipt Date'); ?> &nbsp:&nbsp<?php echo text(date('d/M/y h:i:s A',strtotime($payrow['dtime'])))?></td>
 </tr>
 </table>
 <hr>
 <table border='0' cellspacing='8'>
 <tr>
  <td><?php echo xlt('Paid Via'); ?>:</td>
  <td><?php echo generate_display_field(array('data_type'=>'1','list_id'=>'payment_method'),$payrow['method']); ?></td>
 </tr>
 <tr>
  <td><?php echo xlt('Cheque No./RRN'); ?>:</td>
  <td><?php echo text($payrow['source']) ?></td>
 </tr>
 <tr>
  <td><?php echo xlt('Received Amount'); ?>:</td>
  <td align='right'><?php echo xlt('Rs')?>&nbsp;<?php echo text(oeFormatMoney($payrow['amount1'])) ?></td>
 </tr>
 <tr>
  <td><?php echo xlt('Amount for Past Balance'); ?>:</td>
  <td align='right'><?php echo xlt('Rs')?>&nbsp;<?php echo text(oeFormatMoney($payrow['amount2'])) ?></td>
 </tr>
 <tr>
  <td><?php echo xlt('Against the Bill No'); ?>:</td>
  <td><?php echo text($billid['bill_id']) ?></td>
 </tr>
 <tr>
  <td><?php echo xlt('Received By'); ?>:</td>
  <td><?php echo text($payrow['user']) ?></td>
 </tr>
 <tr>
 
 <?php
if($payrow['towards']==2)
	{
	echo "<td>" . xlt('Towards') . ": " ."</td><td><b>". xlt('Settlement'). "</b></td>";
	}else
	{
	echo "<td>" . xlt('Towards') . ": " . "</td><td><b>".xlt('Advance'). "</b></td>";
	}
?>
</tr>
</table>
<p align="right">
<br/><br/><?php echo xlt('Signature').":  ____________";?>
 <br/><br/>(<?php echo text($payrow['user'])?>)<br/><br/>
 </p>
 <?php print "<hr class='pagebreak' />";?>
 <body class="body_top">
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>
<center>
<?php if($form_towards==2){?>
<p><h4><?php echo xlt('Final Bill'); ?></h4>
<?php }else{?>
<p><h4><?php echo xlt('Bill'); ?></h4>
<?php }?>
</center>
<?php
$enc=sqlStatement("select * from form_encounter where encounter='".$encounter."'");
$enc1=sqlFetchArray($enc);
$provider=$enc1['provider_id'];
$row1 = sqlStatement("SELECT * from users where id='".$provider."'");
$row2=  sqlFetchArray($row1);
$billing=sqlStatement("select * from billing  where encounter='".$encounter."'");
$billid=sqlFetchArray($billing);
$admit=sqlStatement("select * from t_form_admit  where encounter='".$encounter."'");
$admit1=sqlFetchArray($admit);
$add1=$admit1['discharge_date'];

$billingdate=sqlStatement("select max(date) as d from billing  where encounter='".$encounter."'");
$billdate=sqlFetchArray($billingdate);

$row32=sqlStatement("select * from insurance_data where pid='".$pid."'");
$row3=sqlFetchArray($row32);
$approve=sqlStatement("SELECT * from billing_activity_final where encounter='".$encounter."'");
$approved=sqlFetchArray($approve);
$approved_amt=$approved['approved_amt'];
$dob = strtotime($patdata['DOB']);
$current_time = time();
$age_years = date('Y',$current_time) - date('Y',$dob);
$age_months = date('m',$current_time) - date('m',$dob);
$age_days = date('d',$current_time) - date('d',$dob);
if ($age_days<0) {
    $days_in_month = date('t',$current_time);
    $age_months--;
    $age_days= $days_in_month+$age_days;
}

if ($age_months<0) {
    $age_years--;
    $age_months = 12+$age_months;
}
$age=$age_years;
$age_months=$age_months;
$age_days=$age_days;
    echo "<table border=1 rules=cols style='width:100%'>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Name') . ": <b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".text($patdata['title']) ."  " . text($patdata['fname']) . "  " .text($patdata['mname']) ." ".text($patdata['lname']) . "</b></td>";
	echo "<td style='padding-right: '  >" . xlt('Bill No') . ":&nbsp&nbsp&nbsp&nbsp&nbsp " . text($billid['bill_id']) . "</td>";
	if($age!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($age) ." ".xlt('Years')." , ".text($patdata['sex']). "</td>";
	}else
	if($age_months!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($age_months) ." ".xlt('Months')." , ".text($patdata['sex']). "</td>";
	}else
	{
		echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($age_days) ." ".xlt('Days')." , ".text($patdata['sex']). "</td>";
	}
	/* if($add1!=null)
	{
	echo "<td  style='padding-right: 10px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date(' d/M/y h:i:s A',strtotime($admit1['discharge_date'])))."</td>";
	}else
	{
	echo "<td  style='padding-right: 2px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date(' d/M/y h:i:s A'))."</td>";
	}
	 */
	 
	 echo "<td  style='padding-right: 10px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date('d/M/y h:i:s A',strtotime($billdate['d'])))."</td>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Address:') . " &nbsp&nbsp&nbsp&nbsp&nbsp" . "".text($patdata['street']). "</td>";
	echo "<td  style='padding-right: 10px;'>" . xlt('MR No') . ": &nbsp&nbsp&nbsp&nbsp&nbsp" . text($patdata['genericname1']) . "</td></tr>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Location:') . " &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . "".text($patdata['city']).", ".text($patdata['state']) ."</td>";
	echo "<td  style='padding-right: 10px;' >" . xlt('Visit ID') . ":&nbsp&nbsp&nbsp&nbsp&nbsp " . text($enc1['encounter_ipop']) . "</td></tr>";
	echo "<tr><td style='padding-right: 100px;'  >" . xlt('Doctor') . ":&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp" . text($row2['username']) ."</td>";
	echo "<td  style='padding-right: 100px;' >" . xlt('Department') . ": " . text($row2['specialty']) . "</td></tr>";
	if($admit1['admit_to_ward']!=NULL)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('ADM Date') . ": ". text(date('d/M/y h:i:s A',strtotime($admit1['admit_date'])))."</td>";
	echo "<td style='padding-right: 10px;'  >" . xlt('Ward/Bed') . ":&nbsp " . text($admit1['admit_to_ward']) ." , ".text($admit1['admit_to_bed']). "</td></tr>";
	echo "<tr><td   style='padding-right: 100px;'>" . xlt('DCH Date') . ":&nbsp". text(date(' d/M/y h:i:s A',strtotime($admit1['discharge_date'])))."</td>";
    echo "<td  style='padding-right: 10px;' >" . xlt('Rate Plan') . ": &nbsp&nbsp&nbsp" . text($patdata['rateplan']) . "</td></tr>";
	}
    echo "</table>";
		print "</div>";
		 print "<div id='superbill_billingdata'>";
        //print "<h1>".xlt('Billing Information').":</h1>";
      
            $billings = array();
            echo "<table width='100%' style='#000;'>";
            echo "<tr style='border-top: 1px solid #000;' >";
			echo "<b>";
            echo "<td class='bold' width='20%'>".xlt('Date')."</td>";
            echo "<td class='bold' width='20%'>".xlt('Head')."</td>";
			echo "<td class='bold' width='30%'>".xlt('Description')."</td>";
			echo "<td class='bold' width='10%' align='right'>".xlt('Rate')."</td>";
			echo "<td class='bold' width='10%' align='right'>".xlt('Qty')."</td>";
            echo "<td class='bold' width='10%' align='right'>".xlt('Fee')."</td></tr><tr style='border-bottom: 1px solid #000;'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			echo "</b>";
			
            $total = 0.00;
            $copays = 0.00;
            //foreach ($patient as $be) {


                $billing = getPatientBillingEncounter($pid,$encounter);

                $billings[] = $billing;
				
				$item_code=null;
				//$sub_total=sqlStatement("SELECT sum(fee) as fees,code_type,count(code_type) as c  from billing where encounter='".$encounter."' group by code_type ");
                
				foreach ($billing as $b)				
				{
				     //Discount
					 $memo="Discount";
                    $discount=0;
                    $discount = sqlQuery("SELECT SUM(adj_amount) AS adjustments FROM ar_activity WHERE " .
	                                          "memo=? and pid = ? and encounter= ? ",array($memo,$pid,$encounter));
					$totaldis=$discount['adjustments'];
					//$subtotal=0;
                    // grab the date to reformat it in the output
                    $bdate = strtotime($b['date']);
					$ct=$b['code_type'];
					
					$counta=sqlStatement("SELECT max(date) d,sum(fee) as st ,code_type,notecodes,count(code_type) as c from billing where encounter='".$encounter."' and activity='1' and code_type='".$ct."'");
					$cc=sqlFetchArray($counta);
					
					 if($item_code!=$b['code_type']){
					
					 $cct=1;
					  $item_code=$b['code_type'];
					  
					  echo "<td><h5>".$item_code."</td></h5>";
					  
					}
					else
					{
						$cct+=1;
				    }	
					
					$rate=$b['fee']/$b['units'];
					
                    echo "<tr>";
                  /*  if($b['last_update_date']==null)
					{
                    echo "<td class='text' style='font-size: 0.8em'>" . text(date('d/M/y',strtotime($b['date']))) ."</td>";
					}else */
					{
						 echo "<td class='text' style='font-size: 0.8em'>" . text(date('d/M/y',strtotime($cc['d']))) ."</td>";
					}
                    echo "<td class='text'>". text($b['code_type'])."</td>\n";
					echo "<td class='text'>".text($b['code_text'] .' '.$b['notecodes']) . "</td>";
					echo "<td class='text' align='right'>" .text(oeFormatMoney($rate)) . "</td>";
			        echo "<td class='text' align='right'>" .text($b['units']) . "</td>";
                    echo "<td class='text' align='right'>";
                    echo oeFormatMoney($b['fee']);
                    echo "</td>";
					
                    echo "</tr>";
					
			        if($cct==$cc['c'] )  
					{		
						
						echo "\n\n<tr style='border-top: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('')."</td><td class='text'>" . "</td></tr>";
						echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Sub-Total:')."&nbsp&nbsp"."</td><td class='text' align='right'>" . oeFormatMoney($cc['st']) . "</td></tr>";
						
						//$cct=0;
					}
                    $total += $b['fee'];
					
					
                }
				//echo "<tr><td class='bold' colspan=3 style='text-align:right'>".xlt('SubTotal')."</td><td class='text'>" . oeFormatMoney($sub_total) . "</td></tr>";
            // Calculate the copay for the encounter
            $copays = getPatientCopay($pids[$iCounter],$ta[1]);


           
//		   echo "<tr style='border-top: 1px solid #000;'><td colspan=6>&nbsp; </td></tr>";
	//	   echo "<tr style='border-bottom: 1px solid #000;'><td colspan=6>&nbsp; </td></tr>";
			echo "<tr style='border-top: 1px solid #000;'><td>&nbsp; </td></tr>";
            echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Bill Amount:')."&nbsp&nbsp"."</td><td class='text' align='right'>" . oeFormatMoney($total + abs($copays)) . "</td></tr>";

            //echo "<tr><td class='bold' colspan=5 style='text-align:right'>".xlt('Total:')."&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($total) . "</td></tr>";
            echo "</table>";
            echo "<pre>";

            echo "</pre>";
			 
            echo "<table style='border-top: 1px solid #000;' width='100%'>";
            echo "<b><tr style='border-bottom: 1px solid #000;'>";
            echo "<td class='bold'  width='20%'>".xlt('Payments')."</td>";
            echo "<td class='bold' width='20%'>".xlt('Receipt No')."</td>";
			echo "<td class='bold' width='20%'>".xlt('Mode')."</td>";
			echo "<td class='bold' width='10%' nowrap>".xlt('Reference No.')."</td>";
			echo "<td class='bold' width='10%'>".xlt('')."</td>";
            echo "<td class='bold' width='10%' align='right'>".xlt('Amount')."</td></b></tr>\n";
              if($form_towards==2){
			  $inres = sqlStatement("SELECT dtime,amount1,amount2,receipt_id,method,source FROM payments WHERE " .
          "pid = ? AND encounter = ? AND activity=1  " .
			  "ORDER BY dtime", array($pid,$encounter) );
			  }else
			  {
				  $inres = sqlStatement("SELECT dtime,amount1,amount2,receipt_id,method,source FROM payments WHERE " .
          "pid = ? AND encounter = ? AND activity=1  " .
			  "ORDER BY dtime desc limit 1", array($pid,$encounter) );
			  }
    while ($inrow = sqlFetchArray($inres)) {
      $payer = empty($inrow['payer_type']) ? 'Pt' : ('Ins' . $inrow['payer_type']);
      $charges -= sprintf('%01.2f', $inrow['amount1']);
		 $amount = sprintf('%01.2f', 0 - $amount); // make it negative
     echo " <tr>\n\n\n";
       echo "  <td style='font-size: 0.8em'>" .  text(date('d/M/y',strtotime($inrow['dtime']))) . "</td>\n";
     echo "  <td class='text'>" .  text($inrow['receipt_id']) . "</td>\n";
     echo "  <td class='text'>"  .text($inrow['method'])."</td>\n";
     echo "  <td class='text'>".text($inrow['source'])."</td>\n";
	 echo "<td class='text'>".xlt('')."</td>";
	 if($inrow['amount1']==0)
	 {
		 $amt=$inrow['amount2'];
	 }
	 else
	 {
		 $amt=$inrow['amount1'];
	 }
	 //$amt=$inrow['amount1'];
    echo "  <td class='text' align='right'>" . text(oeFormatMoney($amt)) . "</td>\n";
    echo " </tr>\n";
	 $nettotal += $amt;
	

    }
	// echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=6 style='text-align:right'>".xlt('Net Payments :')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($nettotal) . "</td></tr>";
	echo "<tr style='border-top: 1px solid #000;' ><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' style='text-align:right' nowrap>".xlt('Net Payments')."</td><td class='text' style='border-bottom: 1px solid #000;'align='right' >" ."". oeFormatMoney($nettotal) . "</td></tr>";
	if($form_towards==2){
   echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 0px solid #000;'  style='text-align:right' nowrap>".xlt('Total Bill Amount')."</td><td class='text' style='border-bottom: 0px solid #000;' align='right'>" . oeFormatMoney($total) . "</td></tr>";
   echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right'>".xlt('Discount')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($totaldis) . "</td></tr>";
   echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Net Amount')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($total-$totaldis) . "</td></tr>";
    if($approved_amt!=0)
	{
			echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 0px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Amount')."</td><td class='text' style='border-bottom: 0px solid #000;' align='right'>" . oeFormatMoney($approved_amt) . "</td></tr>";
	echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Pay')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($approved_paid) . "</td></tr>";
	echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Due')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($approved_amt-$approved_paid) . "</td></tr>";
	}		
  echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' style='text-align:right' nowrap>".xlt('Balance Due')."</td><td class='text' style='border-bottom: 1px solid #000;' align='right'>" . oeFormatMoney($total-$nettotal-$totaldis-$approved_amt) . "</td></tr>";
	}
	echo "</table>";
	   
        echo "</div>";
	echo"</body>";
	echo "<div align='right'>";
        print "<br/><br/>".xlt('Signature').":  __________";
		 $auth=$_SESSION['authUser'];
         print "<br/><br/>". (text($auth))."&nbsp;&nbsp;&nbsp;<br/>";
		echo "</div>";
	


?>





<div id='printPageButton'>
<p>
<input type='button' value='<?php echo xla('Print'); ?>' onclick='window.print();' />
<!--<input type='button' value='<?php echo xla('Back'); ?>' onclick="location.href='<?php echo $GLOBALS['webroot']?>/interface/forms/newpatient/new.php';" />-->
</div>
</body>
<?php
	}
}
}
	}
	
}
else if ($mode == 'update')
{
  $id = $_POST["id"];
  $result = sqlQuery("SELECT encounter, sensitivity FROM form_encounter WHERE id = ?", array($id));
  if ($result['sensitivity'] && !acl_check('sensitivities', $result['sensitivity'])) {
   die(xlt("You are not authorized to see this encounter."));
  }
  $encounter = $result['encounter'];
  // See view.php to allow or disallow updates of the encounter date.
  $datepart = acl_check('encounters', 'date_a') ? "date = '" . add_escape_custom($date) . "', " : "";
  if($newcrop_user_role['newcrop_user_role'] == 'erxdoctor' || $newcrop_user_role['newcrop_user_role'] == 'erxnurse' )
   {
	   sqlStatement("UPDATE form_encounter SET " .
    "onset_date = '" . add_escape_custom($onset_date) . "', " .
    "reason = '" . add_escape_custom($reason) . "', " .
	 "referral_source = '" . add_escape_custom($referral_source) . "' " .
    "WHERE id = '" . add_escape_custom($id) . "'");
	   
   }else{
  sqlStatement("UPDATE form_encounter SET " .
    $datepart .
    "onset_date = '" . add_escape_custom($onset_date) . "', " .
    "reason = '" . add_escape_custom($reason) . "', " .
    "facility = '" . add_escape_custom($facility) . "', " .
    "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
    "facility_id = '" . add_escape_custom($facility_id) . "', " .
    "billing_facility = '" . add_escape_custom($billing_facility) . "', " .
    "sensitivity = '" . add_escape_custom($sensitivity) . "', " .
	"provider_id = '" . add_escape_custom($provider_id) . "', " .
	"rateplan = '" . add_escape_custom($rateplan) . "', " .
	"tpa_id = '" . add_escape_custom($tpaid) . "', " .
	"package = '" . add_escape_custom($package) . "', " .
    "referral_source = '" . add_escape_custom($referral_source) . "' " .
    "WHERE id = '" . add_escape_custom($id) . "'");
	
    sqlQuery("Update patient_data set rateplan=? where pid=?",array($rateplan,$pid));
 sqlQuery("Update insurance_data set provider=? where pid=?",array($tpaid,$pid));	
	// billing_main_copy needs to be changed here / updated. 
   }
	}
else {
  die("Unknown mode '" . text($mode) . "'");
}

setencounter($encounter);

// Update the list of issues associated with this encounter.
sqlStatement("DELETE FROM issue_encounter WHERE " .
  "pid = ? AND encounter = ?", array($pid,$encounter) );
if (is_array($_POST['issues'])) {
  foreach ($_POST['issues'] as $issue) {
    $query = "INSERT INTO issue_encounter ( pid, list_id, encounter ) VALUES (?,?,?)";
    sqlStatement($query, array($pid,$issue,$encounter));
  }
}

// Custom for Chelsea FC.
//
if ($mode == 'new' && $GLOBALS['default_new_encounter_form'] == 'football_injury_audit') {

  // If there are any "football injury" issues (medical problems without
  // "illness" in the title) linked to this encounter, but no encounter linked
  // to such an issue has the injury form in it, then present that form.

  $lres = sqlStatement("SELECT list_id " .
    "FROM issue_encounter, lists WHERE " .
    "issue_encounter.pid = ? AND " .
    "issue_encounter.encounter = ? AND " .
    "lists.id = issue_encounter.list_id AND " .
    "lists.type = 'medical_problem' AND " .
    "lists.title NOT LIKE '%Illness%'", array($pid,$encounter));

  if (sqlNumRows($lres) > 0) {
    $nexturl = "patient_file/encounter/load_form.php?formname=" .
      $GLOBALS['default_new_encounter_form'];
    while ($lrow = sqlFetchArray($lres)) {
      $frow = sqlQuery("SELECT count(*) AS count " .
         "FROM issue_encounter, forms WHERE " .
         "issue_encounter.list_id = ? AND " .
         "forms.pid = issue_encounter.pid AND " .
         "forms.encounter = issue_encounter.encounter AND " .
         "forms.formdir = ?", array($lrow['list_id'],$GLOBALS['default_new_encounter_form']));
      if ($frow['count']) $nexturl = $normalurl;
		 
    
    }
  }
}
$result4 = sqlStatement("SELECT fe.encounter,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
	" left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
?>
<html>
<body>
<script language='JavaScript'>
<?php if ($GLOBALS['concurrent_layout'])
 {//Encounter details are stored to javacript as array.
?>
	EncounterDateArray=new Array;
	CalendarCategoryArray=new Array;
	EncounterIdArray=new Array;
	Count=0;
	 <?php
			   if(sqlNumRows($result4)>0)
				while($rowresult4 = sqlFetchArray($result4))
				 {
	?>
					EncounterIdArray[Count]='<?php echo attr($rowresult4['encounter']); ?>';
					EncounterDateArray[Count]='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date'])))); ?>';
					CalendarCategoryArray[Count]='<?php echo attr(xl_appt_category($rowresult4['pc_catname'])); ?>';
					Count++;
	 <?php
				 }
	 ?>
	 top.window.parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
<?php } ?>
 top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
<?php if ($mode == 'new') { $_SESSION['visitID'] = $encounter; $_SESSION['LAST_ACTIVITY'] = time(); ?>
 parent.left_nav.setEncounter(<?php echo "'" . oeFormatShortDate($date) . "', " . attr($encounter) . ", window.name"; ?>);
 parent.left_nav.setRadio(window.name, 'enc');
<?php } // end if new encounter ?>
<?php if(isset($_POST['pay'])){?>
window.print();
 parent.left_nav.loadFrame('enc2', window.name,printme());
<?php }else{?>
 parent.left_nav.loadFrame('enc2', window.name,'<?php echo $nexturl; ?>');
<?php }?>
<?php } else { // end if concurrent layout ?>
 window.location="<?php echo $nexturl; ?>";
<?php } // end not concurrent layout ?>
</script>

</body>
</html>
