

<?php

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/encounter.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");



// when the Cancel button is pressed, where do we go?
$enc=$_POST['encounter'];
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
 $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
  setencounter($encounter);
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
if ($_POST['confirm']) {
    // set the discharge flag of the indicated form
	 	$admit_to_ot_room=$_POST['admit_to_ot_room'];
	 sqlStatement("update list_options set is_default=0 where list_id='".$_POST['admit_to_ot_room']."'
	and option_id='".$_POST['appointment_date']."'");

     $status=0;
	 $days="SELECT round(TIME_TO_SEC(TIMEDIFF('".$_POST['discharge_date']."','".$_POST['admit_date']."'))/86400,0) days";
	$day=sqlStatement($days);
	$daa=sqlFetchArray($day);
	$da=$daa['days'];
	//$today = date("Y-m-d H:i:s"); 
	$today = date('Y-m-d H:i:s', strtotime($_POST['release_date']));
	//$today=strtotime($today);
  	$e=$_POST['encounter'];
	$reason=$_POST['reason'];
    sqlStatement("UPDATE t_form_ot SET status=
'0',release_date='".$today."',released_by='".$_SESSION['authUser']."', release_notes='".$_POST['release_notes']."' where admit_to_ot_room='".$_POST['admit_to_ot_room']."' and appointment_date='".$_POST['appointment_date']."' and encounter='".$e."'");
    //if ($_POST['id'] != "*" && $_POST['id'] != '')
    // log the event   
    newEvent("discharge", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "Form ".$_POST['formname']." discharged from Encounter ".$_POST['encounter']);

    // redirect back to the encounter
   $address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder_ip.php";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
}
?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-functions.js"> //optional!!</script>
<!-- supporting javascript code -->
<!--<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>-->

<script type="text/javascript" src="../../../library/dialog.js"></script>
<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script>
function validateForm() {
    
	 var startDate = new Date($('#admit_date').val()).getTime();
    var endDate = new Date($('#discharge_date').val()).getTime();
    var diff =endDate - startDate;
	if (diff < 0) {
    alert("Admission Date is greater than Discharge Date");
  	return false;
  } 
    
}
</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
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
  <?php
  $test = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? and  fe.encounter=? order by fe.date desc", array($pid,$e));
	 $test1=sqlFetchArray($test);
?>
 EncounterIdArray1= '<?php echo htmlspecialchars($test1['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray1 = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($test1['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray1 = '<?php echo htmlspecialchars(xl_appt_category($test1['pc_catname']), ENT_QUOTES); ?>';
 parent.left_nav.setEncounter(EncounterDateArray1,EncounterIdArray1,CalendarCategoryArray1);
<?php } // end setting new pid ?>
 parent.left_nav.setRadio(window.name, 'dem');
 parent.left_nav.syncRadios();
<?php } // end concurrent layout ?>
}
$(window).load(function() {
 setMyPatient();
});
</script>
</head>

<body class="body_top">

<span class="title">Release from OT</span>

<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/release_form.php" name="my_form" id="my_form" onsubmit='return validateForm()'>
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getOTData($pid, "*");
	$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
	  
	$result1=sqlStatement("select * from t_form_ot where encounter='".$e."' and status=1");
	$result2=sqlFetchArray($result1);
	$encounter=($result2['encounter']);
  }
   $admit_to_ot_room=($result2['admit_to_ot_room']);
    $appointment_date=($result2['appointment_date']);
	//$admit_date=($result2['admit_date']);
   ?>
  <input type="hidden" id="encounter" name="encounter" value='<?php echo attr($encounter);?>'>
 <input type="hidden" id="admit_to_ot_room" name="admit_to_ot_room" value='<?php echo attr($admit_to_ot_room);?>'>

<input type="hidden" id="appointment_date" name="appointment_date" value='<?php echo attr($appointment_date);?>'>
<p>
You are about to release a Patient from '<?php echo attr($admit_to_ot_room);?>':'<?php echo attr($appointment_date)?>'
</p>
<table>
<?php
$today = date('Y-m-d H:i:s',strtotime("+0 days"));
?>
<tr>
<td align="left" class="forms"><?php echo xlt('Release date'); ?>:</td>
	   	<td class="forms">
		
			   <input type='text' size='10' name='release_date' id='release_date' <?php echo attr ($disabled)?>;
       value='<?php echo attr($today); ?>' 
       title='<?php echo xla('yyyy-mm-dd Date of Discharge'); ?>'
       onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' />
        <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_end_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'>
		</td>
</tr>
<tr>
 <td><?php echo xlt('Release Notes'); ?>:</td>
 <td><textarea name="release_notes" > </textarea></td>
</tr>
</table>
<input type="button" id="confirmbtn" name="confirmbtn" value="Yes, Release this Patient">
<input type="button" id="cancel" name="cancel" value="Cancel">
</form>

</body>
<script language="javascript">
/* required for popup calendar */
//Calendar.setup({inputField:"admit_date", ifFormat:"%Y-%m-%d", button:"img_transfer_date"});
Calendar.setup({inputField:"discharge_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_end_date",showsTime:'true'});
</script>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#confirmbtn").click(function() { return ConfirmDelete(); });
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

function ConfirmDelete() {
	 $.jAlert({'type': 'confirm','confirmQuestion':'Are you sure you wish to Release this Patient? ', 'onConfirm': function(){
        top.restoreSession();
        $("#my_form").submit();
        return true;   
  }, 'onDeny': function(){
    return false;    
  } });
    /*if (confirm("This action cannot be undone. Are you sure you wish to discharge this Patient?")) {
        top.restoreSession();
        $("#my_form").submit();
        return true;
    }
    return false;*/
}

</script>

</html>
