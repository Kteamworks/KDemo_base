<?php
 /*
 * Add Edit Event User for Patient Portal (add_edit_event_user.php)
 *
 * (Adapted from the Add Edit Event written by Rod Roark <rod@sunsetsystems.com>)
 *
 * This program is used to enter provider , comments, and search for un-used  
 * appointments in the Patient Portal, This works to allow the patient to 
 * schedule there own appointment in Patient Portal.
 * 
 * The add edit event screen looks similar to this:
 *
 *------------------------------------------------------------*
 * Category __________________V     Date                      *
 * Date     _____________ [?]       Time     ___:___ __V      *
 * Title    ___________________     duration ____ minutes     *
 * Provider __________________V                               *
 * Comments ________________________________________________  *
 *                                                            *
 *    [Save] [Find Open Appointments] [Delete] [Cancel]       *
 *------------------------------------------------------------*
 *
 * Copyright (C) 2015 Terry Hill <terry@lillysystems.com> 
 * 
 * Copyright (C) 2005-2013 Rod Roark <rod@sunsetsystems.com>
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
 * @author Terry Hill <terry@lilysystems.com> 
 * @author Rod Roark <rod@sunsetsystems.com> 
 * @link http://www.open-emr.org 
 *
 * Please help the overall project by sending changes you make to the authors and to the OpenEMR community.
 * 
 */
// continue session
session_start();
ini_set('max_execution_time', 0);

//SANITIZE ALL ESCAPES
$fake_register_globals=false;
//STOP FAKE REGISTER GLOBALS
$sanitize_all_escapes=true;
//landing page definition -- where to go if something goes wrong
$landingpage = "index.php?site=".$_SESSION['site_id'];
//
// kick out if patient not authenticated
if ( isset($_SESSION['pid']) && isset($_SESSION['patient_portal_onsite']) ) {
  $pid = $_SESSION['pid'];
}
else {
  session_destroy();
  header('Location: '.$landingpage.'&w');
  exit;
}
//
$ignoreAuth = 1;
global $ignoreAuth;
	  include_once("../interface/globals.php");
 include_once("$srcdir/patient.inc");
 include_once("$srcdir/forms.inc");
$pn=getPatientData($pid, "phone_cell");
$pn_no=$pn['phone_cell'];

  $_POST['form_apptstatus'] = $GLOBALS['portal_default_status'];
  $event_date = fixDate($_POST['form_date']);
if (!$_POST['form_allday']) {
   $tmph = $_POST['form_hour'] + 0;
   $tmpm = $_POST['form_minute'] + 0;
   if ($_POST['form_ampm'] == '2' && $tmph < 12) $tmph += 12;
   $duration = abs($_POST['form_duration']);
  }
 $starttime = "$tmph:$tmpm:00";

  // Compute start and end time strings to be saved.

  
  
  $tmpm += $duration;
  while ($tmpm >= 60) {
   $tmpm -= 60;
   ++$tmph;
  }
  $endtime = "$tmph:$tmpm:00";
  $catID = 14;
  // Useless garbage that we must save.
  $locationspec = 'a:6:{s:14:"event_location";N;s:13:"event_street1";N;' .
   's:13:"event_street2";N;s:10:"event_city";N;s:11:"event_state";N;s:12:"event_postal";N;}';
  // More garbage, but this time 1 character of it is used to save the
  // repeat type.
  if (!$_POST['form_repeat']) {
   $recurrspec = 'a:5:{' .
    's:17:"event_repeat_freq";N;' .
    's:22:"event_repeat_freq_type";s:1:"0";' .
    's:19:"event_repeat_on_num";s:1:"1";' .
    's:19:"event_repeat_on_day";s:1:"0";' .
    's:20:"event_repeat_on_freq";s:1:"1";}';
  }
/* =======================================================
//                                  UPDATE EVENTS
========================================================*/
if (!$eid) {   # if !eid start  
if (!is_array($_POST['form_provider'])) {   

$check = sqlInsert("INSERT INTO openemr_postcalendar_events ( " .
    "pc_catid, pc_aid, pc_pid, pc_title, pc_time, pc_hometext, " .
    "pc_informant, pc_eventDate, pc_endDate, pc_duration, pc_recurrtype, " .
    "pc_recurrspec, pc_startTime, pc_endTime, pc_alldayevent, " .
    "pc_apptstatus, pc_prefcatid, pc_location, pc_eventstatus, pc_sharing, pc_facility " .
    ") VALUES ( " .
    "'" . $catID             . "', " .
    "'" . $_POST['form_provider']             . "', " .
    "'" . $_POST['form_pid']                  . "', " .
    "'" . $_POST['form_title']                . "', " .
    "NOW(), "                                         .
    "'" . $_POST['form_comments']             . "', " .
    "'" . $_SESSION['authUserID']             . "', " .
    "'" . $event_date                         . "', " .
    "'" . fixDate($_POST['form_enddate'])     . "', " .
    "'" . ($duration * 60)                    . "', " .
    "'" . ($_POST['form_repeat'] ? '1' : '0') . "', " .
    "'$recurrspec', "                                 .
    "'$starttime', "                                  .
    "'$endtime', "                                    .
    "'" . $_POST['form_allday']               . "', " .
    "'" . $_POST['form_apptstatus']           . "', " .
    "'" . $_POST['form_prefcat']              . "', " .
    "'$locationspec', "                               .
    "1, " .
    "1," .(int)$_POST['facility']. ")"); // FF stuff
  
   if($check) {

echo $check;
   }
  } // INSERT single
}
  ?>