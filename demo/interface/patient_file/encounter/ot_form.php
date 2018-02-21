<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
include_once("$srcdir/encounter.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$obj = $formid ? formFetch("t_form_ot", $formid) : array();
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
 $e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
 $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
  setencounter($encounter);
$result_patient = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
$result_visit="SELECT * from form_encounter where pid=? and encounter=?";
$result_visit1 = sqlStatement($result_visit, array($pid,$encounter)); 
$result_visit2=sqlFetchArray($result_visit1);
$pfname = $result_patient['fname'];
$plname = $result_patient['lname'];
$pmname = $result_patient['mname'];
$page = $result_patient['age'];
$pgender=$result_patient['sex'];
$pmob = $result_patient['phone_cell'];
?> 
<html>

<head>
<?php html_header_show();?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="<?php echo $GLOBALS['webroot'] ?>/library/css/bootstrap-datetimepicker4.7.14.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot']; ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
  <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  	<link rel="stylesheet" href="style.css"  />
		<link rel="stylesheet" href="../../../library/css/mycss.css"  />
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
	<style>
	.cd-breadcrumb li > *, .cd-multi-steps li > * {
    font-size: 1.4rem !important;
	}
	section {
		margin-left: 20px;
	}
	.cd-breadcrumb.triangle li > * {
    padding: 1em 0.3em 1em 1.4em !important;
	}
	</style>
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
<body>
   <div class="container">
<div class="row">
<div class="col-md-12">
<form class="form-horizontal" method='post' action="<?php echo '../../forms/OT/save.php?id='. $formid; ?>">
<fieldset>

<!-- Form Name -->
<legend>OT Allocation</legend>

<!-- Text input-->




<div class="form-group">
  <label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>  
  <div class="col-md-4">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="Name (Full name)" name="client_name" type="text" placeholder="Name (Full name)" class="form-control input-md" value="<?php echo $pfname. $pmname. $plname ?>" readonly>
      </div>

    
  </div>

  
</div>

<!-- File Button 
<div class="form-group">
  <label class="col-md-4 control-label" for="Upload photo">Upload photo</label>
  <div class="col-md-4">
    <input id="Upload photo" name="Upload photo" class="input-file" type="file">
  </div>
</div>--> 

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Date Of Birth">Age</label>  
  <div class="col-md-4">

  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-birthday-cake"></i>
        
       </div>
       <input id="Age" name="age" type="text" placeholder="Age" class="form-control input-md" value="<?php echo $page; ?>" readonly>
      </div>
  
    
  </div>
</div>

<!-- Multiple Radios (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Gender">Gender</label>
  <div class="col-md-4"> 
    <label class="radio-inline" for="Gender-0">
      <input type="radio" name="Gender" id="Gender-0" value="1" <?php  if($pgender == 'Male') { echo "checked"; } ?>>
      Male
    </label> 
    <label class="radio-inline" for="Gender-1">
      <input type="radio" name="Gender" id="Gender-1" value="2" <?php if($pgender == 'Female') { echo "checked"; } ?>>
      Female
    </label> 
    <label class="radio-inline" for="Gender-2">
      <input type="radio" name="Gender" id="Gender-2" value="3" <?php if($pgender == 'Other') { echo "checked"; } ?>>
      Other
    </label>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Phone number ">Phone number </label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-phone"></i>
        
       </div>
    <input id="Phone number " name="Phone number " type="text" placeholder="Primary Phone number " class="form-control input-md" value="<?php echo $pmob; ?>" readonly>
    
      </div>

  
  </div>
</div>
                    <!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Working Experience (time period)">OT Appointment (time period)</label>  
  <div class="col-md-4">
  <input type=hidden name ="adm_to" value=<?php echo $_POST['adm_to']; ?> > </input>
	<?
	$bid23=$_POST['adm_to'];
	$bid24=$_POST['admit_to_ward'];
	$quer23="select * FROM list_options where list_id='".$bid24."' and option_id='".$bid23."'";

	$res23 = sqlStatement($quer23);
	$result23 = sqlFetchArray($res23);
	$admwdd=$result23['list_id'];
	$admbd=$result23['option_id'];
	echo "<td>"."<input type=\"text\" name=\"admit_to_ot_room\" class=\"form-control\" value=\"$admwdd\" readonly required>"."</input></td>";
	echo "<td>"."<input type=\"text\" name=\"appointment_date\" class=\"form-control\" value=\"$admbd\" readonly required></input>"."</td>"; 
	?>
  <!--<div class="input-group date"  id='datetimepicker' >

      <input type='text' size='10' class='form-control' name='form_date' id='form_date' title='<?php echo xla('yyyy-mm-dd OT Appointment'); ?>' />
	   <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div> -->
				</div> 
				<div class="col-md-4">
				<a href="../../patient_file/encounter/emptyot.php" class="btn btn-default">Find available OT Slots</a>
				</div>
 </div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Working Experience (time period)">Estimated Operation Time (time period)</label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-clock-o"></i>
        
       </div>
    <input id="Working Experience (time period)" name="e_o_t" type="text" onchange="handleChange(this);" placeholder="Time period in hours (max: 24hrs)" class="form-control input-md" value="4">
    
    
      </div>
 
  </div>
</div>

<!-- Textarea -->
<!-- <div class="form-group">
  <label class="col-md-4 control-label" for="Overview (max 200 words)">Overview (max 200 words)</label>
  <div class="col-md-4">                     
    <textarea class="form-control" rows="10"  id="Overview (max 200 words)" name="Overview (max 200 words)">Overview</textarea>
  </div>
</div> -->

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Special (max 200 words)">Special Instructions </label>
  <div class="col-md-4">                     
    <textarea class="form-control" rows="10"  id="Special (max 200 words)" name="ot_instructions"></textarea>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up"></span> Submit</button>
  <a href="#" class="btn btn-danger" onclick="history.go(-1);" value=""><span class="glyphicon glyphicon-remove-sign"></span> Cancel</a>
    
  </div>
</div>

</fieldset>
</form>
</div>
<!--<div class="col-md-2 hidden-xs">
<img src="http://websamplenow.com/30/userprofile/images/avatar.jpg" class="img-responsive img-thumbnail ">
  </div> -->


</div>
   </div>
   		<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

        <script type="text/javascript">

  function handleChange(input) {
    if (input.value < 0) input.value = 0;
    if (input.value > 24) input.value = 24;
  }

		var j = jQuery.noConflict();
            j(function () {
                j('#datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
				});
				</script>

		              <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/moment/moment.js" ></script>
                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap-datetimepicker4.7.14.min.js" type="text/javascript"></script>
					                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
  </html>
  