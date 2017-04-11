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
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
?>

<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>

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

#watermark {
  color: #d0d0d0;
  font-size: 120pt;
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  position: absolute;
 
  margin: 0;
  z-index: -1;
  left: 300px;
  top:200px;
}
.right{
    float:right;

}
.center{
	text-align: center;
    float: right;
	
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

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">


<form method='post' action='discharge_clearence_copy.php' id='theform'>
<div id="report_parameters">
<div id="hideonprint">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<input type='hidden' name='form_save' id='form_save' value=''/>
Enter Encounter Number for Discharge Clearence: <input type='text' name='encounter_no' id='encounter_no' value=''/>
<input type='submit' name='submit' id='submit' value='Submit'/>
<input type='button' class="button-css" value="Print" onclick="printme()" class="button-css">
</div>
 <!-- end of parameters -->
</div>
  

</form>

<?php
if($_POST['submit'])
{

$encounter=$_POST['encounter_no'];
$encounters="SELECT * from discharge_clearence where encounter='".$encounter."'";
	  $encounter=sqlStatement($encounters);
	  $enc=sqlFetchArray($encounter);
	  $pid=$enc['pid'];
	  $authuser=$_SESSION["authUser"];
	  
if($enc['encounter']==null)
{
	 echo( "The Encounter entered is not there in the List of Discharge Clearence!" );
}	
else
{
 sqlStatement("update discharge_clearence set printed=2 ,reprinted_by='" . $_SESSION["authUser"] . "' where encounter='".$enc['encounter']."'");

 ?>
<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/discharge_clearance_copy.php" name="my_form" id="my_form">
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" style="margin:0px 0px" align="top"/>
<hr>

<?php
// output each GET variable as a hidden form input
echo "<center><h4><u>".xlt("DISCHARGE CLEARANCE")."</u></h4></center>";
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}

?>

<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
	 
	   $pid=$enc['pid'];
     $encounter=$_POST['encounter_no'];
    $ressult = sqlStatement("SELECT * from t_form_admit where encounter='$encounter'");
	$result=sqlFetchArray($ressult);
	$result1= getPatientData($pid, "*");
  }
   $authuser=$_SESSION["authUser"];
   $admit_to_ward=($result['admit_to_ward']);
    $admit_to_bed=($result['admit_to_bed']);
	$admit_date=($result['admit_date']);
	$discharge_date=($result['discharge_date']);
	$patient_name=($result1['fname'])." ".($result1['mname'])." ".($result1['lname']);
	$age=$result1['age'];
   $age_months=$result1['age_months'];
   $age_days=$result1['age_days'];
  $encounter=$_POST['encounter_no'];
  $pid=$enc['pid'];
   $billing=sqlStatement("select sum(fee) fees,bill_id from billing  where encounter='".$encounter."' and activity=1");
   $billid=sqlFetchArray($billing);
   $lastpaid=sqlStatement("select amount1,amount2 from payments where encounter='".$encounter."' and activity=1 order by receipt_id desc limit 1");
   $last=sqlFetchArray($lastpaid);
   if($last['amount1']==0)
	 {
		 $amt=$last['amount2'];
	 }
	 else
	 {
		 $amt=$last['amount1'];
	 }
	
   ?>
  
  <p align=right>Admission Date: <?php echo text(date('d/M/y h:i:s A',strtotime($admit_date)));?></p>
  <p align=right>Discharge Date: <?php echo text(date('d/M/y h:i:s A',strtotime($discharge_date)));?></p>
  
 <input type="hidden" id="bill_id" name="bill_id" value='<?php echo attr($billid['bill_id']);?>'>
<input type="hidden" id="amount" name="amount" value='<?php echo attr($billid['fees']);?>'>
<input type="hidden" id="encounter" name="encounter" value='<?php echo attr($encounter);?>'>

<p>
Name &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($result1['title'])?><?php echo attr($patient_name);?> ( <?php echo attr($result1['genericname1']);?> )</u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;<br><br>Age<b><i>&nbsp;&nbsp;&nbsp;&nbsp;
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
	}?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;
Sex &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($result1['sex'])?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
Ward &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($admit_to_ward);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
<br><br>
Against the Bill No. &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($billid['bill_id']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>
Amount &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($billid['fees']);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b> Last Amount Paid &nbsp;&nbsp;&nbsp;&nbsp;<u class="dotted"><b><i><?php echo attr($amt);?>&nbsp;&nbsp;&nbsp;&nbsp;</u></i></b>   Self / Corporate / Insurance / Hospital Employee / Due Bill&nbsp;&nbsp;&nbsp;&nbsp;
<br><br>
This is to inform you that the patient &nbsp;&nbsp;&nbsp;&nbsp;<b><i><u class="dotted"><?php echo attr($result1['title'])?><?php echo attr($patient_name);?></u></i></b>&nbsp;&nbsp;&nbsp;&nbsp;can be discharged from the ward.
<br><br>
Remarks ______________________________________________________________
</p>
<br><br><br><h4><span class=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo xlt('Discharged by')?></span>
<h4><span class=right><?php echo xlt('Authorised by')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<p align="center"><?php echo xlt('RePrinted By')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p align="center">(<?php echo attr($authuser);?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<br><br>
<div id="watermark">
<p>COPY</p>
</div>
<p align="center"><b>Note: If the Amount Paid is less than the Bill Amount then the Authorized Signature is Mandatory</b></p>
<?php 
}
}
?>
</form>

</body>

</html>

