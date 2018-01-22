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
 // Exit if the modify calendar for portal flag is not set
 if (!($GLOBALS['portal_onsite_appt_modify'])) {
   echo htmlspecialchars( xl('You are not authorized to schedule appointments.'),ENT_NOQUOTES);
   exit;
 }
/* Things that might be passed by our opener. */
 $eid           = $_GET['eid'];         // only for existing events
 $date          = $_GET['date'];        // this and below only for new events
 $userid        = $_GET['userid'];
 $default_catid = $_GET['catid'] ? $_GET['catid'] : '5';
 $patientid		= $_GET['patid'];
 //
 if ($date)
  $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6);
 else
  $date = date("Y-m-d");
 //
 $starttimem = '00';
 if (isset($_GET['starttimem']))
  $starttimem = substr('00' . $_GET['starttimem'], -2);
 //
 if (isset($_GET['starttimeh'])) {
  $starttimeh = $_GET['starttimeh'];
  if (isset($_GET['startampm'])) {
   if ($_GET['startampm'] == '2' && $starttimeh < 12)
    $starttimeh += 12;
  }
 } else {
  $starttimeh = date("G");
 }
 $startampm = '';
 $info_msg = "";
 $pn=getPatientData($pid, "phone_cell");
$pn_no=$pn['phone_cell'];


 if ($_POST['form_action'] == "save") {
	 
  $_POST['form_apptstatus'] = $GLOBALS['portal_default_status'];
  $event_date = fixDate($_POST['form_date']);
if (!$_POST['form_allday']) {
   $tmph = $_POST['form_hour'] + 0;
   $tmpm = $_POST['form_minute'] + 0;
   if ($_POST['form_ampm'] == '2' && $tmph < 12) $tmph += 12;
   $duration = abs($_POST['form_duration']);
  }
 $starttime = "$tmph:$tmpm:00";
 $user = 'kavaii';
 $password = '12345';
 $sender_id = 'KAVAII';//helloz welcom FAPcop abhiii'hiiiii
 $sender = $pn_no;//9673776599 9320491970
 $msg = 'City Hospital- Appointment Confirmed with Dr. Anikethan at ';
 $msg.=$starttime;
 $msg.=' hrs on ';
 $msg.=$event_date;
 $priority = 'sdnd';
 $sms_type = 'normal';
 //$data = array('user'=>$user, 'pass'=>$password, 'sender'=>$sender_id, 'phone'=>$sender, 'text'=>$msg,  'stype'=>$sms_type);//'priority'=>$priority,
 $data='user='.$user.'&pass='.$password.'&sender='.$sender_id.'&phone='.$sender.'&text='.$msg.'&stype='.$sms_type.'&priority=sdnd'; 
 
 //http://bhashsms.com/api/sendmsg.php?user='kavaii'&pass='12345'&sender='KAVAII'&phone='9782364064'&text='Hii'&stype='normal'&priority='sdnd'
 
 //http://bhashsms.com/api/sendmsg.php?user=kavaii&pass=12345&sender=kavaii%20&phone=9731960662%20&text=hii%20&priority=sdnd&stype=normal
 $ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
// echo var_dump($data);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// echo var_dump($ch);
 try {
  $response = curl_exec($ch);
 // echo var_dump($ch);
  curl_close($ch);
 // echo var_dump($response);
  echo 'Message has been sent.';
   header('location:get_patient_info.php');
 }catch(Exception $e){
  echo 'Message: ' .$e->getMessage();
 }

  // Compute start and end time strings to be saved.

  
  
  $tmpm += $duration;
  while ($tmpm >= 60) {
   $tmpm -= 60;
   ++$tmph;
  }
  $endtime = "$tmph:$tmpm:00";
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

sqlInsert("INSERT INTO openemr_postcalendar_events ( " .
    "pc_catid, pc_aid, pc_pid, pc_title, pc_time, pc_hometext, " .
    "pc_informant, pc_eventDate, pc_endDate, pc_duration, pc_recurrtype, " .
    "pc_recurrspec, pc_startTime, pc_endTime, pc_alldayevent, " .
    "pc_apptstatus, pc_prefcatid, pc_location, pc_eventstatus, pc_sharing, pc_facility " .
    ") VALUES ( " .
    "'" . $_POST['form_category']             . "', " .
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
  
   
  } // INSERT single
  
  
  
  
}
 else
 {
# appointment was edited and re-saved
            sqlStatement("UPDATE openemr_postcalendar_events SET " .
            "pc_catid = '"       . $_POST['form_category']             . "', " .
            "pc_aid = '"         . $_POST['form_provider']             . "', " .
            "pc_pid = '"         . $_POST['form_pid']                  . "', " .
            "pc_title = '"       . $_POST['form_title']                . "', " .
            "pc_time = NOW(), "                                                .
            "pc_hometext = '"    . $_POST['form_comments']             . "', " .
            "pc_informant = '"   . $_SESSION['authUserID']             . "', " .
            "pc_eventDate = '"   . $event_date                         . "', " .
            "pc_endDate = '"     . fixDate($_POST['form_enddate'])     . "', " .
            "pc_duration = '"    . ($duration * 60)                    . "', " .
            "pc_recurrtype = '"  . ($_POST['form_repeat'] ? '1' : '0') . "', " .
            "pc_recurrspec = '$recurrspec', "                                  .
            "pc_startTime = '$starttime', "                                    .
            "pc_endTime = '$endtime', "                                        .
            "pc_alldayevent = '" . $_POST['form_allday']               . "', " .
            "pc_apptstatus = '"  . $_POST['form_apptstatus']           . "', "  .
            "pc_prefcatid = '"   . $_POST['form_prefcat']              . "' ,"  .
             "pc_facility = '"   .(int)$_POST['facility']               ."' "  . // FF stuff
            "WHERE pc_eid = '$eid'");
			 $pid=$_POST['form_pid'];
			 
	       
	}
	
	
 } // else - insert  EID End
  // Save new DOB if it's there.
  $patient_dob = trim($_POST['form_dob']);
  if ($patient_dob && $_POST['form_pid']) {
   sqlStatement("UPDATE patient_data SET DOB = '$patient_dob' WHERE " .
    "pid = '" . $_POST['form_pid'] . "'");
  }
 
 
 # not allowing the patient to delete appointments
 else if ($_POST['form_action'] == "delete") {
        // =======================================
        //  multi providers case
        // =======================================
        if ($GLOBALS['select_multi_providers']) {
             // what is multiple key around this $eid?
            $row = sqlQuery("SELECT pc_multiple FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
			if ( $row['pc_multiple'] ) {
				sqlStatement("DELETE FROM openemr_postcalendar_events WHERE pc_multiple = ?", array($row['pc_multiple']));
			} else {
                                sqlStatement("DELETE FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
                        }
        // =======================================
        //  EOS multi providers case
        // =======================================
        } else {
            sqlStatement("DELETE FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
        }
 }
 if ($_POST['form_action'] != "") {
  // Close this window and refresh the calendar display.
  echo "<html>\n<body>\n<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('$info_msg');\n";
  echo " parent.jQuery.fn.fancybox.close();\n";
  echo "</script>\n</body>\n</html>\n";
  exit();
 }
 
 //*********************************
 // If we get this far then we are displaying the form.
 //*********************************
 $repeats = 0; // if the event repeats
 $repeattype = '0';
 $repeatfreq = '0';
 $patienttitle = "";
 $hometext = "";
 $appt_title = "Patient Scheduled in Portal";
 $row = array();
 // If we are editing an existing event, then get its data.
 if ($eid) {
  $row = sqlQuery("SELECT * FROM openemr_postcalendar_events WHERE pc_eid = $eid");
  $date = $row['pc_eventDate'];
  $userid = $row['pc_aid'];
  $patientid = $row['pc_pid'];
  $starttimeh = substr($row['pc_startTime'], 0, 2) + 0;
  $starttimem = substr($row['pc_startTime'], 3, 2);
  $repeats = $row['pc_recurrtype'];
  $multiple_value = $row['pc_multiple'];
  if (preg_match('/"event_repeat_freq_type";s:1:"(\d)"/', $row['pc_recurrspec'], $matches)) {
   $repeattype = $matches[1];
  }
  if (preg_match('/"event_repeat_freq";s:1:"(\d)"/', $row['pc_recurrspec'], $matches)) {
   $repeatfreq = $matches[1];
  }
  $hometext = $row['pc_hometext'];
  if (substr($hometext, 0, 6) == ':text:') $hometext = substr($hometext, 6);
 }
 else {
  $patientid=$_GET['pid'];
 }
 // If we have a patient ID, get the name and phone numbers to display.
 if ($patientid) {
  $prow = sqlQuery("SELECT lname, fname, phone_home, phone_biz, DOB " .
   "FROM patient_data WHERE pid = ?", array($patientid) );
  $patientname = $prow['lname'] . ", " . $prow['fname'];
  if ($prow['phone_home']) $patienttitle .= " H=" . $prow['phone_home'];
  if ($prow['phone_biz']) $patienttitle  .= " W=" . $prow['phone_biz'];
 }
 // Get the providers list.
 $ures = sqlStatement("SELECT id, username, fname, lname,specialty FROM users WHERE " .
  "authorized != 0 AND active = 1 ORDER BY lname, fname");
 //-------------------------------------
 //(CHEMED)
 //Set default facility for a new event based on the given 'userid'
 if ($userid) {
     $pref_facility = sqlFetchArray(sqlStatement("SELECT facility_id, facility FROM users WHERE id = ?", array($userid)));
     $e2f = $pref_facility['facility_id'];
     $e2f_name = $pref_facility['facility'];
 }
 //END of CHEMED -----------------------
 // Get event categories.
 $cres = sqlStatement("SELECT pc_catid, pc_catname, pc_recurrtype, pc_duration, pc_end_all_day " .
  "FROM openemr_postcalendar_categories ORDER BY pc_catname");
 // Fix up the time format for AM/PM.
 $startampm = '1';
 if ($starttimeh >= 12) { // p.m. starts at noon and not 12:01
  $startampm = '2';
  if ($starttimeh > 12) $starttimeh -= 12;
 }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $eid ? xlt('Edit') : xlt('Add New') ?> <?php echo xlt('Event');?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../library/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../library/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../library/dist/css/skins/_all-skins.min.css">
<?php html_header_show(); ?>


<style>
#loading {
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(0,0,0,.5);
	background-image: url('gifloader.gif');
	background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; 
    -webkit-transition: all .5s ease;
    z-index: 1000;
    display:none;
}
.navbar-nav > .user-menu .user-image {

    background-color: white;
}
.img-circle {
	    background-color: white;
}
.height-dev {
	height: 100%;
}
.logo {
	background: transparent !important;
}

* {
    box-sizing: border-box;
}

/* Create three columns of equal width */
.columns {
    float: right;
    padding: 8px;
}

/* Style the list */
.price {
    list-style-type: none;
    border: 1px solid #eee;
    margin: 0;
    padding: 0;
    -webkit-transition: 0.3s;
    transition: 0.3s;
}

/* Add shadows on hover */
.price:hover {
    box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
}
ul.price li {
	display:inline;
}
/* Pricing header */
.price .header {
    background-color: #111;
    color: white;
    font-size: 20px;
}

/* List items */
.price li {
    border-bottom: 1px solid #eee;
    padding: 14px;
    text-align: center;
}

/* Grey list item */
.price .grey {
    background-color: #eee;
    font-size: 20px;
}
.price .grey:hover {
    background-color: #ccc;
}

/* The "PayPal" button */
.button-paytm {
    background-color: #012b72;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
}

.button-paypal {
    background-color: #23527c;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
}

/* Change the width of the three columns to 100% 
(to stack horizontally on small screens) */
@media only screen and (max-width: 600px) {
    .columns {
        width: 100%;
    }
	ul.price li {
    display: block;
}

}

</style>

<style type="text/css">@import url(../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../library/topdialog.js"></script>
<script type="text/javascript" src="../library/dialog.js"></script>
<script type="text/javascript" src="../library/textformat.js"></script>
<script type="text/javascript" src="../library/dynarch_calendar.js"></script>
<script type="text/javascript" src="../library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="../library/dynarch_calendar_setup.js"></script>

<script language="JavaScript">
 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';
 var durations = new Array();
 // var rectypes  = new Array();
<?php
 // Read the event categories, generate their options list, and get
 // the default event duration from them if this is a new event.
 $catoptions = "";
 $prefcat_options = "    <option value='0'>-- " . xlt("None") . " --</option>\n";
 $thisduration = 0;
 if ($eid) {
  $thisduration = $row['pc_alldayevent'] ? 1440 : round($row['pc_duration'] / 60);
 }
 while ($crow = sqlFetchArray($cres)) {
  $duration = round($crow['pc_duration'] / 60);
  if ($crow['pc_end_all_day']) $duration = 1440;
  echo " durations[" . attr($crow['pc_catid']) . "] = " . attr($duration) . "\n";
  // echo " rectypes[" . $crow['pc_catid'] . "] = " . $crow['pc_recurrtype'] . "\n";
  $catoptions .= "    <option value='" . attr($crow['pc_catid']) . "'";
  if ($eid) {
   if ($crow['pc_catid'] == $row['pc_catid']) $catoptions .= " selected";
  } else {
   if ($crow['pc_catid'] == $default_catid) {
    $catoptions .= " selected";
    $thisduration = $duration;
   }
  }
  $catoptions .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
  // This section is to build the list of preferred categories:
  if ($duration) {
   $prefcat_options .= "    <option value='" . $crow['pc_catid'] . "'";
   if ($eid) {
    if ($crow['pc_catid'] == $row['pc_prefcatid']) $prefcat_options .= " selected";
   }
   $prefcat_options .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
  }
 }
?>
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
 // This is for callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.forms[0];
  f.form_patient.value = lname + ', ' + fname;
  f.form_pid.value = pid;
  dobstyle = (dob == '' || dob.substr(5, 10) == '00-00') ? '' : 'none';
  document.getElementById('dob_row').style.display = dobstyle;
 }
 function change_provider(doctor){
	 var sel = doctor.value;
	 		        $.ajax({
                // Where to send request
                url: 'doctor.price.php',
                // What to send
                data: { did: sel },
                // How to send
                type: 'post',
                // What to do when request succeeds
                success: function(response) {
                    // Save the contents of the response into
                    // whatever has the id="list"
					if(response) {

                    $("#price").html(response);
											$("#save_btn").prop("disabled",true);
					}
					else {
						$("#save_btn").prop("disabled",false);
						$("#doctor_price_list").remove();
					}
                }
        });
	  var f = document.forms[0];
  f.form_date.value='';
  f.form_hour.value='';
  f.form_minute.value='';
 }
 // This is for callback by the find-patient popup.
 function unsetpatient() {
  var f = document.forms[0];
  f.form_patient.value = '';
  f.form_pid.value = '';
 }
 // This invokes the find-patient popup.
 function sel_patient() {
  dlgopen('find_patient_popup.php', '_blank', 500, 400);
 }
 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_display() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   var style_apptstatus = document.getElementById('title_apptstatus').style;
   var style_prefcat = document.getElementById('title_prefcat').style;
   if (catid == '14') { // In Office
    style_apptstatus.display = 'none';
    style_prefcat.display = '';
    f.form_apptstatus.style.display = 'none';
    f.form_prefcat.style.display = '';
   } else {
    style_prefcat.display = 'none';
    style_apptstatus.display = '';
    f.form_prefcat.style.display = 'none';
    f.form_apptstatus.style.display = '';
   }
  }
 }
 // Gray out certain fields according to selection of Category DDL
 function categoryChanged() {
    var value = '5';
   
	document.getElementById("form_patient").disabled=false;
	//document.getElementById("form_apptstatus").disabled=false;
	//document.getElementById("form_prefcat").disabled=false;
 
 }
 // Modify some visual attributes when the all-day or timed-event
 // radio buttons are clicked.
 function set_allday() {
  var f = document.forms[0];
  var color1 = '#777777';
  var color2 = '#777777';
  var disabled2 = true;
  /*if (document.getElementById('rballday1').checked) {
   color1 = '#000000';
  }
  if (document.getElementById('rballday2').checked) {
   color2 = '#000000';
   disabled2 = false;
  }*/
  document.getElementById('tdallday1').style.color = color1;
  document.getElementById('tdallday2').style.color = color2;
  document.getElementById('tdallday3').style.color = color2;
  document.getElementById('tdallday4').style.color = color2;
  document.getElementById('tdallday5').style.color = color2;
  f.form_hour.disabled     = disabled2;
  f.form_minute.disabled   = disabled2;
  f.form_ampm.disabled     = disabled2;
  f.form_duration.disabled = disabled2;
 }
 // This is for callback by the find-available popup.
 function setappt(year,mon,mday,hours,minutes) {
  var f = document.forms[0];
  f.form_date.value = '' + year + '-' +
   ('' + (mon  + 100)).substring(1) + '-' +
   ('' + (mday + 100)).substring(1);
  f.form_ampm.selectedIndex = (hours >= 12) ? 1 : 0;
  f.form_hour.value = (hours > 12) ? hours - 12 : hours;
  f.form_minute.value = ('' + (minutes + 100)).substring(1);
 }
 
    // Invoke the find-available popup.
 function find_available() {
        // (CHEMED) Conditional value selection, because there is no <select> element 
        // when making an appointment for a specific provider
        var s = document.forms[0].form_provider;
        <?php if ($userid != 0) { ?>
            s = document.forms[0].form_provider.value;
        <?php } else {?>
            s = document.forms[0].form_provider.options[s.selectedIndex].value;
        <?php }?>       
        var formDate = document.forms[0].form_date;
        window.open('find_appt_popup_user.php?providerid=' + s +
                '&catid=5' + 
                '&startdate=' + formDate.value, '_blank', "width=550, height=425");
        //END (CHEMED) modifications
 }
 // Check for errors when the form is submitted.
 function validate() {
  var f = document.getElementById('theform');
  if (!f.form_date.value || !f.form_hour.value || !f.form_minute.value) {
   alert('Please click on "Find Open Appointment" to select a time.');
   return false;
  }
  
//  in lunch outofoffice reserved vacation
  f.form_category.value='14';
  if (f.form_patient.value=='Click to select' && (!(
         f.form_category.value=='2' || f.form_category.value=='8' || f.form_category.value=='3' || f.form_category.value=='4' || f.form_category.value=='11'
	 || f.form_category.value=='10'))) {
   alert('Please select a patient.');
   return false;
  } else if (f.form_category.value=='10') {
	unsetpatient();	
  }
   // var form_category = $("#form_category").val(); 
 // var form_date = $("#form_date").val();
 // var form_title = $("#form_title").val();
 //   var form_hour = $("#form_hour").val();
//	  var form_minute = $("#form_minute").val();
//	    var form_ampm = $("#form_ampm").val();

  var form_action = document.getElementById('form_action');
  form_action.value="save";
  f.submit();
  return true;
 }
 function deleteEvent() {
    if (confirm("Deleting this event cannot be undone. It cannot be recovered once it is gone. Are you sure you wish to delete this event?")) {
        var f = document.getElementById('theform');
        var form_action = document.getElementById('form_action');
        form_action.value="delete";
        f.submit();
        return true;
    }
    return false;
 }
</script>
 
</head>

<body onunload='imclosing()' onload='categoryChanged()' class="hold-transition skin-blue layout-top-nav">
<div id="loading"></div>
<div class="wrapper">

<?php
 $result = getPatientData($pid);
?>
  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="summary_pat_portal.php" class="logo"><img src="../images/logo.png" class="img-responsive height-dev"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
           
            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                    <li><!-- start notification -->
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                    <!-- end notification -->
                  </ul>
                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>
           
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="../library/dist/img/male_user.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo htmlspecialchars($result['fname']." ".$result['lname'],ENT_NOQUOTES); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="../library/dist/img/male_user.png" class="img-circle" alt="User Image">

                  <p>
                    <?php echo htmlspecialchars($result['fname']." ".$result['lname'],ENT_NOQUOTES); ?>
                    <small><?php echo htmlspecialchars($result['genericname1'],ENT_NOQUOTES); ?></small>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">

                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
				  
<input type="button" style="text-align: right;" class="btn btn-default btn-flat" value="<?php echo xl('Change Password'); ?>" onclick="window.location = '<?php echo $landingpage."&password_update=1";?>'"/>

                    </div>
                  <div class="pull-right">
				  <input type="button" style="text-align: right;" class="btn btn-default btn-flat" value="<?php echo xl('Log Out'); ?>" onclick="window.location = 'logout.php'"/>

                     </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          <?php echo htmlspecialchars( xl('Welcome'), ENT_NOQUOTES); ?> <b><?php echo htmlspecialchars($result['fname']." ".$result['lname'],ENT_NOQUOTES); ?></b>

        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		  <li><a href="<?php echo $GLOBALS['webroot'] ?>/patients/get_patient_info.php">Patient Portal</a></li>
          <li class="active">New Appointment</li>
        </ol>
      </section>


      <!-- Main content -->
      <section class="content">
       
<form method='post' name='theform' id='theform' action='add_edit_event_user.php?eid=<?php echo attr($eid) ?>' onsubmit='return top.restoreSession()' />
<input type="hidden" name="form_action" id="form_action" value="">

		  <div class="form-group">
		    <label for="category"><?php echo xlt('Category'); ?>:</label>
   
   <input type="text" class="form-control" id='form_category' name='form_category' value='Office Visit' readonly='readonly' style='width:100%'/>
  </div>
  <div class='row'>
		  <div class="col-sm-4">
		    <label for="category"><?php echo xlt('Date'); ?>:</label>
   
  <div id='tdallday1'>
   <input type='text' class="form-control" size='7' name='form_date' readonly id='form_date' <?php echo $disabled ?>
    value='<?php if (isset($eid)) { echo $eid ? $row['pc_eventDate'] : $date; } ?>'
    onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' >
  </div>
 </div>
		  <div class="col-sm-4">
  <div id='tdallday2'>
  <label> <?php echo xlt('Time'); ?></label>
  </div>
  <div id='tdallday3'>
  <div class="col-xs-4" style="padding: 0px">
   <input type='text' class="form-control" size='1' name='form_hour' value='<?php if(isset($eid)) { echo attr($starttimeh); } ?>'
    title='<?php echo xla('Event start time'); ?>' readonly />
   </div><div class="col-xs-4"><input type='text' class="form-control" size='1' name='form_minute' value='<?php if(isset($eid)) { echo attr($starttimem); } ?>'
    title='<?php echo xla('Event start time'); ?>' readonly />&nbsp;
   </div><div class="col-xs-4"><select name='form_ampm' class="form-control" id='form_ampm' title='<?php echo xla('Note: 12:00 noon is PM, not AM'); ?>' readonly='readonly' >
    <option value='1' ><?php echo xlt('AM'); ?></option>
    <option value='2' <?php if ($startampm == '2') echo " selected" ?>><?php echo xlt('PM'); ?></option>
   </select></div>
  </div>
 </div>
 		  <div class="col-sm-4">
  <label id='tdallday4' style="display:block"><?php echo xlt('Duration'); ?>
  </label>
  <div  class="col-xs-8" id='tdallday5' style="padding:0px">
  <input type='text' class="form-control" size='1' name='form_duration' value='<?php echo attr($thisduration) ?>' title='<?php echo xla('Event duration in minutes'); ?>' readonly /> 
    </div><div class="col-xs-4"><?php echo xlt('minutes'); ?> 
    
  </div>
  </div>
 </div>

		  <div class="form-group">
		    <label for="category"><?php echo xlt('Title'); ?>:</label>
   
   <input type='text' class="form-control" size='5' name='form_title' id='form_title' value='<?php echo attr($appt_title) ?>' readonly='readonly'
    style='width:100%'
    title='<?php echo xla('Event title'); ?>' />
  </div>

		  <div class="form-group">
   <label><?php echo xlt('Patient'); ?>:</label>

   <input type='text' class="form-control" size='10' id='form_patient' name='form_patient' style='width:100%;' value='<?php echo attr($patientname) ?>' title='Patient' readonly />
   <input type='hidden' name='form_pid' value='<?php echo attr($patientid) ?>' />
  </div>

		  <div class="form-group">
   <label><?php echo xlt('Doctor'); ?>:</label>

   <?php
        // present a list of providers to choose from
        // default to the currently logged-in user
    if (!$eid) 
    {
      // default to the currently logged-in user
      $defaultProvider = $_SESSION['authUserID'];
      // or, if we have chosen a provider in the calendar, default to them
      // choose the first one if multiple have been selected
      if (count($_SESSION['pc_username']) >= 1) {
        // get the numeric ID of the first provider in the array
        $pc_username = $_SESSION['pc_username'];
        $firstProvider = sqlFetchArray(sqlStatement("select id from users where username=?", array($pc_username[0]) ));
        $defaultProvider = $firstProvider['id'];
      }
      // if we clicked on a provider's schedule to add the event, use THAT.
      if ($userid) $defaultProvider = $userid;
    }
        echo "<select name='form_provider' class='form-control' id='form_provider' onchange='change_provider(this);'  />";
        while ($urow = sqlFetchArray($ures)) {
            echo "    <option value='" . attr($urow['id']) . "'";
            if ($urow['id'] == $defaultProvider) echo " selected";
            #if (($urow['id'] == $_GET['userid'])||($urow['id']== $userid)) echo " selected"; 
            echo ">" . text($urow['fname']);
            if ($urow['fname']) echo " " . text($urow['lname']). " (" . text($urow['specialty'] . ")");
            echo "</option>\n";
        }
        echo "</select>";
?>

 </div>

		  <div class="form-group">
   <label><?php echo xlt('Comments'); ?>:</label>

	<input type='text' class="form-control" name='form_comments' value='<?php echo attr($hometext); ?>' title='<?php echo xla('Optional information about this event');?>' />
  </div>
 
<div class="columns row" id="price">
</div>

<p>
<input type='button' name='form_save' id='save_btn' class='btn btn-primary' value='<?php echo xla('Save');?>' onclick="validate()" />
&nbsp;
<input type='button' class='btn btn-info' value='<?php echo xla('Find Open Appointment');?>' onclick='find_available()' />
&nbsp;
<?php
if($eid) {
?>
<input type='button' class='btn btn-danger' value='<?php echo xla('Delete');?>' onclick='deleteEvent()' />
&nbsp;
<?php } ?>
<input type='button' class='btn btn-warning' value='<?php echo xla('Cancel');?>' onclick='document.location.href="../patients/summary_pat_portal.php"' />
</p>


</form>

      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.3
      </div>
      <strong>Copyright &copy; 2013-2018 <a href="http://medsmart.com">MedSmart</a>.</strong> All rights
      reserved.
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../library/dist/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../library/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../library/dist/js/app.min.js"></script>
</body>
</html>