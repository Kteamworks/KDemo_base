<?php 
/**
 * This is a report of Financial Summary by Service Code.
 *
 * This is a summary of service code charge/pay/adjust and balance,
 * with the ability to pick "important" codes to either highlight or
 * limit to list to. Important codes can be configured in
 * Administration->Service section by assigning code with
 * 'Service Reporting'.
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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
 * @author  Visolve
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes=true;
$fake_register_globals=false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once "$srcdir/appointments.inc.php";
?>

<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<?php html_header_show();?>
<script type="text/javascript">

function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>
<style type="text/css">
/* specifically include & exclude from printing */

@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results {
       margin-top:30px;
    }
	
	#report {
       margin:30px;
    }
}
table{
    border-style: none;
    border-top: none;
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
#report_parameters {
  background-color: #ececec;
  margin-top: 10px; }

  #report_parameters table {
    border: none;
    width: 100%;
    border-collapse: collapse; }

    #report_parameters table table td {
      padding: 5px; }

	  #report_parameters table table table {
      border: none;
      border-collapse: collapse;
      font-size: 0.8em; }

      #report_parameters table table table td.label {
        text-align: right; }

#report_results table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report_results table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 0.7em; }
  #report_results table th {
   
    padding: 5px; }
  #report_results table td {
    padding: 5px;
    
    font-size: 0.8em; }

	
	#report table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 0.7em; }
  #report th {
   
    padding: 5px; }
  #report table td {
    
    font-size: 0.8em; }
</style>

<title><?php echo xlt('General Receipts') ?></title>
</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">


<form method='post' action='editreceipts.php' id='theform'>
<div id="report_parameters">
<div id="hideonprint">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<input type='hidden' name='form_save' id='form_save' value=''/>
Enter Receipt Number to Delete: <input type='text' name='receipt_name' id='receipt_name' value=''/>
<input type='submit' name='submit' id='submit' value='submit'/>
</div>
 <!-- end of parameters -->
</div>
  

</form>

<?php
if($_POST['submit'])
{

$receipt_no=$_POST['receipt_name'];
$receipts="SELECT * from payments where receipt_id='$receipt_no' and activity=1";
	  $receipt=sqlStatement($receipts);
	  $rec=sqlFetchArray($receipt);
	  $authuser=$_SESSION["authUser"];
	  
if($rec['encounter']==null)
{
	 echo( "Please Enter the Proper Receipt Number!" );
}	
else
{
sqlStatement("UPDATE payments set activity=0 where receipt_id='$receipt_no' ");
	   
$r2=sqlInsert("INSERT into payments SET ".
	 " rec_ref= '" . add_escape_custom($receipt_no) . "', " .	 
	  "dtime= NOW(), " .
	  "pid = '" . add_escape_custom($rec['pid']) . "', " .
	    "encounter = '" . add_escape_custom($rec['encounter']) . "', " .
		"recreverseYN = 1, " .
		"activity = 0, " .
	  "method = '" . add_escape_custom($rec['method']) . "', " .
	    "source = '" . add_escape_custom($rec['source']) . "', " .
		"amount1 = '" . add_escape_custom(-($rec['amount1'])) . "', " .
	    "amount2 = '" . add_escape_custom(-($rec['amount2'])) . "', " .
		"posted1 = '" . add_escape_custom($rec['posted1']) . "', " .
	    "posted2 = '" . add_escape_custom($rec['posted2']) . "', " .
	  "user = '" . add_escape_custom($rec['user']). "', " .
	  "deletedby = '" . add_escape_custom($authuser). "'");
	  $totalrev=$rec['amount1']+$rec['amount2'];
	  sqlQuery("Update billing_main_copy set patient_paid=patient_paid - ? where encounter=?",array($totalrev,$encounter));   
	  
	  $qry2="CALL proc_reverseReceiptEntry('$receipt_no')";
	  $r3=sqlStatement($qry2);
 if($rec['amount1']==0)
	 {
		 $pay_total=$rec['amount2'];
	 }
	 else
	 {
		 $pay_total=$rec['amount1'];
	 }	  
	   $payment_id = idSqlStatement("insert into ar_session set "    .
					"payer_id = 0"       .
					", patient_id = ?"   .
					", user_id = ?"     .
					", closed = ?"      .
					", reference = ?"   .
					", check_date =  now() , deposit_date = now() "	.
					",  pay_total = ?"    .
					", payment_type = 'reverse'" .
					
					", adjustment_code = ?" .
					", post_to_date = now() " .
					", payment_method = ?",
					array($rec['pid'],$_SESSION['authUserID'],0,' ',(-($pay_total)),'reverse_payment',' '));
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
							", account_code = 'REV'",
							array($rec['pid'],$rec['encounter'],' ' ,' ',' ',0,$_SESSION['authUserID'],$payment_id,(-($pay_total)),0));
			sqlQuery("Update billing_main_copy set refund_amt=? where encounter=?",array($pay_total,$rec['encounter']));   
	  echo( "Delete Successful!" );
}
}

		
?>
</body>

<!-- stuff for the popup calendar -->

<!-- <link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
 top.restoreSession();-->
</script>
</html>

