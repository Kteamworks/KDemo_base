<?php
/**
 * Common script for the encounter form (new and view) scripts.
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

require_once("$srcdir/options.inc.php");

$months = array("01","02","03","04","05","06","07","08","09","10","11","12");
$days = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14",
  "15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
$thisyear = date("Y");
$years = array($thisyear-1, $thisyear, $thisyear+1, $thisyear+2);

if ($viewmode) {
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
  $result = sqlQuery("SELECT * FROM form_encounter WHERE id = ?", array($id));
  $encounter = $result['encounter'];
  if ($result['sensitivity'] && !acl_check('sensitivities', $result['sensitivity'])) {
    echo "<body>\n<html>\n";
    echo "<p>" . xlt('You are not authorized to see this encounter.') . "</p>\n";
    echo "</body>\n</html>\n";
    exit();
  }
}

// Sort comparison for sensitivities by their order attribute.
function sensitivity_compare($a, $b) {
  return ($a[2] < $b[2]) ? -1 : 1;
}

// get issues
$ires = sqlStatement("SELECT id, type, title, begdate FROM lists WHERE " .
  "pid = ? AND enddate IS NULL " .
  "ORDER BY type, begdate", array($pid));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Patient Obstetric Record'); ?></title>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-functions.js"> //optional!!</script>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" />
<!--<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.4.3.min.js"></script>-->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/overlib_mini.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>

<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/ajax/facility_ajax_jav.inc.php"); ?>
<script language="JavaScript">

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 // Process click on issue title.
 function newissue() {
  dlgopen('../../patient_file/summary/add_edit_issue.php', '_blank', 800, 600);
  return false;
 }

 // callback from add_edit_issue.php:
 function refreshIssue(issue, title) {
  var s = document.forms[0]['issues[]'];
  s.options[s.options.length] = new Option(title, issue, true, true);
 }

 function saveClicked() {
  var f = document.forms[0];

<?php if (!$GLOBALS['athletic_team']) { ?>
  var category = document.forms[0].pc_catid.value;
  if ( category == '_blank' ) {
   alert("<?php echo xls('You must select a visit category'); ?>");
   return;
  }
<?php } ?>

  top.restoreSession();
  f.submit();
 }

$(document).ready(function(){
  enable_big_modals();
});
function bill_loc(){
var pid=<?php echo attr($pid);?>;
var dte=document.getElementById('form_date').value;
var facility=document.forms[0].facility_id.value;
ajax_bill_loc(pid,dte,facility);
}

// Handler for Cancel clicked when creating a new encounter.
// Show demographics or encounters list depending on what frame we're in.
function cancelClicked() {
 if (window.name == 'RBot') {
  parent.left_nav.setRadio(window.name, 'ens');
  parent.left_nav.loadFrame('ens1', window.name, 'patient_file/history/encounters.php');
 }
 else {
  parent.left_nav.setRadio(window.name, 'dem');
  parent.left_nav.loadFrame('dem1', window.name, 'patient_file/summary/demographics.php');
 }
 return false;
}
function getRatePlan(plan)
{
	var plan= document.getElementById('rateplan').value;
	if(plan=="TPA Insurance")
	{
		document.getElementById('instpa').style.display = '';
	}
	else
	{
		document.getElementById('instpa').style.display = 'none';
	}
	
}
</script>
</head>

<?php if ($viewmode) { ?>
<body class="body_top">
<?php } else { ?>
<body class="body_top" onload="javascript:document.new_encounter.reason.focus();">
<?php } ?>

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<form method='post' action="<?php echo $rootdir ?>/forms/newobspatient/save.php" name='new_encounter'
 <?php if (!$GLOBALS['concurrent_layout']) echo "target='Main'"; ?>>

<div style = 'float:left'>
<?php if ($viewmode) { ?>
<input type=hidden name='mode' value='update'>
<input type=hidden name='id' value='<?php echo (isset($_GET["id"])) ? attr($_GET["id"]) : '' ?>'>
<span class=title><?php echo xlt('Obstetric Visit Record Card'); ?></span>
<?php } else { ?>
<input type='hidden' name='mode' value='new'>
<span class='title'><?php echo xlt('Obstetric Visit Record Card'); ?></span>
<?php } ?>
</div>

<div>
    <div style = 'float:left; margin-left:8px;margin-top:-3px'>
      <a href="javascript:saveClicked();" class="css_button link_submit"><span><?php echo xlt('Save'); ?></span></a>
      <?php if ($viewmode || !isset($_GET["autoloaded"]) || $_GET["autoloaded"] != "1") { ?>
    </div>

    <div style = 'float:left; margin-top:-3px'>
  <?php if ($GLOBALS['concurrent_layout']) { ?>
      <a href="<?php echo "$rootdir/patient_file/encounter/encounter_top.php"; ?>"
        class="css_button link_submit" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
  <?php } else { ?>
      <a href="<?php echo "$rootdir/patient_file/encounter/patient_encounter.php"; ?>"
        class="css_button link_submit" target='Main' onClick="top.restoreSession()">
      <span><?php echo xlt('Cancel'); ?>]</span></a>
  <?php } // end not concurrent layout ?>
  <?php } else if ($GLOBALS['concurrent_layout']) { // not $viewmode ?>
      <a href="" class="css_button link_submit" onClick="return cancelClicked()">
      <span><?php echo xlt('Cancel'); ?></span></a>
  <?php } // end not $viewmode ?>
    </div>
 </div>
<br><br>

<table width='56%'>
<tr></tr>
<tr>
       <td><span class='bold'>Visit Type: </span></td>
     <td><span class='bold'>
      <select name='pc_catid' id='pc_catid'>
	<option value='_blank'>-- <?php echo xlt('Select One'); ?> --</option>
<?php
 $cres = sqlStatement("SELECT pc_catid, pc_catname " .
  "FROM openemr_postcalendar_categories where pc_catid in (19,20,21) ORDER BY pc_catname");
 while ($crow = sqlFetchArray($cres)) {
  $catid = $crow['pc_catid'];
  if ($catid < 9 && $catid != 5) continue;
  echo "       <option value='" . attr($catid) . "'";
  if ($viewmode && $crow['pc_catid'] == $result['pc_catid']) echo " selected";
  echo ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
 }
?>
      </select>
     </td>
    </tr>	
<tr><td><span class=bold>Cousanguinity: </span></td><td><input type=entry name="cons" value="" ></td>
<td><span class=bold>Married Life: </span></td><td><input type=entry name="ml" value="" ></td></tr>
<tr><td><span class=bold>LMP: </span></td><td><?php echo generate_select_list('lmp', 'lmp', $viewmode ? $result['lmp'] : '', ''); ?></td>
<td><span class=bold>LMP Date: </span></td><td><input type=entry name='lmpd' size=10 value='YYYY-MM-DD' ></td>
</tr>
<tr>
<td><span class=bold>Menstrual History: </span></td><td><?php echo generate_select_list('mens_cycle', 'menhist', $viewmode ? $result['mens_cycle'] : '', ''); ?></td><td><span class=bold>EDD: </span></td><td><input type=entry name='edd' size=10 value='YYYY-MM-DD' ></td></tr>
<tr>
<td><span class=bold>Gravida: </span></td><td><input type=entry name='gravida' size=10 value="" ></td><td></td><td></td></tr>
<tr><td><span class=bold>Para: </span></td><td><input type=entry name="para" size=10 value="" ></td><td></td><td></td></tr>
<td><span class=bold>Living Children: </span></td><td><input type=entry name="livingchildren" size="2" value="" ></td><td></td><td></td></tr>

<tr><td><span class=bold>Age at First Pregnancy: </span></td><td><input type=entry name="aafp" size="2"  value="" ></td></tr>

<tr>
<td><span class=bold>Total No. of Pregnancies: </span></td><td><input type=entry name='tnop' size="2"  value="" ></td></tr>
<tr><td><span class=bold>Number of Vaginal Deliveries: </span></td><td><input type=entry name="novd" size="2"  value="" ></td></tr>

<tr>
<td><span class=bold>Gestational Age: </span></td><td><input type=entry name="ga" size="2" value="" ></td>
<td><span class=bold>Gest Age By: </span></td><td><?php echo generate_select_list('gest_age_by', 'gest_age_by', $viewmode ? $result['gest_age_by'] : '', ''); ?></td>
</tr>



	
<!-- <tr><td><span class=bold>Previous Obstetric History: </span></td><td><?php echo generate_select_list('gen_obs_hist', 'genobshist', $viewmode ? $result['gen_obs_hist'] : '', ''); ?></td><td></td></tr> -->
</table>

<p>




<table width='56%'>

 


</table>

</form>

</body>

<script language="javascript">
/* required for popup calendar */
//Calendar.setup({inputField:"form_date", ifFormat:"%Y-%m-%d", button:"img_form_date"});
//Calendar.setup({inputField:"form_onset_date", ifFormat:"%Y-%m-%d", button:"img_form_onset_date"});
<?php
if (!$viewmode) { ?>
 function duplicateVisit(enc, datestr) {
     $.jAlert({'type': 'confirm', 'confirmQuestion': 'A visit already exists for this patient today. Click NO to open it, or YES to proceed with creating a new one.','!onConfirm': function(){
       
  }, 'onDeny': function(){
    top.restoreSession();
            parent.left_nav.setEncounter(datestr, enc, window.name);
            parent.left_nav.setRadio(window.name, 'enc');
            parent.left_nav.loadFrame('enc2', window.name, 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
            return;     
  } });
   /* if (!confirm('<?php echo xl("A visit already exists for this patient today. Click Cancel to open it, or OK to proceed with creating a new one.") ?>')) {
            // User pressed the cancel button, so re-direct to today's encounter
            top.restoreSession();
            parent.left_nav.setEncounter(datestr, enc, window.name);
            parent.left_nav.setRadio(window.name, 'enc');
            parent.left_nav.loadFrame('enc2', window.name, 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
            return;
        }*/
        // otherwise just continue normally
    }    
<?php

  // Search for an encounter from today
  $erow = sqlQuery("SELECT fe.encounter, fe.date " .
    "FROM form_encounter AS fe, forms AS f WHERE " .
    "fe.pid = ? " . 
    " AND fe.date >= ? " . 
    " AND fe.date <= ? " .
    " AND " .
    "f.formdir = 'newobspatient' AND f.form_id = fe.id AND f.deleted = 0 " .
    "ORDER BY fe.encounter DESC LIMIT 1",array($pid,date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')));

  if (!empty($erow['encounter'])) {
    // If there is an encounter from today then present the duplicate visit dialog
    echo "duplicateVisit('" . $erow['encounter'] . "', '" .
      oeFormatShortDate(substr($erow['date'], 0, 10)) . "');\n";
  }
}
?>
</script>

</html>
