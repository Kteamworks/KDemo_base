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
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}
</style>
<style type="text/css">
/* specifically include & exclude from printing */
h2{
    margin:0;
    padding:0;
}
h4{
    margin:0;
    padding:0;
}
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

<title><?php echo xlt('Birth Certificate') ?></title>
</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">
<form method='post' action='birth_certificate.php' id='theform'>
<?php
}
?>  <!-- End no export-->
<?php
if ($_POST['submit']) {
	
	$pid=$_SESSION['pid'];
	$encounter=$GLOBALS['encounter'];
	sqlInsert("INSERT into birth_certificate SET
	pid= '" . add_escape_custom($_POST["pid"]) . "',
	date= NOW(),
    birth_date          = '" . add_escape_custom($_POST["birth_date"]) . "',
	birth_date_sec      = '".add_escape_custom($_POST["birth_date_sec"])."',
	birth_time          = '" . add_escape_custom($_POST["birth_time"]) . "',
	birth_time_sec         = '" . add_escape_custom($_POST["birth_time_sec"]) . "',
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
$result = getAdmitData($pid, "*");
$result1= getPatientData($pid, "*");
}
$pid=$_SESSION['pid'];
$encounter=$GLOBALS['encounter'];
$medication=sqlStatement("SELECT * from lists where pid='".$pid."' and type='delivery'");
$medi=sqlFetchArray($medication);
$baby=$medi['sex'];
$baby1=$medi['sex_sec'];
if($result['encounter']==0)
{
	echo "Please Enter the Admission Details";
	
}else
if($medi['type']!='delivery'||$medi['type']==null)
{
	echo "Please Enter the Delivery Details";
	
}else 
if($result1['sex']=='Male')
{
	echo "Patient Should be Female";
}else
if($medi['birthdate']==0||$medi['birthtime']==0)
{
	echo "Please Enter the Birth Details";
}else
if(($result1['guardiansname']==null))
{
	echo "Please Enter the Husbands Name on Demographics";
}
else
{ 
   
 
 ?>
 <br><br><br><br><br><br><br>
<?php
// output each GET variable as a hidden form input
//echo "<center><h2 style='font-family:lucida calligraphy;color:red;'>".xlt("BIRTH CERTIFICATE")."</h2></center>";
echo "<center><h4 style='font-family:lucida calligraphy;'>".xlt("This is to Certify that a $baby Baby was Delivered by")."</h4></center>";
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}

?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
	$result1= getPatientData($pid, "*");
  }
	
	$patient_name=($result1['fname'])." ".($result1['mname'])." ".($result1['lname']);
	$encounter=$GLOBALS['encounter'];
    $pid=$_SESSION['pid'];
    $form_encounter=sqlStatement("SELECT * from form_encounter where pid='".$pid."' and encounter='".$encounter."'");
    $id=sqlFetchArray($form_encounter);
    $birth_date=$medi['birthdate'];
	$birth_date_sec=$medi['birthdate_sec'];
	$birth_time=$medi['birthtime'];
	$birth_time_sec=$medi['birthtime_sec'];
	  

   ?>
<input type="hidden" id="birth_date" name="birth_date" value='<?php echo attr($medi['birthdate']);?>'>
<input type="hidden" id="birth_date_sec" name="birth_date_sec" value='<?php echo attr($medi['birthdate_sec']);?>'>
<input type="hidden" id="birth_time" name="birth_time" value='<?php echo attr($medi['birthtime']);?>'>
<input type="hidden" id="birth_time_sec" name="birth_time_sec" value='<?php echo attr($medi['birthtime_sec']);?>'>
<input type="hidden" id="encounter" name="encounter" value='<?php echo attr($encounter);?>'>
<input type="hidden" id="pid" name="pid" value='<?php echo attr($pid);?>'>

<p style='font-family:lucida calligraphy;'>
Mrs. &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($patient_name);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
wife of Mr.&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['guardiansname']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
in this Hospital on&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted" style='font-family: calibri' ><b><i><?php echo text(date('d/M/y',strtotime($birth_date)));?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
&nbsp;&nbsp;&nbsp;&nbsp;at&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted" style='font-family: calibri' ><b><i><?php echo text(date('h:i:s A',strtotime($birth_time)));?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
The Above Information is Extracted from the Medical Record of this Hospital.
<br><br><br><br>
</p>
<span class="right">Medical Director&nbsp;&nbsp;&nbsp;&nbsp;</span>
<br>
<span class="right" style='font-family: lucida calligraphy'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dr.Moshin Pasha</span>


<?php if($birth_date_sec!=0 && $baby1!=null){ ?>

<div class="page-break"></div>
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>
 <br><br><br><br><br><br><br>
<?php
// output each GET variable as a hidden form input
//echo "<center><h2 style='font-family:lucida calligraphy;color:red;'>".xlt("BIRTH CERTIFICATE")."</h2></center>";
echo "<center><h4 style='font-family:lucida calligraphy;'>".xlt("This is to Certify that a $baby1 Baby was Delivered by")."</h4></center>";

?>
<p style='font-family:lucida calligraphy;'>
Mrs. &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($patient_name);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br><br>
wife of Mr.&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['guardiansname']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br><br>
in this Hospital on&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted" style='font-family: calibri' ><b><i><?php echo text(date('d/M/y',strtotime($birth_date_sec)));?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
&nbsp;&nbsp;&nbsp;&nbsp;at&nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted" style='font-family: calibri' ><b><i><?php echo text(date('h:i:s A',strtotime($birth_time_sec)));?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br><br>
The Above Information is Extracted from the Medical Record of this Hospital.
<br><br><br><br><br>
</p>
<span class="right">Medical Director&nbsp;&nbsp;&nbsp;&nbsp;</span>
<br>
<span class="right" style='font-family: lucida calligraphy'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dr.Moshin Pasha</span>
<?php
}
$printed=sqlStatement("SELECT printed from dealth_certificate where encounter='".$encounter."' and pid='".$pid."'");
$pri=sqlFetchArray($printed);
$pr=$pri['printed'];
if($pr==0)
{

?>
<div id="hideonprint" align="center"><!--<input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;-->
	 <input type='submit'  id='submit' name='submit' value="Print" onclick="printme()" class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />
 </div>
<?php
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

