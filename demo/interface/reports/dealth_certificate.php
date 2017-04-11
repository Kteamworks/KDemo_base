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

$grand_total_units  = 0;
$grand_total_amt_billed  = 0;
$grand_total_amt_paid  = 0;
$grand_total_amt_adjustment  = 0;
$grand_total_amt_balance  = 0;


  if (! acl_check('acct', 'rep')) die(xlt("Unauthorized access."));

  $INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;
  $returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

  if (!$INTEGRATED_AR) SLConnect();

  $form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
  $form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
  $form_facility  = $_POST['form_facility'];
  $form_provider  = $_POST['form_provider'];
  $form_des=$_POST['form_des'];
  if ($_POST['form_csvexport']) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=payment_voucher_".attr($form_from_date)."--".attr($form_to_date).".csv");
    header("Content-Description: File Transfer");
    // CSV headers:
    } // end export
  else {
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

.right{
    float:right;
}

.left{
    float:left;
}
u.dotted{
  border-bottom: 1px dotted #999;
  text-decoration: none; 
}
hr.dotted1 {
 
  width:80%;
}

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
       margin-top:10px;
    }
	
	#report {
       margin:10px;
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
      font-size: 1.0em; }

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
    font-size: 1.0em; }
  #report_results table th {
   
    padding: 5px; }
  #report_results table td {
    padding: 5px;
    
    font-size: 1.0em; }

	
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
    font-size: 1.0em; }
  #report th {
   
    padding: 5px; }
  #report table td {
    
    font-size: 1.0em; }
</style>

<title><?php echo xlt('Death Certificate') ?></title>
</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">
<form method='post' action='dealth_certificate.php' id='theform'>
<?php
}
?>  <!-- End no export-->
<?php
if ($_POST['submit']) {
	
	$pid=$_SESSION['pid'];
	$encounter=$GLOBALS['encounter'];
	sqlInsert("INSERT into dealth_certificate SET
	pid= '" . add_escape_custom($_POST["pid"]) . "',
	date= NOW(),
    admit_date          = '" . add_escape_custom($_POST["admit_date"]) . "',
    date_deceased          = '" . add_escape_custom($_POST["date_deceased"]) . "',
    deceased_reason      ='".add_escape_custom($_POST["deceased_reason"])."',
    done_by = '" . $_SESSION["authUser"] . "',
	printed             = 1,
    encounter          = '" . add_escape_custom($_POST["encounter"]) . "'");
	
?>
<?php
}
?>  
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>

<?php
if (is_numeric($pid)) {  
$result1= getPatientData($pid, "*");
}
if(($result1['deceased_date']==0)&&($result1['deceased_reason']==0))
{
	echo "Please Enter the Deceased Date and the Reason in Demographics First";
}
else
{ 
   
 
 ?>
<?php
// output each GET variable as a hidden form input
echo "<center><h4><u>".xlt("DEATH CERTIFICATE")."</u></h4></center>";
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}

?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
	$result1= getPatientData($pid, "*");
  }
   $admit_to_ward=($result['admit_to_ward']);
    $admit_to_bed=($result['admit_to_bed']);
	$admit_date=($result['admit_date']);
	$status=$result['status'];
	$discharge_date=($result['discharge_date']);
	$patient_name=($result1['fname'])." ".($result1['mname'])." ".($result1['lname']);
	$age=$result1['age'];
   $age_months=$result1['age_months'];
   $age_days=$result1['age_days'];
   $encounter=$GLOBALS['encounter'];
   $pid=$_SESSION['pid'];
 
   $form_encounter=sqlStatement("SELECT * from form_encounter where pid='".$pid."' and encounter='".$encounter."'");
   $id=sqlFetchArray($form_encounter);
   $slno=sqlStatement("SELECT * from dealth_certificate where pid='".$pid."' and encounter='".$encounter."'");
   $sl=sqlFetchArray($slno);
	$sl1=$sl['id'];
	   if ($_POST['submit'])
{
   ?>

  <span class=left><?php echo xlt('Sl No.')?>:<?php echo attr($sl1);?></span>
<?php }?>
  <p align=right>Date: <?php echo text(date('d/M/y h:i:s A'));?></p>
  <p align=right>IP/OP No. :&nbsp;&nbsp;<?php echo attr($id['encounter_ipop']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
  
 <input type="hidden" id="date_deceased" name="date_deceased" value='<?php echo attr($result1['deceased_date']);?>'>
<input type="hidden" id="deceased_reason" name="deceased_reason" value='<?php echo attr($result1['deceased_reason']);?>'>
<input type="hidden" id="admit_date" name="admit_date" value='<?php echo attr($admit_date);?>'>
<input type="hidden" id="encounter" name="encounter" value='<?php echo attr($encounter);?>'>
<input type="hidden" id="pid" name="pid" value='<?php echo attr($pid);?>'>

<p>
Name &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($result1['title'])?><?php echo attr($patient_name);?> ( <?php echo attr($result1['genericname1']);?> )&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Father's / Husband's Name :&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['guardiansname']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Mother's Name :&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['mothersname']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>Age<b><i>&nbsp;&nbsp;&nbsp;&nbsp;
<u class="dotted">
<?php if($age!=0)
	{
	  echo attr($age);echo attr(Years);
	}else
	if($age_months!=0)
	{
	  echo attr($age_months);echo attr(Months);
	}else
	{
		echo attr($age_days);echo attr(Days);
	}?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Sex &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['sex'])?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Address &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['street']);?>&nbsp;,&nbsp;<?php echo attr($result1['city']);?>&nbsp;-&nbsp;<?php echo attr($result1['postal_code']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Admission Date &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo text(date('d/M/y h:i:s A',strtotime($admit_date)));?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date of Dealth &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo text(date('d/M/y h:i:s A',strtotime($result1['deceased_date'])));?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Cause of Death &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['deceased_reason'])?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Doctor's Name:
</p>
<span class=left><?php echo xlt('KMC No.')?></span>
<span class=right>Doctor's Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<?php
$printed=sqlStatement("SELECT printed from dealth_certificate where encounter='".$encounter."' and pid='".$pid."'");
$pri=sqlFetchArray($printed);
$pr=$pri['printed'];

if ($_POST['submit'])
{
?>
<div id="hideonprint" align="center"><!--<input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;-->
	 <input type='button'  id='print' name='print' value="Print" onclick="printme()" class="button-css">&nbsp;
</div>

<?php } else {
	if($pr==0)
{?>

 <div id="hideonprint" align="center"><!--<input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;-->
	 <input type='submit'  id='submit' name='submit' value="Submit"  class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />
 </div>
<?php
}
}
}?>


</form>
</body>

<!-- stuff for the popup calendar -->

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 //Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 //top.restoreSession();
</script>
<script language="javascript">
// jQuery stuff to make the page a little easier to use

function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>
</html>

