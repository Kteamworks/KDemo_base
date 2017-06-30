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

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/classes/Address.class.php");
require_once("$srcdir/classes/InsuranceCompany.class.php");
require_once("$srcdir/formatting.inc.php");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
?>
<html>
<head>
<?php html_header_show();?>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>
<img src=" <?php echo $GLOBALS['webroot']?>/interface/pic/medii.jpg" />
<hr>
<body bgcolor='#ffffff'>
<center>
<p><h2><?php echo xlt('OUTPATIENT CARD'); ?></h2>
</center>
<?php function ageCalculator($dob){
	if(!empty($dob)){
		$birthdate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthdate->diff($today)->y;
		return $age;
	}else{
		return 0;
	}
}
$encounter=$_SESSION['encounter'];
$patdata = getPatientData($pid, 'age,age_days,age_months,title,sex,DOB,date,fname,mname,lname,pubpid,genericname1,street,city,state,postal_code');
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
$today = date("Y-m-d h:i:s A"); 
$time= date("H:i:s");
$day=sqlStatement("select dayname('$today') day");
$days=sqlFetchArray($day);
$dayy=$days['day'];
$payrow = sqlQuery("SELECT * from payments where pid=? and encounter=?", array($pid,$encounter) );
$dob =text($patdata['DOB']) ;
$enc=sqlStatement("select * from form_encounter where encounter='".$payrow['encounter']."'");
$enc1=sqlFetchArray($enc);
$provider=$enc1['provider_id'];
$row1 = sqlStatement("SELECT * from users where id='".$provider."'");
$row2=  sqlFetchArray($row1);

?>
<p>
<table border='0' style="width:100%">
 <tr>

  <td><?php echo xlt('Date'); ?> &nbsp:&nbsp<?php echo text(date('d/M/y h:i:s A',strtotime($payrow['dtime']))) ?>
  <td><?php echo xlt('Patient Name'); ?> &nbsp:&nbsp<b><?php echo text($patdata['title'])." ".text($patdata['fname']) . " " . text($patdata['mname']) . " " .
       text($patdata['lname'])?></b></td>
 </tr>
 <tr>
 <td><?php echo xlt('Patient ID'); ?>&nbsp:&nbsp<?php echo text($patdata['genericname1']) ?></td>
 <td><?php echo xlt('Patient Visit ID'); ?> &nbsp:&nbsp<?php echo text($enc1['encounter_ipop']) ?></td>
 </tr>
 <tr>
 
 <td><?php echo xlt('Registration Date'); ?>&nbsp:&nbsp<?php echo text(date('d/M/y h:i:s A',strtotime($patdata['date']))) ?></td>
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
		echo "<td>" . xlt('Age/Gender') . ": " . text($age_days) ." ".xlt('Days')." , ".text($patdata['sex']). "</td>";
	}
?>	
 </tr>
  <tr>
 <td><?php echo xlt('Doctor Name'); ?>&nbsp:&nbsp<?php echo text($row2['username'])?></td>
 <td><?php echo xlt('Department'); ?> &nbsp:&nbsp<?php echo text($row2['specialty'])?></td>
 </tr>

 <tr>
 <td><?php echo xlt('Receipt ID'); ?>&nbsp:&nbsp<?php echo text($payrow['receipt_id']) ?></td>
 <td><?php echo xlt('Receipt Date'); ?> &nbsp:&nbsp<?php echo text(date('d/M/y h:i:s A',strtotime($payrow['dtime'])))?></td>
 </tr>
 <tr>
<td><?php echo xlt('Amount Paid'); ?>&nbsp:&nbsp<?php echo text($payrow['amount1']) ?></td>
</tr>

 </table>
<body ><hr>
<table  border="0">

	
	<tr>
		<td align="left" class="forms"><b><?php echo xlt('Presenting Complaints'); ?>:</b></td>
		</tr>
		<tr>
		<td colspan="10"><textarea name="presenting_complaints" rows="5" cols="145" wrap="virtual name"><?php echo text($obj{"presenting_complaints"});?></textarea></td>
		</tr>
		<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	<tr>
		<td align="left" class="forms"><b><?php echo xlt('O/E'); ?>:</b></td>
	</tr>
	<tr>
		<td colspan="10"><textarea name="oe" rows="5" cols="145" wrap="virtual name"><?php echo attr($oe);?></textarea></td>
			</tr>
				<tr>
		<td align="left" class="forms"><b><?php echo xlt('Investigations'); ?>:</b></td>
	</tr>
	<tr>
		<td colspan="10"><textarea name="investigations" rows="5" cols="145" wrap="virtual name"><?php echo attr($investigations);?></textarea></td>
			</tr>
						<tr>
		<td align="left" class="forms"><b><?php echo xlt('Provisional Diagnosis'); ?>:</b></td>
	</tr>
	<tr>
		<td colspan="10"><textarea name="provisional_diagnosis" rows="5" cols="145" wrap="virtual name"><?php echo attr($provisional_diagnosis);?></textarea></td>
			</tr>
			<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
						<tr>
		<td align="left" class="forms"><b><?php echo xlt('Treatment Advised'); ?>:</b></td>
	</tr>
	<tr>
		<td colspan="10"><textarea name="treatment_advised" rows="31" cols="145" wrap="virtual name"><?php echo attr($treatment_advised);?></textarea></td>
			</tr>
			<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	
 <tr>
		<td align="left" class="forms"><b><?php echo xlt('Reffered To'); ?>:</b></td>
	</tr>
	<tr>
		<td colspan="2"><textarea name="reffered_to" rows="2" cols="145" wrap="virtual name"><?php echo attr($reffered_to);?></textarea></td>
			</tr>
			<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	<tr>
		<td align="right" class="forms"><b><?php echo xlt('Signature of Resident Doctor'); ?></b></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><textarea align="right" rows="2" cols="30" wrap="virtual name"></textarea></td>
			</tr>
	<tr>
	<td align="center" class="forms"><b><?php echo xlt('Note:This card is valid for three days for the same ailment'); ?></b></td>
	<tr>
<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	
	<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	<tr>
		<td></td>
	
    <td>
 </td>

	</tr>
</table>
 <div id="hideonprint" align="center"><!--<input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;-->
	 <input type='button'  value="Print" onclick="printme()" class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />
 </div>
</form>


<script type="text/javascript">

$("#admit_to_ward").change(function () {
    var sel = $("#admit_to_ward").prop("selectedIndex") - 1;
    var list = AnimalTypeList[sel].split(",");
    var Counter = list.length;
    $("#sample").children().remove();
    for (var i = 0; i < Counter; i++) {
        $("#sample").append("<option value = '" + list[i] + "'> " + list[i] + "</option>");
    }    
})
function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>
<?php
formFooter();
?>
