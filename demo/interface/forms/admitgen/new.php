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
 $sanitize_all_escapes=true;

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=false;
 
include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
formHeader("Form:Admit");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$obj = $formid ? formFetch("t_form_admit", $formid) : array();

?>
<html>
<head>
<?php html_header_show();?>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-functions.js"> //optional!!</script>
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script>
function validateForm() {
    var x = document.forms["my_form"]["admit_to_ward"].value;
	 var y = document.forms["my_form"]["admit_to_bed"].value;
    if (x == null || x == "") {
        alert("Please select the Ward");
        return false;
    }
	 if (y == null || y == "") {
        alert("Please select the Bed");
        return false;
    }
}
</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>

<body class="body_top">
<p><span class="forms-title"><?php echo xlt('Admission Details'); ?></span></p>
</br>
<?php
echo "<form method='post' name='my_form' " .
  "action='$rootdir/forms/admit/save.php?id=" . attr($formid) ."' onsubmit='return validateForm()'>\n";
?>
<table  border="0">
<tr>
<td>
<input type="hidden" name="pid" value="<?php echo $GLOBALS['pid']?>"/>
</td>
<td>
<input type="hidden" name="encounter" value="<?php echo $GLOBALS['encounter']?>"/>
</td>
</tr>
<tr>
<td align="left" class="forms" class="forms"><b><?php echo xlt('Patient Name' ); ?>:</td>
		<td class="forms">
			<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getPatientData($pid, "fname,lname,squad,street,city,state,postal_code,locality,phone_cell");
   echo text($result['fname'])." ".text($result['lname']);}
   $patient_name=($result['fname'])." ".($result['lname']);
   
   $address=($result['locality']).", ".($result['street']).", ".($result['city']).", ".($result['state']).", ".($result['postal_code']);
   $pc=$result['phone_cell'];
   ?>
   </label>
   <input type="hidden" name="client_name" value="<?php echo attr($patient_name);?>">
		</td>
		
		<td align="left" class="forms" class="forms"><b><?php echo xlt('Hospital Number' ); ?>:</td>
		<td class="forms">
			<label class="forms-data"> <?php echo text($GLOBALS['encounter']); ?></label>
				<input type="hidden" name="client_name" value="<?php echo attr($GLOBALS['encounter']);?>">
		</td>
</tr>
<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
</tr>
<tr>
		<td align="left" class="forms" class="forms"><b><?php echo xlt('Permanent Address' ); ?>:</td>
		<td class="forms">
		<label class="forms-data"> <?php echo text($address); ?></label>
		<input type="hidden" name="loc" value="<?php echo text($address);?>">
		</td>
		
		<td align="left" class="forms" class="forms"><b><?php echo xlt('Mobile'); ?>:</td>
		<td class="forms"> <label class="forms-data"> <?php echo text($pc); ?></label>
		<input type="hidden" name="pc" value="<?php echo text($pc);?>">
		</td>
</tr>	
<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
</tr>	
<?php 
		$today = date('Y-m-d',strtotime("+0 days"));
		
		$tomorrow = date('Y-m-d',strtotime("+1 days"));
		?>
		 <tr>
		<td align="left" class="forms"><b><?php echo xlt('Admit date'); ?>:</td>
	   	<td class="forms">
			   <input type='text' size='10' name='admit_date' id='admit_date' <?php echo attr ($disabled)?>;
       value='<?php echo attr($today); ?>'
       title='<?php echo xla('yyyy-mm-dd Date of Admit'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_transfer_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>
		
		<td align="left" class="forms"><b><?php echo xlt('Discharge date'); ?>:</td>
	   	<td class="forms">
		
			   <input type='text' size='10' name='discharge_date' id='discharge_date' <?php echo attr ($disabled)?>;
       value='<?php echo attr($tomorrow); ?>' 
       title='<?php echo xla('yyyy-mm-dd Date of Discharge'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_end_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>

	</tr>
	<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
    </tr>	
	<tr>
	<td>
 	</td>
		
				
	
	

<?php
$eee=$GLOBALS['encounter'];
$PPD=$GLOBALS['pid'];
$l="discharge";
$queryy="select status,pid,admit_to_bed,admit_to_ward FROM t_form_admit where encounter='".$eee."'";
$qqueryy="select status,pid,admit_to_bed,admit_to_ward FROM t_form_admit where pid='".$PPD."' and status!='".$l."'";
$resy = sqlStatement($queryy);
$resulty = sqlFetchArray($resy);
$rey1 = sqlStatement($qqueryy);
$resy1 = sqlFetchArray($rey1);

if($resulty!=0)
{
	echo "Patient has already been admitted to ward ".$resulty['admit_to_ward']." - ".$resulty['admit_to_bed']."";
	exit;
}

else if($resy1!=0)
{
    echo "Patient has already admitted with different encounter";
	exit;
}
else
?>

	</tr>
	<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	
	
	<tr>
	    <td align="left" class="forms"><b><?php echo xlt('Occupation'); ?>:</b></td>
		<td><input type="text" name="occ" value=""></input></td>
		<td align="left" class="forms"><b><?php echo xlt('Clinical Diagnosis'); ?>:</b></td>
		
		<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getDiagnosis($pid, "*");
   }
   $diagnosis=($result['title']);
   ?>
   </label>
		<td colspan="3"><input name="diagnosis" rows="3" cols="60" wrap="virtual name"><?php echo attr($diagnosis);?></textarea></td>
			</tr>
			
	<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>		
	<tr>
	    <td align="left" class="forms"><b><?php echo xlt('Department'); ?>:</b></td>
		<td><input name="dept" ><?php echo attr($department);?></input></td>
		<td align="left" class="forms"><b><?php echo xlt('Brought By'); ?>:</b></td>
		<td><input name="brgby" ><?php echo attr($brgby);?></input></td>
	</tr>
			
	<tr>
	
	<td><a href='emptybeds.php'> <img src="bed.png" align='center' height='42', width='40',name="nothing" readonly></img></a><b><?php echo xlt('Ward/Bed'); ?>:
	
	<input type=hidden name ="adm_to" value=<?php echo $_POST['adm_to']; ?> > </input>
	<?
	$bid23=$_POST['adm_to'];
	$quer23="select * FROM list_options where option_id='".$bid23."'";

	$res23 = sqlStatement($quer23);
	$result23 = sqlFetchArray($res23);
	$admwdd=$result23['list_id'];
	$admbd=$result23['option_id'];
	
	echo "<td>"."<input type=\"text\" name=\"admit_to_ward\" size=\"10\" value=\"$admwdd\" readonly>"."</input>";
	
	echo ""."<input type=\"text\" name=\"admit_to_bed\" size=\"10\"  value=\"$admbd\" readonly></input>"."</td>"; 
	
	?>
	</tr>		<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	
<tr> 
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	
	<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	<tr>
		<td></td>
    <td><input type='submit'  value='<?php echo xlt('Save');?>' class="button-css">&nbsp;
	 <input type='button'  value="Print" onclick="window.print()" class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />

 </td>
	</tr>
</table>
</form>

<script language="javascript">
/* required for popup calendar */
Calendar.setup({inputField:"admit_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_transfer_date",showsTime:'true'});
Calendar.setup({inputField:"discharge_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_end_date",showsTime:'true'});
</script>
<?php
formFooter();
?>
