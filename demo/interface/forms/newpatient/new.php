<?php
/**
 * Encounter form new script.
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

include_once("../../globals.php");
include_once("$srcdir/acl.inc");
include_once("$srcdir/lists.inc");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/encounter.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
// Check permission to create encounters.
$tmp = getPatientData($pid, "squad");
if (($tmp['squad'] && ! acl_check('squads', $tmp['squad'])) ||
     ! (acl_check('encounters', 'notes_a' ) ||
        acl_check('encounters', 'notes'   ) ||
        acl_check('encounters', 'coding_a') ||
        acl_check('encounters', 'coding'  ) ||
        acl_check('encounters', 'relaxed' )))
{
  echo "<body>\n<html>\n";
  echo "<p>(" . xlt('New Visits not authorized'). ")</p>\n";
  echo "</body>\n</html>\n";
  exit();
}

$viewmode = false;
include_once("$srcdir/pid.inc");
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
require_once("common.php");
?>
<html>
<head>
<script type="text/javascript">
function setMyPatient() {
<?php if ($GLOBALS['concurrent_layout']) { ?>
 // Avoid race conditions with loading of the left_nav or Title frame.
 if (!parent.allFramesLoaded()) {
  setTimeout("setMyPatient()", 500);
  return;
 }
 
<?php
 $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
 if (isset($_GET['set_pid'])) { ?>

 parent.left_nav.setPatient(<?php echo "'" . htmlspecialchars(($result['fname']) . " " . ($result['lname']),ENT_QUOTES) .
   "'," . htmlspecialchars($pid,ENT_QUOTES) . ",'" . htmlspecialchars(($result['genericname1']),ENT_QUOTES) .
   "','', ' " . htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate($result['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result['DOB_YMD']), ENT_QUOTES) . "'"; ?>);
 var EncounterDateArray = new Array;
 var CalendarCategoryArray = new Array;
 var EncounterIdArray = new Array;
 var Count = 0;
<?php
  //Encounter details are stored to javacript as array.
  $result4 = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
  if(sqlNumRows($result4)>0) {
    while($rowresult4 = sqlFetchArray($result4)) {
?>
 EncounterIdArray[Count] = '<?php echo htmlspecialchars($rowresult4['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray[Count] = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray[Count] = '<?php echo htmlspecialchars(xl_appt_category($rowresult4['pc_catname']), ENT_QUOTES); ?>';
 Count++;
<?php
    }
  }
?>
 parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
<?php } // end setting new pid ?>
 parent.left_nav.setRadio(window.name, 'dem');
 parent.left_nav.syncRadios();
 
 
 <?php if ( (isset($_GET['set_pid']) ) && (isset($_GET['set_encounterid'])) && ( intval($_GET['set_encounterid']) > 0 ) ) {
 $encounter = intval($_GET['set_encounterid']);
 $_SESSION['encounter'] = $encounter; 
 $query_result = sqlQuery("SELECT `date` FROM `form_encounter` WHERE `encounter` = ?", array($encounter)); ?>
 var othername = (window.name == 'RTop') ? 'RBot' : 'RTop';
 parent.left_nav.setEncounter('<?php echo oeFormatShortDate(date("Y-m-d", strtotime($query_result['date']))); ?>', '<?php echo attr($encounter); ?>', othername);
 parent.left_nav.setRadio(othername, 'enc');
 parent.frames[othername].location.href = '../encounter/encounter_top.php?set_encounter=' + <?php echo attr($encounter);?> + '&pid=' + <?php echo attr($pid);?>;
 <?php } // end setting new encounter id (only if new pid is also set) ?>
 
<?php } // end concurrent layout ?>
}

$(window).load(function() {
 setMyPatient();
});

</script>
</head>
</html>
