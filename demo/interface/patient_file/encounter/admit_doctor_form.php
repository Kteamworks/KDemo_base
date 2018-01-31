

<?php

include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/pnotes.inc");
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
    $notes=$_POST['form_related_notes'];
	$form_message_status="New";
	$form_note_type="Admit";
	//$pid=$_SESSION['pid'];
	$assigned_to="admission";
    addPnote($pid, $notes, $userauthorized, '1', $form_note_type, $assigned_to, '', $form_message_status);
    // redirect back to the encounter
   $address = "{$GLOBALS['rootdir']}/patient_file/summary/summary_print.php";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
}
?>
<html>

<head>
<?php html_header_show();?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
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
<style>
.cd-breadcrumb.triangle li {
	font-size: 1.6rem !important;
}
.cd-breadcrumb.triangle li > * {
    padding: 1em 0em 1em 1.5em !important;
}
</style>

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
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
  <?php if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?>
<?php 
$rid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$rid1=sqlFetchArray($rid);
		$rid2=$rid1['form_id'];
$vid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='vitals' order by form_id desc limit 1 ");
		$vid1=sqlFetchArray($vid);
		$vid2=$vid1['form_id'];
		$plid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='dictation' order by form_id desc limit 1 ");
		$plid1=sqlFetchArray($plid);
		$plid2=$plid1['form_id'];
		$nvid=sqlStatement("SELECT id from form_encounter where encounter='".$_SESSION['encounter']."'");

  $nvid1=sqlFetchArray($nvid);

 $nvid2=$nvid1['id'];
		?>
<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
		<li><a href="../summary/stats_full.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			<?php if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
			<li><a href="../encounter/view_form.php?formname=newpatient&id=<?php echo $nvid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Visit Notes</a></li>
			<li><a href="../encounter/load_form.php?formname=procedure_order"><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li><a href="../../../controller.php?prescription&edit&id=&pid=<?php echo $pid ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
			<?php if($plid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=dictation"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } else { ?>
									<li><a href="../encounter/view_form.php?formname=dictation&id=<?php echo $plid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } ?>
			<li><a href="../../patient_file/transaction/add_transaction.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li class="current"></i><em>Admission</em></li>
			<li><a href="../../patient_file/summary/summary_print.php">Summary</a></li>
		</ol>
	</nav>
</section>
  <?php }?>
<span class="title">Admit Patient</span>

<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/admit_doctor_form.php" name="my_form" id="my_form" onsubmit='return validateForm()'>
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
     $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
    
  }
   
   ?>


<table>
<?php
$today = date('Y-m-d H:i:s',strtotime("+0 days"));
?>
<tr>
<td align="left" class="forms"><b><?php echo xlt('Enter your Comments'); ?>:</b></td>
</tr>
<tr>
	   	<td class="forms">
		
			 <textarea rows='10' cols='50' name='form_related_notes'
    title='<?php echo xla('Enter the notes which is required'); ?>'
      style='width:100%' class='inputtext' /> 
	  <?php echo htmlspecialchars($row['notes_test'], ENT_QUOTES);?>
      </textarea>
		</td>
</tr>

</table>
<br>
<input type="button" id="confirmbtn" class="btn btn-primary" name="confirmbtn" value="Admit">
<input type="button" id="cancel" name="cancel" class="btn btn-warning" value="Cancel">
</form>

</body>
<script language="javascript">
/* required for popup calendar */
//Calendar.setup({inputField:"admit_date", ifFormat:"%Y-%m-%d", button:"img_transfer_date"});
//Calendar.setup({inputField:"discharge_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_end_date",showsTime:'true'});
</script>

<script language="javascript">
// jQuery stuff to make the page a little easier to use
//var $j = jQuery.noConflict();
$(document).ready(function(){
    $("#confirmbtn").click(function() { return ConfirmDelete(); });
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/summary/summary_print.php";?>'; });
});

function ConfirmDelete() {
	 $.jAlert({'type': 'confirm','confirmQuestion':'Are you sure you wish to Admit this Patient? ', 'onConfirm': function(){
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
