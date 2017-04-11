<?php
// Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/payment.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sl_eob.inc.php");
require_once("$srcdir/invoice_summary.inc.php");
require_once("../../custom/code_types.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/encounter_events.inc.php");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");
require_once("$srcdir/formatting.inc.php");
$pid = $_REQUEST['hidden_patient_code'] > 0 ? $_REQUEST['hidden_patient_code'] : $pid;

$INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;
?>
<html>
<head>
<?php html_header_show();?>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<?php
// Format dollars for display.
//
function bucks($amount) {
  if ($amount) {
    $amount = oeFormatMoney($amount);
    return $amount;
  }
  return '';
}

function rawbucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    return $amount;
  }
  return '';
}

// Display a row of data for an encounter.
//
$var_index=0;
function echoLine($iname, $date, $charges, $ptpaid, $inspaid, $duept,$encounter=0,$copay=0,$patcopay=0,$bid) {
  global $var_index;
  $var_index++;
  $balance = bucks($charges - $ptpaid - $inspaid);
  $balance = (round($duept,2) != 0) ? 0 : $balance;//if balance is due from patient, then insurance balance is displayed as zero
  $encounter = $encounter ? $encounter : '';
  echo " <tr id='tr_".attr($var_index)."' >\n";
  echo "  <td class='detail'>" . text(oeFormatShortDate($date)) . "</td>\n";
  echo "  <td class='detail' id='".attr($date)."' align='center'>" . htmlspecialchars($bid, ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_charges_$var_index' >" . htmlspecialchars(bucks($charges), ENT_QUOTES) . "</td>\n";
  /* echo "  <td class='detail' align='center' id='td_inspaid_$var_index' >" . htmlspecialchars(bucks($inspaid*-1), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_ptpaid_$var_index' >" . htmlspecialchars(bucks($ptpaid*-1), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_patient_copay_$var_index' >" . htmlspecialchars(bucks($patcopay), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_copay_$var_index' >" . htmlspecialchars(bucks($copay), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='balance_$var_index'>" . htmlspecialchars(bucks($balance), ENT_QUOTES) . "</td>\n";
  */
  //echo "  <td class='detail' align='center' id='duept_$var_index'>" . htmlspecialchars(bucks(round($duept,2)*1), ENT_QUOTES) . "</td>\n";
  
  //echo "  <td class='detail' align='right'><input type='text' name='".attr($iname)."'  id='paying_".attr($var_index)."' " .
  //  " value='" .  '' . "' onchange='coloring();calctotal()'  autocomplete='off' " .
  //  "onkeyup='calctotal()'  style='width:50px'/></td>\n"; 
  echo " </tr>\n";
}


function echoLine1($iname, $date, $charges, $ptpaid, $inspaid, $duept,$encounter=0,$copay=0,$patcopay=0,$bid) {
  global $var_index;
  $var_index++;
  $balance = bucks($charges - $ptpaid - $inspaid);
  $balance = (round($duept,2) != 0) ? 0 : $balance;//if balance is due from patient, then insurance balance is displayed as zero
  $encounter = $encounter ? $encounter : '';
  //echo " <tr id='tr_".attr($var_index)."' >\n";
  //echo "  <td class='detail'>" . text(oeFormatShortDate($date)) . "</td>\n";
  //echo "  <td class='detail' id='".attr($date)."' align='center'>" . htmlspecialchars($bid, ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_charges_$var_index' >" . htmlspecialchars(bucks($charges), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_inspaid_$var_index' >" . htmlspecialchars(bucks($inspaid*-1), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_ptpaid_$var_index' >" . htmlspecialchars(bucks($ptpaid*-1), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_patient_copay_$var_index' >" . htmlspecialchars(bucks($patcopay), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='td_copay_$var_index' >" . htmlspecialchars(bucks($copay), ENT_QUOTES) . "</td>\n";
  echo "  <td class='detail' align='center' id='balance_$var_index'>" . htmlspecialchars(bucks($balance), ENT_QUOTES) . "</td>\n";
  
  echo "  <td class='detail' align='center' id='duept_$var_index'>" . htmlspecialchars(bucks(round($duept,2)*1), ENT_QUOTES) . "</td>\n";
  
  echo "  <td class='detail' align='right'><input type='text' name='".attr($iname)."'  id='paying_".attr($var_index)."' " .
    " value='" .  '' . "' onchange='coloring();calctotal()'  autocomplete='off' " .
   "onkeyup='calctotal()'  style='width:50px'/></td>\n"; 
  echo " </tr>\n";
}


// We use this to put dashes, colons, etc. back into a timestamp.
//
function decorateString($fmt, $str) {
  $res = '';
  while ($fmt) {
    $fc = substr($fmt, 0, 1);
    $fmt = substr($fmt, 1);
    if ($fc == '.') {
      $res .= substr($str, 0, 1);
      $str = substr($str, 1);
    } else {
      $res .= $fc;
    }
  }
  return $res;
}

// Compute taxes from a tax rate string and a possibly taxable amount.
//
function calcTaxes($row, $amount) {
  $total = 0;
  if (empty($row['taxrates'])) return $total;
  $arates = explode(':', $row['taxrates']);
  if (empty($arates)) return $total;
  foreach ($arates as $value) {
    if (empty($value)) continue;
    $trow = sqlQuery("SELECT option_value FROM list_options WHERE " .
      "list_id = 'taxrate' AND option_id = ? LIMIT 1", array($value) );
    if (empty($trow['option_value'])) {
      echo "<!-- Missing tax rate '".text($value)."'! -->\n";
      continue;
    }
    $tax = sprintf("%01.2f", $amount * $trow['option_value']);
    // echo "<!-- Rate = '$value', amount = '$amount', tax = '$tax' -->\n";
    $total += $tax;
  }
  return $total;
}

$now = time();
$today = date('Y-m-d', $now);
$timestamp = date('Y-m-d H:i:s', $now);

if (!$INTEGRATED_AR) slInitialize();

// $patdata = getPatientData($pid, 'fname,lname,mname,pubpid');

$patdata = sqlQuery("SELECT " .
  "p.title,p.age,p.age_days,p.age_months,p.rateplan,p.date,p.DOB,p.sex,p.genericname1,p.fname, p.mname, p.lname, p.pubpid,p.pid, i.copay " .
  "FROM patient_data AS p " .
  "LEFT OUTER JOIN insurance_data AS i ON " .
  "i.pid = p.pid AND i.type = 'primary' " .
  "WHERE p.pid = ? ORDER BY i.date DESC LIMIT 1", array($pid) );

$alertmsg = ''; // anything here pops up in an alert box

  
// If the Save button was clicked...
if ($_POST['form_save']) {
  $pid=$_SESSION['pid'];
  $encounter=$_SESSION['encounter'];
  $billid=$_POST['bill_no'];
  $claim_amt=$_POST['claimamt'];
  $approved_amt=$_POST['aprvlname']; 
  $bil_act=idSqlStatement("INSERT into billing_activity set ".
  "pid=?".
  ",encounter=?".
  ",billid=?".
  ", claim_amt=?".
  ", approved_amt=?",
  array($pid,$encounter,$billid,$claim_amt,$approved_amt));
  sqlStatement("update form_encounter set last_level_closed=? where encounter=? and pid=? ",
							array(4,$encounter,$pid));
  //sqlStatement("update billing set billed=? where encounter=? and pid=?",
	//						array(1,$encounter,$pid));
  $form_pid = $_POST['form_pid'];
  $form_method = trim($_POST['form_method']);
  $form_source = trim($_POST['form_source']);
  $patdata = getPatientData($form_pid, 'title,age,age_days,age_months,rateplan,date,sex,DOB,genericname1,fname,mname,lname,pubpid');
  $NameNew=$patdata['fname'] . " " .$patdata['mname']. " " .$patdata['lname'];

	if($_REQUEST['radio_type_of_payment']=='pre_payment')
	 {
		  $payment_id = idSqlStatement("insert into ar_session set "    .
			"payer_id = ?"       .
			", patient_id = ?"   .
			", user_id = ?"     . 
			", closed = ?"      .
			", reference = ?"   . 
			", check_date =  now() , deposit_date = now() "	.
			",  pay_total = ?"    . 
			", payment_type = 'patient'" .
			", description = ?"   .
			", adjustment_code = 'pre_payment'" .
			", post_to_date = now() " .
			", payment_method = ?",
			array(0,$form_pid,$_SESSION['authUserID'],0,$form_source,$_REQUEST['form_prepayment'],$NameNew,$form_method));
	
		 frontPayment($form_pid, 0, $form_method, $form_source, $_REQUEST['form_prepayment'], 0, $timestamp);//insertion to 'payments' table.
	 }
  //
  if ($_POST['form_upay'] && $_REQUEST['radio_type_of_payment']!='pre_payment') {
    foreach ($_POST['form_upay'] as $enc => $payment) {
      if ($amount = 0 + $payment) {
	       $zero_enc=$enc;
	       if($_REQUEST['radio_type_of_payment']=='invoice_balance')
		    { 
			 ;
		    }
		   else
		    { 
			 if (!$enc) 
			  {
					$enc = calendar_arrived($form_pid);
			  }
		    }
//----------------------------------------------------------------------------------------------------
			//Fetching the existing code and modifier
			$ResultSearchNew = sqlStatement("SELECT * FROM billing LEFT JOIN code_types ON billing.code_type=code_types.ct_key ".
				"WHERE code_types.ct_fee=1 AND billing.activity!=0 AND billing.pid =? AND encounter=? ORDER BY billing.code,billing.modifier",
				array($form_pid,$enc));
			 if($RowSearch = sqlFetchArray($ResultSearchNew))
			  {
                                $Codetype=$RowSearch['code_type'];
				$Code=$RowSearch['code'];
				$Modifier=$RowSearch['modifier'];
			  }
			 else
			  {
                                $Codetype='';
				$Code='';
				$Modifier='';
			  }
//----------------------------------------------------------------------------------------------------
			if($_REQUEST['radio_type_of_payment']=='copay')//copay saving to ar_session and ar_activity tables
			 {
				$session_id=idSqlStatement("INSERT INTO ar_session (payer_id,user_id,reference,check_date,deposit_date,pay_total,".
				 " global_amount,payment_type,description,patient_id,payment_method,adjustment_code,post_to_date) ".
				 " VALUES ('0',?,?,now(),now(),?,'','patient','COPAY',?,?,'patient_payment',now())",
				 array($_SESSION['authId'],$form_source,$amount,$form_pid,$form_method));
				 
				  $insrt_id=idSqlStatement("INSERT INTO ar_activity (pid,encounter,code_type,code,modifier,payer_type,post_time,post_user,session_id,pay_amount,account_code)".
				   " VALUES (?,?,?,?,?,0,now(),?,?,?,'PCP')",
					 array($form_pid,$enc,$Codetype,$Code,$Modifier,$_SESSION['authId'],$session_id,$amount));
				   
				 frontPayment($form_pid, $enc, $form_method, $form_source, $amount, 0, $timestamp);//insertion to 'payments' table.
			 }
			if($_REQUEST['radio_type_of_payment']=='invoice_balance' || $_REQUEST['radio_type_of_payment']=='cash')
			 {				//Payment by patient after insurance paid, cash patients similar to do not bill insurance in feesheet.
				  if($_REQUEST['radio_type_of_payment']=='cash')
				   {
				    sqlStatement("update form_encounter set last_level_closed=? where encounter=? and pid=? ",
							array(4,$enc,$form_pid));
				    sqlStatement("update billing set billed=? where encounter=? and pid=?",
							array(1,$enc,$form_pid));
				   }
				  $adjustment_code='patient_payment';
				  $payment_id = idSqlStatement("insert into ar_session set "    .
					"payer_id = ?"       .
					", patient_id = ?"   .
					", user_id = ?"     .
					", closed = ?"      .
					", reference = ?"   .
					", check_date =  now() , deposit_date = now() "	.
					",  pay_total = ?"    .
					", payment_type = 'patient'" .
					", description = ?"   .
					", adjustment_code = ?" .
					", post_to_date = now() " .
					", payment_method = ?",
					array(0,$form_pid,$_SESSION['authUserID'],0,$form_source,$amount,$NameNew,$adjustment_code,$form_method));

	//--------------------------------------------------------------------------------------------------------------------

        			frontPayment($form_pid, $enc, $form_method, $form_source, 0, $amount, $timestamp);//insertion to 'payments' table.

	//--------------------------------------------------------------------------------------------------------------------

					$resMoneyGot = sqlStatement("SELECT sum(pay_amount) as PatientPay FROM ar_activity where pid =? and ".
						"encounter =? and payer_type=0 and account_code='PCP'",
						array($form_pid,$enc));//new fees screen copay gives account_code='PCP'
					$rowMoneyGot = sqlFetchArray($resMoneyGot);
					$Copay=$rowMoneyGot['PatientPay'];
					
	//--------------------------------------------------------------------------------------------------------------------

					//Looping the existing code and modifier
					$ResultSearchNew = sqlStatement("SELECT * FROM billing LEFT JOIN code_types ON billing.code_type=code_types.ct_key WHERE code_types.ct_fee=1 ".
						"AND billing.activity!=0 AND billing.pid =? AND encounter=? ORDER BY billing.code,billing.modifier",
					  array($form_pid,$enc));
					 while($RowSearch = sqlFetchArray($ResultSearchNew))
					  {
                                                $Codetype=$RowSearch['code_type'];
						$Code=$RowSearch['code'];
						$Modifier =$RowSearch['modifier'];
						$Fee =$RowSearch['fee'];
						
						$resMoneyGot = sqlStatement("SELECT sum(pay_amount) as MoneyGot FROM ar_activity where pid =? ".
							"and code_type=? and code=? and modifier=? and encounter =? and !(payer_type=0 and account_code='PCP')",
						array($form_pid,$Codetype,$Code,$Modifier,$enc));
						//new fees screen copay gives account_code='PCP'
						$rowMoneyGot = sqlFetchArray($resMoneyGot);
						$MoneyGot=$rowMoneyGot['MoneyGot'];

						$resMoneyAdjusted = sqlStatement("SELECT sum(adj_amount) as MoneyAdjusted FROM ar_activity where ".
						  "pid =? and code_type=? and code=? and modifier=? and encounter =?",
						  array($form_pid,$Codetype,$Code,$Modifier,$enc));
						$rowMoneyAdjusted = sqlFetchArray($resMoneyAdjusted);
						$MoneyAdjusted=$rowMoneyAdjusted['MoneyAdjusted'];
						
						$Remainder=$Fee-$Copay-$MoneyGot-$MoneyAdjusted;
						$Copay=0;
						if(round($Remainder,2)!=0 && $amount!=0) 
						 {
						  if($amount-$Remainder >= 0)
						   {
								$insert_value=$Remainder;
								$amount=$amount-$Remainder;
						   }
						  else
						   {
								$insert_value=$amount;
								$amount=0;
						   }
						  sqlStatement("insert into ar_activity set "    .
							"pid = ?"       .
							", encounter = ?"     .
                                                        ", code_type = ?"      .
							", code = ?"      .
							", modifier = ?"      .
							", payer_type = ?"   .
							", post_time = now() " .
							", post_user = ?" .
							", session_id = ?"    .
							", pay_amount = ?" .
							", adj_amount = ?"    .
							", account_code = 'PP'",
							array($form_pid,$enc,$Codetype,$Code,$Modifier,0,$_SESSION['authUserID'],$payment_id,$insert_value,0));
						 }//if
					  }//while
					 if($amount!=0)//if any excess is there.
					  {
						  sqlStatement("insert into ar_activity set "    .
							"pid = ?"       .
							", encounter = ?"     .
                                                        ", code_type = ?"      .
							", code = ?"      .
							", modifier = ?"      .
							", payer_type = ?"   .
							", post_time = now() " .
							", post_user = ?" .
							", session_id = ?"    .
							", pay_amount = ?" .
							", adj_amount = ?"    .
							", account_code = 'PP'",
							array($form_pid,$enc,$Codetype,$Code,$Modifier,0,$_SESSION['authUserID'],$payment_id,$amount,0));
					  }

	//--------------------------------------------------------------------------------------------------------------------
			   }//invoice_balance
			}//if ($amount = 0 + $payment) 
		}//foreach
	 }//if ($_POST['form_upay'])
  }//if ($_POST['form_save'])

if ($_POST['form_save'] || $_REQUEST['receipt']) {

  if ($_REQUEST['receipt']) {
    $form_pid = $_GET['patient'];
    $timestamp = decorateString('....-..-.. ..:..:..', $_GET['time']);
  }

  // Get details for what we guess is the primary facility.
  $frow = sqlQuery("SELECT * FROM facility " .
    "ORDER BY billing_location DESC, accepts_assignment DESC, id LIMIT 1");

  // Get the patient's name and chart number.
  $patdata = getPatientData($form_pid, 'title,age,age_days,age_months,rateplan,date,sex,DOB,genericname1,fname,mname,lname,pubpid');

  // Re-fetch payment info.
  $payrow = sqlQuery("SELECT " .
    "SUM(amount1) AS amount1, " .
	 "receipt_id,".
    "SUM(amount2) AS amount2, " .
    "MAX(method) AS method, " .
    "MAX(source) AS source, " .
    "MAX(dtime) AS dtime, " .
    // "MAX(user) AS user " .
    "MAX(user) AS user, " .
    "MAX(encounter) as encounter ".
    "FROM payments WHERE " .
    "pid = ? AND dtime = ?", array($form_pid,$timestamp) );

  // Create key for deleting, just in case.
	$ref_id = ($_REQUEST['radio_type_of_payment']=='copay') ? $session_id : $payment_id ;
  $payment_key = $form_pid . '.' . preg_replace('/[^0-9]/', '', $timestamp).'.'.$ref_id;

  // get facility from encounter
  $tmprow = sqlQuery("
    SELECT facility_id
    FROM form_encounter
    WHERE encounter = ?", array($payrow['encounter']) );
  $frow = sqlQuery("SELECT * FROM facility " .
    " WHERE id = ?", array($tmprow['facility_id']) );

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
    font-size: 0.8em;
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
    font-size: 0.8em;
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
    font-size: 0.8em;
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
</style>
<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // Process click on Print button.
 function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  window.print();
  // divstyle.display = 'block';
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
<body class="body_top">
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" style="margin:0px 0px" align="top"/>
<center>
<hr>
<p><h4><?php echo xlt('Final Bill'); ?></h4>
</center>
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

$dob =text($patdata['DOB']) ;

$encounter=$_SESSION['encounter'];
$enc=sqlStatement("select * from form_encounter where encounter='".$encounter."'");
$enc1=sqlFetchArray($enc);
$provider=$enc1['provider_id'];
$row1 = sqlStatement("SELECT * from users where id='".$provider."'");
$row2=  sqlFetchArray($row1);
$billing=sqlStatement("select * from billing  where encounter='".$encounter."'");
$billid=sqlFetchArray($billing);
$admit=sqlStatement("select * from t_form_admit  where encounter='".$encounter."'");
$admit1=sqlFetchArray($admit);
$row32=sqlStatement("select * from insurance_data where pid='".$form_pid."'");
$row3=sqlFetchArray($row32);
$provider1=$row3['provider'];
$insurance=sqlStatement("select * from insurance_companies where id='".$provider1."'");
$insurance1=sqlFetchArray($insurance);
$age=$patdata['age'];
$age_months=$patdata['age_months'];
$age_days=$patdata['age_days'];
$rateplan=$patdata['rateplan'];

    echo "<table border=1 rules=cols style='width:100%'>";
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Name') . ": <b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" .text($patdata['title']) ."  ". text($patdata['fname']) . "  " . text($patdata['mname'])." ".text($patdata['lname']) . "</b></td>";
	echo "<td style='padding-right: '  >" . xlt('Bill No') . ":&nbsp&nbsp&nbsp&nbsp&nbsp " . text($billid['bill_id']) . "</td>";
	if($age!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age']) ." ".xlt('Years')." , ".text($patdata['sex']). "</td>";
	}else
	if($age_months!=0)
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age_months']) ." ".xlt('Months')." , ".text($patdata['sex']). "</td>";
	}else
	{
		echo "<tr><td  style='padding-right: 100px;' >" . xlt('Age/Gender') . ": " . text($patdata['age_days']) ." ".xlt('Days')." , ".text($patdata['sex']). "</td>";
	}
	echo "<td  style='padding-right: 2px;' >" . xlt('Bill Date') . ":&nbsp&nbsp ". text(date(' d/M/y h:i:s A',strtotime($admit1['discharge_date'])))."</td>";
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
	if($rateplan=="TPAInsurance")
	{
	echo "<tr><td  style='padding-right: 100px;' >" . xlt('TPA Insurance') . ": ".$insurance1['name'] ."</td></tr>";
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
			echo "<td class='bold' width='20%'>".xlt('Description')."</td>";
			echo "<td class='bold' width='20%'>".xlt('Rate')."</td>";
			echo "<td class='bold' width='10%'>".xlt('Qty')."</td>";
            echo "<td class='bold' width='10%'>".xlt('Fee')."</td></tr><tr style='border-bottom: 1px solid #000;'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
			echo "</b>";
			
            $total = 0.00;
            $copays = 0.00;
            //foreach ($patient as $be) {


                $billing = getPatientBillingEncounter($form_pid,$encounter);

                $billings[] = $billing;
				
				$item_code=null;
				//$sub_total=sqlStatement("SELECT sum(fee) as fees,code_type,count(code_type) as c  from billing where encounter='".$encounter."' group by code_type ");
                
				foreach ($billing as $b)				
				{
				    $memo="Discount";
                    $discount=0;
                    $discount = sqlQuery("SELECT SUM(adj_amount) AS adjustments FROM ar_activity WHERE " .
	                                          "memo=? and pid = ? and encounter = ? ",array($memo,$form_pid,$encounter));
					
					$totaldis=$discount['adjustments'];
					
					//$subtotal=0;
                    // grab the date to reformat it in the output
                 
                      

				 $bdate = strtotime($b['date']);
					$ct=$b['code_type'];
					
					$counta=sqlStatement("SELECT sum(fee) as st ,code_type,notecodes, count(code_type) as c from billing where encounter='".$encounter."' and activity='1' and code_type='".$ct."'");
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
                    echo "<td class='text' style='font-size: 0.8em'>" . text(date('d/M/y',strtotime($b['date']))) ."</td>";
                    echo "<td class='text'>". text($b['code_type'])."</td>\n";
					echo "<td class='text' nowrap>".text($b['code_text'] .' '.$b['notecodes']) . "</td>";
					echo "<td class='text'>" .text($rate) . "</td>";
			        echo "<td class='text'>" .text($b['units']) . "</td>";
                    echo "<td class='text'>";
                    echo oeFormatMoney($b['fee']);
                    echo "</td>";
					
                    echo "</tr>";
					
			        if($cct==$cc['c'] )  
					{		
						
						echo "\n\n<tr style='border-top: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('')."</td><td class='text'>" . "</td></tr>";
						echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Sub-Total:')."&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($cc['st']) . "</td></tr>";
						
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
            echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=5 style='text-align:right'>".xlt('Bill Amount:')."&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($total + abs($copays)) . "</td></tr>";

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
            echo "<td class='bold' width='10%'>".xlt('Amount')."</td></b></tr>\n";
             
			  $inres = sqlStatement("SELECT dtime,amount1,amount2,receipt_id,method FROM payments WHERE " .
          "pid = ? AND encounter = ? " .
      "ORDER BY dtime", array($form_pid,$encounter) );
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
    echo "  <td class='text'>" . text(oeFormatMoney($amt)) . "</td>\n";
    echo " </tr>\n";
	 $nettotal += $amt;
	

    }
	
	// echo "<tr style='border-bottom: 1px solid #000;'><td class='bold' colspan=6 style='text-align:right'>".xlt('Net Payments :')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."</td><td class='text'>" . oeFormatMoney($nettotal) . "</td></tr>";
	echo "<tr style='border-top: 1px solid #000;' ><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' nowrap style='text-align:right'>".xlt('Net Payments ')."</td><td class='text' style='border-bottom: 1px solid #000;' >" ."". oeFormatMoney($nettotal) . "</td></tr>";
   echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 0px solid #000;'  style='align:right' nowrap>".xlt('Total Bill Amount')."</td><td class='text'>" . oeFormatMoney($total) . "</td></tr>";
   echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right'>".xlt('Discount')."</td><td class='text' style='border-bottom: 1px solid #000;'>" . oeFormatMoney($totaldis) . "</td></tr>";
    echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Net Amount')."</td><td class='text' style='border-bottom: 1px solid #000;'>" . oeFormatMoney($total-$totaldis) . "</td></tr>";
  	echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 0px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Amount')."</td><td class='text' style='border-bottom: 0px solid #000;'>" . oeFormatMoney($approved_amt) . "</td></tr>";
	echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Pay')."</td><td class='text' style='border-bottom: 1px solid #000;'>" . oeFormatMoney($approved_paid) . "</td></tr>";
	echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;'  style='align:right' nowrap>".xlt('Pri. Sponsor Due')."</td><td class='text' style='border-bottom: 1px solid #000;'>" . oeFormatMoney($approved_amt-$approved_paid) . "</td></tr>";
  echo "<tr><td></td><td></td><td></td><td></td><td class='bold' style='border-bottom: 1px solid #000;' style='align:right' nowrap>".xlt('Patient Due')."</td><td class='text' style='border-bottom: 1px solid #000;'>" . oeFormatMoney($total-$nettotal-$approved_amt-$totaldis) . "</td></tr>";
	echo "</table>";
	   
        echo "</div>";
	echo"</body>";
	echo "<div align='right'>";
        print "<br/><br/>".xlt('Signature').":  __________";
		 $auth=$_SESSION['authUser'];
         print "<br/><br/>". (text($auth))."&nbsp;&nbsp;&nbsp;<br/>";
		echo "</div>";
?>



<div id='hideonprint'>
<p>
<input type='button' value='<?php echo xla('Print'); ?>' onclick='printme()' />

<?php
  $todaysenc = todaysEncounterIf($pid);
  if ($todaysenc && $todaysenc != $encounter) {
    echo "&nbsp;<input type='button' " .
      "value='" . xla('Open Today`s Visit') . "' " .
      "onclick='toencounter($todaysenc,\"$today\",opener.top)' />\n";
  }
?>

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;
<input type='button' value='<?php xl('Delete','e'); ?>' style='color:red' onclick='deleteme()' />
<?php } ?>

</div>
</center>
</body>

<?php
  //
  // End of receipt printing logic.
  //
} else {
  //
  // Here we display the form for data entry.
  //
?>
<title><?php echo xlt('Record Payment'); ?></title>

<style type="text/css">
 body    { font-family:sans-serif; font-size:10pt; font-weight:normal }
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
#ajax_div_patient {
	position: absolute;
	z-index:10;
	background-color: #FBFDD0;
	border: 1px solid #ccc;
	padding: 10px;
}
</style>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>



<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="../../library/js/common.js"></script>
<script type="text/javascript" src="../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script type="text/javascript" src="../../library/js/jquery.easydrag.handler.beta2.js"></script> 
<script language='JavaScript'>
 var mypcc = '1';
</script>
<?php include_once("{$GLOBALS['srcdir']}/ajax/payment_ajax_jav.inc.php"); ?>
<script language="javascript" type="text/javascript">
document.onclick=HideTheAjaxDivs;
</script>

<script type="text/javascript" src="../../library/topdialog.js"></script>

<script language="JavaScript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

function calctotal() {
 var f = document.forms[0];
 var total = 0;
 for (var i = 0; i < f.elements.length; ++i) {
  var elem = f.elements[i];
  var ename = elem.name;
  if (ename.indexOf('form_upay[') == 0 || ename.indexOf('form_bpay[') == 0) {
   if (elem.value.length > 0) total += Number(elem.value);
  }
 }
 f.form_paytotal.value = Number(total).toFixed(2);
 return true;
}
function coloring()
 {
   for (var i = 1; ; ++i) 
    {
	  if(document.getElementById('paying_'+i))
	   {
	    paying=document.getElementById('paying_'+i).value*1;
		patient_balance=document.getElementById('duept_'+i).innerHTML*1;
		//balance=document.getElementById('balance_'+i).innerHTML*1;
		if(patient_balance>0 && paying>0)
		 {
			if(paying>patient_balance)
			 {
			  document.getElementById('paying_'+i).style.background='#FF0000';
			 }
			else if(paying<patient_balance)
			 {
			  document.getElementById('paying_'+i).style.background='#99CC00';
			 }
			else if(paying==patient_balance)
			 {
			  document.getElementById('paying_'+i).style.background='#ffffff';
			 }
		 }
		else
		 {
		  document.getElementById('paying_'+i).style.background='#ffffff';
		 }
	   }
	  else
	   {
	    break;
	   }
	}
 }
function CheckVisible(MakeBlank)
 {//Displays and hides the check number text box.
   if(document.getElementById('form_method').options[document.getElementById('form_method').selectedIndex].value=='check_payment' ||
   	  document.getElementById('form_method').options[document.getElementById('form_method').selectedIndex].value=='bank_draft'  )
   {
	document.getElementById('check_number').disabled=false;
   }
   else
   {
	document.getElementById('check_number').disabled=true;
   }
 }
function validate()
 {
  var f = document.forms[0];
  ok=-1;
  top.restoreSession();
  issue='no';
   if(((document.getElementById('form_method').options[document.getElementById('form_method').selectedIndex].value=='check_payment' ||
   	  document.getElementById('form_method').options[document.getElementById('form_method').selectedIndex].value=='bank_draft') &&
	   document.getElementById('check_number').value=='' ))
   {
    alert("<?php echo addslashes( xl('Please Fill the Check/Ref Number')) ?>");
	document.getElementById('check_number').focus();
	return false;
   }

  if(document.getElementById('radio_type_of_payment_self1').checked==false && document.getElementById('radio_type_of_payment_self2').checked==false   && document.getElementById('radio_type_of_payment1').checked==false && document.getElementById('radio_type_of_payment2').checked==false  && document.getElementById('radio_type_of_payment5').checked==false  && document.getElementById('radio_type_of_payment4').checked==false)
   {
	  alert("<?php echo addslashes( xl('Please Select Type Of Payment.')) ?>");
	  return false;
   }
  if(document.getElementById('radio_type_of_payment_self1').checked==true || document.getElementById('radio_type_of_payment_self2').checked==true || document.getElementById('radio_type_of_payment1').checked==true || document.getElementById('radio_type_of_payment5').checked==true)
   {
	 for (var i = 0; i < f.elements.length; ++i) 
	 {
	  var elem = f.elements[i];
	  var ename = elem.name;
	  if (ename.indexOf('form_upay[0') == 0) //Today is this text box.
	  {
	   if(elem.value*1>0)
	    {//A warning message, if the amount is posted with out encounter.
		 if(confirm("<?php echo addslashes( xl('Are you sure to post for today?')) ?>"))
		  {
		   ok=1;
		  }
		 else
		  {
		   elem.focus();
		   return false;
		  }
		}
	   break;
	  }
	}
   }

  if(document.getElementById('radio_type_of_payment1').checked==true)//CO-PAY 
   {
	 var total = 0;
	 for (var i = 0; i < f.elements.length; ++i) 
	 {
	  var elem = f.elements[i];
	  var ename = elem.name;
	  if (ename.indexOf('form_upay[') == 0) //Today is this text box.
	  {
	   if(f.form_paytotal.value*1!=elem.value*1)//Total CO-PAY is not posted against today
	    {//A warning message, if the amount is posted against an old encounter.
		 if(confirm("<?php echo addslashes( xl('You are posting against an old encounter?')) ?>"))
		  {
		   ok=1;
		  }
		 else
		  {
		   elem.focus();
		   return false;
		  }
		}
	   break;
	  }
	}
   }//Co Pay
 else if(document.getElementById('radio_type_of_payment2').checked==true)//Invoice Balance
  {
   if(document.getElementById('Today').innerHTML=='')
    {
	 for (var i = 0; i < f.elements.length; ++i) 
	  {
	   var elem = f.elements[i];
	   var ename = elem.name;
	   if (ename.indexOf('form_upay[') == 0) 
		{
		 if (elem.value*1 > 0)
		  {
			  alert("<?php echo addslashes( xl('Invoice Balance cannot be posted. No Encounter is created.')) ?>");
			  return false;
		 }
		 break;
	   }
	  }
	}
  }
 if(ok==-1)
  {
	 if(confirm("<?php echo addslashes( xl('Would you like to save?')) ?>"))
	  {
	   return true;
	  }
	 else
	  {
	   return false;
	  }
  }
}
function cursor_pointer()
 {//Point the cursor to the latest encounter(Today)
	 var f = document.forms[0];
	 var total = 0;
	 for (var i = 0; i < f.elements.length; ++i) 
	 {
	  var elem = f.elements[i];
	  var ename = elem.name;
	  if (ename.indexOf('form_upay[') == 0) 
	  {
	   elem.focus();
	   break;
	  }
	}
 }
 //=====================================================
function make_it_hide_enc_pay()
 {
  	document.getElementById('td_head_insurance_payment').style.display="none";
  	document.getElementById('td_head_patient_co_pay').style.display="none";
  	document.getElementById('td_head_co_pay').style.display="none";
  	document.getElementById('td_head_insurance_balance').style.display="none";
  for (var i = 1; ; ++i) 
  {
   	var td_inspaid_elem = document.getElementById('td_inspaid_'+i)
		var td_patient_copay_elem = document.getElementById('td_patient_copay_'+i)
   	var td_copay_elem = document.getElementById('td_copay_'+i)
   	var balance_elem = document.getElementById('balance_'+i)
   if (td_inspaid_elem) 
   {
    td_inspaid_elem.style.display="none";
		td_patient_copay_elem.style.display="none";
    td_copay_elem.style.display="none";
    balance_elem.style.display="none";
   }
  else
   {
    break;
   }
  }
  document.getElementById('td_total_4').style.display="none";
  document.getElementById('td_total_7').style.display="none";
	document.getElementById('td_total_8').style.display="none";
  document.getElementById('td_total_6').style.display="none";
 
  document.getElementById('table_display').width="420px";
 }

 //=====================================================
function make_visible()
 {
  document.getElementById('td_head_rep_doc').style.display="";
  document.getElementById('td_head_description').style.display="";
  document.getElementById('td_head_total_charge').style.display="none";
  document.getElementById('td_head_insurance_payment').style.display="none";
  document.getElementById('td_head_patient_payment').style.display="none";
	document.getElementById('td_head_patient_co_pay').style.display="none";
  document.getElementById('td_head_co_pay').style.display="none";
  document.getElementById('td_head_insurance_balance').style.display="none";
  document.getElementById('td_head_patient_balance').style.display="none";
  for (var i = 1; ; ++i) 
  {
   var td_charges_elem = document.getElementById('td_charges_'+i)
   var td_inspaid_elem = document.getElementById('td_inspaid_'+i)
   var td_ptpaid_elem = document.getElementById('td_ptpaid_'+i)
	 var td_patient_copay_elem = document.getElementById('td_patient_copay_'+i)
   var td_copay_elem = document.getElementById('td_copay_'+i)
   var balance_elem = document.getElementById('balance_'+i)
   var duept_elem = document.getElementById('duept_'+i)
   if (td_charges_elem) 
   {
    td_charges_elem.style.display="none";
    td_inspaid_elem.style.display="none";
    td_ptpaid_elem.style.display="none";
		td_patient_copay_elem.style.display="none";
    td_copay_elem.style.display="none";
    balance_elem.style.display="none";
    duept_elem.style.display="none";
   }
  else
   {
    break;
   }
  }
  document.getElementById('td_total_7').style.display="";
	document.getElementById('td_total_8').style.display="";
  document.getElementById('td_total_1').style.display="none";
  document.getElementById('td_total_2').style.display="none";
  document.getElementById('td_total_3').style.display="none";
  document.getElementById('td_total_4').style.display="none";
  document.getElementById('td_total_5').style.display="none";
  document.getElementById('td_total_6').style.display="none";
 
  document.getElementById('table_display').width="505px";
 }
function make_it_hide()
 {
  document.getElementById('td_head_rep_doc').style.display="none";
  document.getElementById('td_head_description').style.display="none";
  document.getElementById('td_head_total_charge').style.display="";
  document.getElementById('td_head_insurance_payment').style.display="";
  document.getElementById('td_head_patient_payment').style.display="";
  document.getElementById('td_head_patient_co_pay').style.display="";
	document.getElementById('td_head_co_pay').style.display="";
  document.getElementById('td_head_insurance_balance').style.display="";
  document.getElementById('td_head_patient_balance').style.display="";
  for (var i = 1; ; ++i) 
  {
   var td_charges_elem = document.getElementById('td_charges_'+i)
   var td_inspaid_elem = document.getElementById('td_inspaid_'+i)
   var td_ptpaid_elem = document.getElementById('td_ptpaid_'+i)
	 var td_patient_copay_elem = document.getElementById('td_patient_copay_'+i)
   var td_copay_elem = document.getElementById('td_copay_'+i)
   var balance_elem = document.getElementById('balance_'+i)
   var duept_elem = document.getElementById('duept_'+i)
   if (td_charges_elem) 
   {
    td_charges_elem.style.display="";
    td_inspaid_elem.style.display="";
    td_ptpaid_elem.style.display="";
		td_patient_copay_elem.style.display="";
    td_copay_elem.style.display="";
    balance_elem.style.display="";
    duept_elem.style.display="";
   }
  else
   {
    break;
   }
  }
  document.getElementById('td_total_1').style.display="";
  document.getElementById('td_total_2').style.display="";
  document.getElementById('td_total_3').style.display="";
  document.getElementById('td_total_4').style.display="";
  document.getElementById('td_total_5').style.display="";
  document.getElementById('td_total_6').style.display="";
	document.getElementById('td_total_7').style.display="";
  document.getElementById('td_total_8').style.display="";
 
  document.getElementById('table_display').width="635px";
 }
function make_visible_radio()
 {
  document.getElementById('tr_radio1').style.display="";
  document.getElementById('tr_radio2').style.display="none";
 }
function make_hide_radio()
 {
  document.getElementById('tr_radio1').style.display="none";
  document.getElementById('tr_radio2').style.display="";
 }
function make_visible_row()
 {
  document.getElementById('table_display').style.display="";
  document.getElementById('table_display_prepayment').style.display="none";
 }
function make_hide_row()
 {
  document.getElementById('table_display').style.display="none";
  document.getElementById('table_display_prepayment').style.display="";
 }
function make_self()
 {
  make_visible_row();
  make_it_hide();
  make_it_hide_enc_pay();
  document.getElementById('radio_type_of_payment_self1').checked=true;
  cursor_pointer();
 }
function make_insurance()
 {
  make_visible_row();
  make_it_hide();
  cursor_pointer();
  document.getElementById('radio_type_of_payment1').checked=true;
 }
 
 function showInsurance(str) {
    if (str == "") {
        document.getElementById("insurance").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("insurance").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","incdetails.php?q="+str,true);
        xmlhttp.send();
    }
}


</script>

</head>

<body class="body_top" onunload='imclosing()' onLoad="cursor_pointer();">
<center>

<form method='post' action='front_payment_test.php<?php if ($payid) echo "?payid=$payid"; ?>'
 onsubmit='return validate();'>
<input type='hidden' name='form_pid' value='<?php echo attr($pid) ?>' />
<?php 
/* $pri='primary';
$isins=sqlStatement("SELECT * from insurance_data where pid='".$pid."' and provider is not null");
$as=sqlFetchArray($isins); */
$insurance=sqlStatement("SELECT COUNT( DISTINCT TYPE ) NumberOfInsurance FROM insurance_data
			where pid = ? and provider>0 ",array($pid));
$insu = sqlFetchArray($insurance);
$ins=$insu['NumberOfInsurance'];
if($ins>0)
{
?>

<table border='0' cellspacing='0' cellpadding="0">

 <tr height="10">
 	<td colspan="3">&nbsp;</td>
 </tr>
 <?php
   $encounter=$_SESSION['encounter'];
   
$billid=sqlQuery("SELECT * from billing where encounter='".$encounter."'");
?>
 <tr>
  <td colspan='3' align='center' class='text' >
   <b><?php echo htmlspecialchars(xl('Accept Payment for'), ENT_QUOTES); ?>&nbsp;:&nbsp;&nbsp;<?php echo htmlspecialchars($patdata['fname'], ENT_QUOTES) . " " .
    htmlspecialchars($patdata['lname'], ENT_QUOTES) . " " .htmlspecialchars($patdata['mname'], ENT_QUOTES). " (" . htmlspecialchars($patdata['pid'], ENT_QUOTES) . ")" ?></b>
	<?php $NameNew=$patdata['fname'] . " " .$patdata['lname']. " " .$patdata['mname'];?>
  </td>
 </tr>
 <tr>
 <td colspan='3' align='center' class='text' >
   <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars(xl('Bill ID'), ENT_QUOTES); ?>&nbsp;:&nbsp;&nbsp;
   <input type='text' name='bill_no'  value='<?php echo htmlspecialchars($billid['bill_id']); ?>' style='width:70px;border:none;font-weight: bold;' readonly >
 </tr>

 <tr height="15"><td colspan='3'></td></tr>


 <tr>
  <td class='text' >
   <?php echo xlt('Payment Method'); ?>:
  </td>
  <td colspan='2' >
  <select name="form_method" id="form_method"  class="text" onChange='showInsurance(this.value)'>
  <?php
  $query1112 = "SELECT * FROM list_options where list_id=?  ORDER BY seq, title ";
  $bres1112 = sqlStatement($query1112,array('payment_type'));
  while ($brow1112 = sqlFetchArray($bres1112)) 
   {
  	if($brow1112['option_id']=='electronic' || $brow1112['option_id']=='bank_draft')
	 continue;
	echo "<option value='".htmlspecialchars($brow1112['option_id'], ENT_QUOTES)."'>".htmlspecialchars(xl_list_label($brow1112['title']), ENT_QUOTES)."</option>";
   }
  ?>
  </select>
  </td>
 </tr>
</table>

<p>
<div id="insurance">


</div>
<p>
<input type='submit' name='form_save' value='<?php echo htmlspecialchars( xl('Save'), ENT_QUOTES);?>' /> &nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />

<input type="hidden" name="hidden_patient_code" id="hidden_patient_code" value="<?php echo attr($pid);?>"/>
<input type='hidden' name='ajax_mode' id='ajax_mode' value='' />
<input type='hidden' name='mode' id='mode' value='' />
</form>
<?php }

else
echo "No Insurance Data"; 

?>
<script language="JavaScript">
 calctotal();
</script>
</center>
</body>

<?php
}
if (!$INTEGRATED_AR) SLClose();
?>
</html>
