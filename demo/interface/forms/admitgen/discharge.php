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
formHeader("Form:Discharge Form");
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$obj = $formid ? formFetch("t_form_admit", $formid) : array();

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

<body class="body_top">
<p><span class="forms-title"><?php echo xlt('Discharge Summary'); ?></span></p>
</br>
<?php
echo "<form method='post' name='my_form' " .
  "action='save.php'>\n";
?>

  
		<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
   echo text($result['admit_to_ward']);}
   $admit_to_ward=($result['admit_to_ward']);
   ?>
   </label>
     <input type="hidden" name="admit_to_ward" value="<?php echo attr($admit_to_ward);?>">
	
		
		<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
   echo text($result['admit_to_bed']);}
   $admit_to_bed=($result['admit_to_bed']);
   ?>
   </label>
     <input type="hidden" name="admit_to_bed" value="<?php echo attr($admit_to_bed);?>">
		
		<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
   echo text($result['admit_date']);}
   $admit_date=($result['admit_date']);
   ?>
   </label>
     <input type="hidden" name="admit_date" value="<?php echo attr($admit_date);?>">
		</td>
		</tr>
	<tr>
		<td align="left"  class="forms"><?php echo xlt('Discharge Date'); ?>:</td>
		<td class="forms">
		<label class="forms-data"> <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
   echo text($result['discharge_date']);}
   $discharge_date=($result['discharge_date']);
   ?>
   </label>
     <input type="hidden" name="discharge_date" value="<?php echo attr($discharge_date);?>">
		</td>
		</tr>
	<tr>
		<td align="left" class="forms"><b><?php echo xlt('Status Of Discharge'); ?>:</b></td>
		<td colspan="3"><textarea name="status_of_admission" rows="3" cols="60" wrap="virtual name"><?php echo text($obj{"status_of_admission"});?></textarea></td>
		</tr>
		<tr>
		<td align="left colspan="3" style="padding-bottom:7px;"></td>
	</tr>
	<td><input type='submit'  name="discharge" value='<?php echo xlt('Discharge');?>' class="button-css">&nbsp;
	
	 <input type='button'  value="Print" onclick="window.print()" class="button-css">&nbsp;
	<input type='button' class="button-css" value='<?php echo xlt('Cancel');?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />

 </td>
 </tr>
</table>
</form>
	
