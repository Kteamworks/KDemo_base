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
 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/acl.inc");

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
    echo "<p>" . xlt('You are not authorized to see this visit.') . "</p>\n";
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
<title><?php echo xlt('Patient Visit'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<link href="<?php echo $GLOBALS['webroot'] ?>/library/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="<?php echo $GLOBALS['webroot'] ?>/library/css/bootstrap-datetimepicker4.7.14.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
	<style>
	/* Create three columns of equal width */
.columns {
    float: right;
    padding: 8px;
	    margin-bottom: 15px;
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
    font-size: 15px;
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
    font-size: 15px;
}
.price .grey:hover {
    background-color: #ccc;
}
	.form-group .control-label:after {
  content:"*";
  color:red;
}

        h1 {
            font-size: 20px;
            color: #111;
        }

        .content {
            width: 80%;
            margin: 0 auto;
            margin-top: 50px;
        }

        .tt-hint,
        .city {
            border: 2px solid #CCCCCC;
            border-radius: 8px 8px 8px 8px;
            font-size: 24px;
            height: 45px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 400px;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px 8px 8px 8px;
            font-size: 18px;
            color: #111;
            background-color: #F1F1F1;
        }
   
	</style>
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
<?php
  //IP Patient List
  $ipadmit = sqlStatement("SELECT c.status as IP FROM form_encounter a,patient_data b,t_form_admit c 
  where a.pid=b.pid  and a.encounter=c.encounter and a.pc_catid=12 and
  c.status='admit' and c.pid='".$pid."'  group by a.pid,a.encounter");
  $ipadmit1=sqlFetchArray($ipadmit);
?>
  var ippat='<?php echo $ipadmit1['IP']?>';
  var category = document.forms[0].pc_catid.value;
  var pat = document.getElementById('TypeAheadInput').value;
  var id= document.getElementById('pc_catid').value;
  var patid='<?php echo $_SESSION['pid'] ?>';
  var frameDocument = $('frame[name="Title"]', top.document)[0].contentDocument;
  if (! $(frameDocument).find('#EncounterHistory')[0]) {
  alert("<?php echo xls('Please select a Patient'); ?>");
   return false;
  }
  if(category==12 && ippat=='admit')
  {
 alert("<?php echo xls('Patient is already admitted with different Visit'); ?>");
   return false;
  }
  if ( category == '_blank' ) {
   alert("<?php echo xls('You must select a visit category'); ?>");
   return false;
  }
<?php } ?>
  top.restoreSession();
  f.submit();
 }


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
	var plan= document.getElementById('rateplans').value;
	if(plan=="TPAInsurance")
	{
		document.getElementById('instpa').style.display = '';
	}
	else
	{
		document.getElementById('instpa').style.display = 'none';
	}
	
}
function getCatId(id)
{
	if(id =="10") {
		document.getElementById('price').style.display = '';
		document.getElementById('pay').style.display = '';
	}
	else {
		document.getElementById('price').style.display = 'none';
		document.getElementById('pay').style.display = 'none';
	}
	if(id=="12")
	{
		document.getElementById('hospa').style.display = '';
		document.getElementById('rplan').style.display = '';
	}
	else
	{
		document.getElementById('hospa').style.display = 'none';
		document.getElementById('rplan').style.display = 'none';
		document.getElementById('instpa').style.display = 'none';
	}

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
if(document.getElementById('pc_catid').value == 10) {
document.getElementById('price').style.display = '';
		document.getElementById('pay').style.display = '';
	
}
									//		$("#save_btn").prop("disabled",true);
					}
					else {
					//	$("#save_btn").prop("disabled",false);
						$("#price").hide();
					document.getElementById('pay').style.display = 'none';
					}
                }
        });
 }
function setMyPatient() {
 // Avoid race conditions with loading of the left_nav or Title frame.
 if (!parent.allFramesLoaded()) {
  setTimeout("setMyPatient()", 500);
  return;
 }
<?php $result1  = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD"); ?>
 parent.left_nav.setPatient(<?php echo "'" . htmlspecialchars(($result1['fname']) . " " . ($result1['lname']),ENT_QUOTES) .
   "'," . htmlspecialchars($pid,ENT_QUOTES) . ",'" . htmlspecialchars(($result1['genericname1']),ENT_QUOTES) .
   "','', ' " . htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate($result1['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result1['DOB_YMD']), ENT_QUOTES) . "'"; ?>);
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

}
</script>

</head>

<?php if ($viewmode) { ?>
<body class="body_top">
<?php } else { ?>
<body class="body_top" onload="javascript:document.new_encounter.reason.focus();">
<?php } $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'"); 
		if($newcrop_user_role['newcrop_user_role']!='erxdoctor' && $newcrop_user_role['newcrop_user_role']!='erxnurse') { 	 ?>
    <div class="container" style="margin-bottom:15px" id="selectpat">
            <h1>Select Patient</h1>
            <input type="text" name="city" size="30" class="city" id="TypeAheadInput" placeholder="Search by Name, Mobile or MRN No.">
    </div>
<?php } ?>
<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
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
		if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?>
<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
			<li><a href="../../patient_file/summary/stats_full.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			<?php if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
			<li class="current"></i><em>Visit Notes</em></li>
			<li><a href="../encounter/load_form.php?formname=procedure_order"><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li><a href="../../../controller.php?prescription&edit&id=&pid=<?php echo $pid ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
						<?php if($plid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=dictation"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } else { ?>
									<li><a href="../encounter/view_form.php?formname=dictation&id=<?php echo $plid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } ?>
			<li><a href="../transaction/add_transaction.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li><a href="../../patient_file/encounter/admit_doctor_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li><a href="../../patient_file/summary/summary_print.php">Summary</a></li>
		</ol>
	</nav>
</section>
  <?php } else if($newcrop_user_role['newcrop_user_role']=='erxnurse'){?>
	<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
       <?php if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
			<li class="current"></i><em>Visit Notes</em></li>
			<li>
			<a href="../../patient_file/summary/nurse_checkout.php"  style="background-color: #dd4b39 !important;
color: #fff;{$DISPLAYNONE1};">
check out</a></li>
		
	</ol>
	</nav>
</section>  
 <?php }?>
<form method='post' action="<?php echo $rootdir ?>/forms/newpatient/save.php" name='new_encounter' onsubmit="return saveClicked();"
 <?php if (!$GLOBALS['concurrent_layout']) echo "target='Main'"; ?> >


<div class='container'>

<?php if ($viewmode) { ?>
<input type='hidden' name='mode' value='update'>
<input type='hidden' name='id' value='<?php echo (isset($_GET["id"])) ? attr($_GET["id"]) : '' ?>'>
<span class='title'><?php echo xlt('Patient Visit Form'); ?></span>
<?php } else { ?>
<input type='hidden' name='mode' value='new'>
<span class='title'><?php echo xlt('New Visit Form'); ?></span>
<?php } ?>


	<?php
     $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
     if($newcrop_user_role['newcrop_user_role']=='erxdoctor'|| $newcrop_user_role['newcrop_user_role']=='erxnurse'){
	?>
	<!--<div style="float:right; margin-top:-3px">
 <a href='<?php echo "$rootdir/patient_file/summary/stats_full.php"; ?>' class='css_button' id='back'><span><?php echo htmlspecialchars( xl('Back'), ENT_NOQUOTES); ?></span></a>
 <a href='<?php echo "$rootdir/patient_file/encounter/load_form.php?formname=vitals"; ?>' class='css_button' id='next'><span><?php echo htmlspecialchars( xl('Next'), ENT_NOQUOTES); ?></span></a>
</div>-->
	 <?php }?>
	
	


<br> <br>
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'"); ?>
<table width='96%'>

 <tr>

  <td width='33%' nowrap class='bold' 
   <?php if($newcrop_user_role['newcrop_user_role']!='erxdoctor' && $newcrop_user_role['newcrop_user_role']!='erxnurse') { ?>
  style="display:none"
   <?php  } ?>>
   
   <?php echo xlt('Consultation Brief Description'); ?>:</td>

  <td width='34%' rowspan='2' align='center' valign='center' class='text' <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor' || $newcrop_user_role['newcrop_user_role']=='erxnurse') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
   <table>

    <tr<?php if ($GLOBALS['athletic_team']) echo " style='visibility:hidden;'"; ?>>
	<div class="form-group">
     <label class="pull-left control-label"><?php echo xlt('Visit Category:'); ?></label>
   
      <select name='pc_catid'class="form-control" id='pc_catid'  required="required" onChange='getCatId(this.value);' />
	<option value='_blank'>-- <?php echo xlt('Select One'); ?> --</option>
<?php
 $cres = sqlStatement("SELECT pc_catid, pc_catname " .
  "FROM openemr_postcalendar_categories ORDER BY pc_catname");
 while ($crow = sqlFetchArray($cres)) {
  $catid = $crow['pc_catid'];
  if ($catid < 9 && $catid != 5) continue;
  echo "       <option value='" . attr($catid) . "'";
  if($result['pc_catid']=='')
  {
	  if($crow['pc_catid'] == 10) echo "selected";
  }else
  {
  if ($viewmode && $crow['pc_catid'] == $result['pc_catid']) echo " selected";
  }
  echo ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
 }
?>
      </select>
     </div>
    </tr>
	
		<tr>
     	<div class="form-group">
     <label class="pull-left control-label"><?php echo xlt('Doctor:'); ?></label>
     
<?php
  $ures = sqlStatement("SELECT id, username, fname, lname, specialty FROM users WHERE " .
  "authorized != 0 AND active = 1 ORDER BY fname, lname");
   echo "<select name='form_provider' class='form-control'  required='required' onchange='change_provider(this);'/>";
    while ($urow = sqlFetchArray($ures)) {
      echo "    <option value='" . attr($urow['id']) . "'";
	  if($result['provider_id']=='')
  {
	  if($urow['id'] == $defaultProvider) echo "selected";
  }else
  {
  if ($viewmode && $urow['id'] == $result['provider_id']) echo " selected";
  }
     // if ($urow['id'] == $defaultProvider) echo " selected";
      echo ">" . "".text($urow['fname']);
      if ($urow['lname']) echo " " . text($urow['lname']). " (" . text($urow['specialty'] . ")");;
      echo "</option>\n";
    }
    echo "</select>";
?>

     </div>
    </tr>
	<tr>
   <div class='form-group'>
   <label class='pull-left'><?php echo xlt('Package:'); ?></label>
	 
  
<?php


  $ures = sqlStatement("select * from procedure_type where parent=283");
   echo "<select name='package' class='form-control' />  <option value='0'></option>";
    while ($urow = sqlFetchArray($ures)) {
      echo "    <option value='" . attr($urow['name']) . "'";
    // if ($urow['id'] == $defaultProvider);
      echo ">" . text($urow['name']);
    
      echo "</option>\n";
    }
    echo "</select>";
?>
     </div>
	</tr>
	
	
	
	<tr>
		<div class="form-group" style='display:none' id='rplan'>
     <label class="pull-left control-label"><?php echo xlt('Rate Plan:'); ?></label>
	 
<?php
//get default insurance data
	$getdefins = sqlStatement("select provider,name from insurance_data a, insurance_companies b where pid='$pid' and a.provider = b.id");
	$getins = sqlFetchArray($getdefins);
  $ures = sqlStatement("select * from list_options where list_id='RatePlan' ");
   echo "<select name='rateplan' class='form-control' id='rateplans' onChange='getRatePlan(this.value);' />";
   
    while ($urow = sqlFetchArray($ures)) {
	  if($getins['provider']>0  & $urow['title']=="TPA Insurance") { 
	  echo "<option selected value='" . attr($urow['option_id']) . "'";
      echo ">" . text($urow['title']);
	  echo "</option>\n";
	  }	
	else
	{
      echo "    <option value='" . attr($urow['option_id']) . "'";
      //if ($urow['title'] == $defaultProvider) echo " selected";
      echo ">" . text($urow['title']);
    
      echo "</option>\n";
	}
	}		
    echo "</select>";
?>
</div>
	</tr>
	

   <tr>
  	<div class="form-group" style='display:none' id='instpa'>
     <label class="pull-left"><?php echo xlt('TPA:'); ?></label>
	 
<?php

	$ures = sqlStatement("select distinct name, id from insurance_companies ");
	echo "<select name='instpa' class='form-control'>";
	 if($getins['provider']>0){
     echo "<option value='".attr($getins['provider'])."'";
	 echo ">" . attr($getins['name']);
	 echo "</option>";
	 }
	 else
	 {
		  echo "<option value='0'>";
		  echo "</option>";
		 
	 }
    while ($urow = sqlFetchArray($ures)) {
		if ($getins['provider']!=$urow['id']){
      echo "    <option value='" . attr($urow['id']) . "'";
      //if ($urow['id'] == $defaultProvider) echo " selected";
      echo ">" . text($urow['name']);
    
      echo "</option>\n";
		}
    }
    echo "</select>";
	
	?>
	</div>
	</tr>

    <tr>
     	<div class="form-group" style='visibility:hidden;position:absolute'>
     <label class="pull-left"><?php echo xlt('Facility:'); ?></label>

      <select name='facility_id' class='form-control' onChange="bill_loc()">
<?php

if ($viewmode) {
  $def_facility = $result['facility_id'];
} else {
  $dres = sqlStatement("select facility_id from users where username = ?", array($_SESSION['authUser']));
  $drow = sqlFetchArray($dres);
  $def_facility = $drow['facility_id'];
}
$fres = sqlStatement("select * from facility where service_location != 0 order by name");
if ($fres) {
  $fresult = array();
  for ($iter = 0; $frow = sqlFetchArray($fres); $iter++)
    $fresult[$iter] = $frow;
  foreach($fresult as $iter) {
?>
       <option value="<?php echo attr($iter['id']); ?>" <?php if ($def_facility == $iter['id']) echo "selected";?>><?php echo text($iter['name']); ?></option>
<?php
  }
 }
?>
      </select>
     </div>
    </tr>
	<tr>
			<div class="form-group" style='visibility:hidden;position:absolute'>
     <label class="pull-left"><?php echo xlt('Billing Facility'); ?>:</label>
	
			<div id="ajaxdiv">
			<?php
			billing_facility('billing_facility',$result['billing_facility']);
			?>
			</div>
		</div>
     </tr>
    <tr>
<?php
 $sensitivities = acl_get_sensitivities();
 if ($sensitivities && count($sensitivities)) {
  usort($sensitivities, "sensitivity_compare");
?>
    	<div class="form-group" style='visibility:hidden;position:absolute'>
     <label class="pull-left"><?php echo xlt('Sensitivity:'); ?></label>
     
      <select name='form_sensitivity' class='form-control'>
<?php
  foreach ($sensitivities as $value) {
   // Omit sensitivities to which this user does not have access.
   if (acl_check('sensitivities', $value[1])) {
    echo "       <option value='" . attr($value[1]) . "'";
    if ($viewmode && $result['sensitivity'] == $value[1]) echo " selected";
    echo ">" . xlt($value[3]) . "</option>\n";
   }
  }
  echo "       <option value=''";
  if ($viewmode && !$result['sensitivity']) echo " selected";
  echo ">" . xlt('None'). "</option>\n";
?>
      </select>
     </div>
<?php
 } else {
?>
     <td colspan='2'><!-- sensitivities not used --></td>
<?php
 }
?>
    </tr>

 <!--   <tr<?php if (!$GLOBALS['gbl_visit_referral_source']) echo " style='visibility:hidden;'"; ?>>
    	<div class="form-group">
     <label class="pull-left"><?php echo xlt('Referral Source'); ?>:</label>
     
<?php
  echo generate_select_list('form_referral_source', 'refsource', $viewmode ? $result['referral_source'] : '', '');
?>
     </div>
    </tr> -->

    <tr>
     	<div class="form-group">
     <label class="pull-left  control-label"><?php echo xlt('Date of Service:'); ?></label>
  <br>
  <br>
	                 <div class='input-group date'  id='datetimepicker' >
                    
      <input type='text' size='10' class='form-control' name='form_date' id='form_date' <?php echo $disabled ?>
       value='<?php echo $viewmode ? date("d-m-Y h:i:s", strtotime($result['date'])) : date('d-m-Y h:i:s'); ?>'
       title='<?php echo xla('dd-mm-yyyy Date of service'); ?>' />
	   <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
       <!-- <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_form_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'> -->
     </div>
    </tr>

    <tr<?php if ($GLOBALS['ippf_specific'] || $GLOBALS['athletic_team']) echo " style='visibility:hidden;'"; ?>>
   	<div class="form-group" style='display:none' id='hospa'>
     <label class="pull-left"><?php echo xlt('Onset/hosp. date:'); ?></label>
   <!-- default is blank so that while generating claim the date is blank. -->
     <br>
  <br>
   <div class='input-group date'  id='datetimepicker1' >
                    
      <input type='text' size='10' class='form-control' name='form_onset_date' id='form_onset_date'
       value='<?php echo $viewmode && $result['onset_date']!='00-00-0000 00:00:00' ? date("d-m-Y h:i:s", strtotime($result['onset_date'])) : ''; ?>' 
       title='<?php echo xla('dd-mm-yyyy Date of onset or hospitalization'); ?>' />
      <!-- onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' -->
	      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
       <!-- <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22'
        id='img_form_onset_date' border='0' alt='[?]' style='cursor:pointer;cursor:hand'
        title='<?php echo xla('Click here to choose a date'); ?>'> -->
     </div>
    </tr>

    <tr>
     <td class='text' colspan='2' style='padding-top:1em'>
<?php if ($GLOBALS['athletic_team']) { ?>
      <p><i>Click [Add Issue] to add a new issue if:<br />
      New injury likely to miss &gt; 1 day<br />
      New significant illness/medical<br />
      New allergy - only if nil exist</i></p>
<?php } ?>
     </td>
    </tr>

   </table>

  </td>

  <td class='bold' width='33%'
  <?php  if($newcrop_user_role['newcrop_user_role']!='erxdoctor' && $newcrop_user_role['newcrop_user_role']!='erxnurse') { ?> style="visibility:hidden;position:absolute" <?php } else { ?> style="float:left" <?php } ?> nowrap>
    <div style='float:left'>
   <?php echo xlt('Issues (Injuries/Medical/Allergy)'); ?>
    </div>
    <div>
<?php if ($GLOBALS['athletic_team']) { // they want the old-style popup window ?>
      <a href="#" class="css_button_small link_submit"
       onclick="return newissue()"><span><?php echo xlt('Add'); ?></span></a>
<?php } else { ?>
      <a href="../../patient_file/summary/add_edit_issue.php" class="css_button_small link_submit iframe"
       onclick="top.restoreSession()"><span><?php echo xlt('Add'); ?></span></a>
<?php } ?>
    </div>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']!='erxdoctor'&& $newcrop_user_role['newcrop_user_role']!='erxnurse') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td class='text' valign='top'>
   <textarea name='reason' cols='40' rows='12' wrap='virtual' style='width:96%'
    ><?php echo $viewmode ? text($result['reason']) : text($GLOBALS['default_chief_complaint']); ?></textarea>
  </td>
  <td class='text' valign='top'>
   <select multiple name='issues[]' size='8' style='width:100%'
    title='<?php echo xla('Hold down [Ctrl] for multiple selections or to unselect'); ?>'>
<?php
while ($irow = sqlFetchArray($ires)) {
  $list_id = $irow['id'];
  $tcode = $irow['type'];
  if ($ISSUE_TYPES[$tcode]) $tcode = $ISSUE_TYPES[$tcode][2];
  echo "    <option value='" . attr($list_id) . "'";
  if ($viewmode) {
    $perow = sqlQuery("SELECT count(*) AS count FROM issue_encounter WHERE " .
      "pid = ? AND encounter = ? AND list_id = ?", array($pid,$encounter,$list_id));
    if ($perow['count']) echo " selected";
  }
  else {
    // For new encounters the invoker may pass an issue ID.
    if (!empty($_REQUEST['issue']) && $_REQUEST['issue'] == $list_id) echo " selected";
  }
  echo ">" . text($tcode) . ": " . text($irow['begdate']) . " " .
    text(substr($irow['title'], 0, 40)) . "</option>\n";
}
?>
   </select>

   <p><i><?php echo xlt('To link this encounter/consult to an existing issue, click the '
   . 'desired issue above to highlight it and then click [Save]. '
   . 'Hold down [Ctrl] button to select multiple issues.'); ?></i></p>

  </td>
 </tr>

</table>
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
  <?php if($newcrop_user_role['newcrop_user_role']=='erxdoctor'||$newcrop_user_role['newcrop_user_role']=='erxnurse') { ?>
  <div style = 'float:left; margin-left:8px;margin-top:10px'>
     <!-- <a href="javascript:saveClicked();" class="btn btn-primary link_submit" style="margin:10px"><span><?php echo xlt('Save'); ?></span></a>-->
    <input type="submit" name="submit"  class="btn btn-primary link_submit" value="Save" style="margin-right: 10px;"></input>
	</div>
	  <?php }else{
		  ?>
    <div style="position: fixed; style='display:none' ;
top: 10px;
right: 80px;"><div class="columns row" id="price" >
</div>
     <div><input type="submit" name="submit"  class="btn btn-primary" value="Save" style="margin-right: 15px;"></input>
	 <?php if (acl_check('acct', 'rep')) {	?>  <input type="submit" name="pay" id="pay" style="display:none"  class="btn btn-primary"  value="Save and Pay" formtarget="_blank"></input><?php }	  ?></div>
    </div>

	  <?php 
	  }?>
	   <?php if ($viewmode || !isset($_GET["autoloaded"]) || $_GET["autoloaded"] != "1") { ?>
    <div >
  <?php if ($GLOBALS['concurrent_layout']) {
if($newcrop_user_role['newcrop_user_role']!='erxdoctor' && $newcrop_user_role['newcrop_user_role']!='erxnurse') {	  ?>
  
      <a href="<?php echo "$rootdir/patient_file/encounter/encounter_top.php"; ?>"
        class="btn btn-default" style="margin:10px" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
<?php } else if($newcrop_user_role['newcrop_user_role']=='erxdoctor') {?>
     <a href="<?php echo "$rootdir/patient_file/encounter/load_form.php?formname=procedure_order"; ?>"
        class="btn btn-default" style="margin:10px" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
<?php } else {
$rid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$rid1=sqlFetchArray($rid);
		$rid2=$rid1['form_id'];
			
		if($rid2 == null) {
 ?>
 <a href="<?php echo "$rootdir/patient_file/encounter/load_form.php?formname=ros"; ?>"
        class="btn btn-default" style="margin:10px" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
<?php }else{?>
			 <a href="<?php echo "$rootdir/patient_file/encounter/view_form.php?formname=ros&id=".$rid2; ?>"
        class="btn btn-default" style="margin:10px" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
	<?php	}
	
 }
 ?>
  <?php } else { ?>
      <a href="<?php echo "$rootdir/patient_file/encounter/patient_encounter.php"; ?>"
        class="btn btn-warning" target='Main' onClick="top.restoreSession()">
      <span><?php echo xlt('Cancel'); ?>]</span></a>
  <?php } // end not concurrent layout ?>
  <?php } else if ($GLOBALS['concurrent_layout']) { // not $viewmode ?>
      <a href="" class="btn btn-warning" onClick="return cancelClicked()">
      <span><?php echo xlt('Cancel'); ?></span></a>
  <?php } // end not $viewmode ?>
    </div>
</div>
</form>
		<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>

        <script type="text/javascript">
		var j = jQuery.noConflict();
            j(function () {
                j('#datetimepicker1').datetimepicker({
                    format: 'DD-MM-YYYY HH:mm:ss'
                });
				                j('#datetimepicker').datetimepicker({
                    format: 'DD-MM-YYYY HH:mm:ss'
                });
            });
			j(document).ready(function(){
	            j('input.city').typeahead({
                name: 'genericname1',
                remote: 'mysql.php?query=%QUERY',
			
            });
			j("#TypeAheadInput").on("typeahead:selected typeahead:autocompleted", function(e,datum) { var str = datum.label; var str_array = str.split(','); var MRN =str_array[0];      
			$.ajax({
        type:"GET",
        cache:false,
        url:"serPatient.php",
        data:{ mrn : MRN },    // multiple data sent using ajax
        success: function (html) {
		//	console.log(html);
var result = $.parseJSON(html);

var date= result['fruits'][4];
var dateAr = date.split('-');
var newDate = dateAr[2] + '/' + dateAr[1] + '/' + dateAr[0];
var age = getAge(newDate);
//console.log(result['fruits'][1] +' '+result['fruits'][2],result['fruits'][0],result['fruits'][3],result['fruits'][5],'DOB: '+newDate+' Age: '+age);
 parent.left_nav.setPatient(result['fruits'][1] +' '+result['fruits'][2],result['fruits'][0],result['fruits'][3],result['fruits'][5],'DOB: '+newDate+' Age: '+age);
 var EncounterDateArray = new Array;
 var CalendarCategoryArray = new Array;
 var EncounterIdArray = new Array;
 var Count = 0;
 $.each(result['animals'], function(bb) {
	 EncounterIdArray[Count] = result['animals'][bb].encounter;
	 var dt = new Date(result['animals'][bb].date);

var edate = dt.getDate();
var emon = dt.getMonth() + 1;
if (emon.toString().length == 1) {
emon = "0" + emon;
}
if (edate.toString().length == 1) {
edate = "0" + edate;
}
var eyr = dt.getFullYear();
 EncounterDateArray[Count] = edate+'/'+emon+'/'+eyr;
 CalendarCategoryArray[Count] = result['animals'][bb].pc_catname;
 Count++;
 

});
 parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
        }
      });
 });
			//console.log(j('.tt-input').typeahead('label')); // and works as you'd expect

  enable_big_modals();
});
function getAge(dateString) {
  var now = new Date();
  var today = new Date(now.getYear(),now.getMonth(),now.getDate());

  var yearNow = now.getYear();
  var monthNow = now.getMonth();
  var dateNow = now.getDate();

  var dob = new Date(dateString.substring(6,10),
                     dateString.substring(0,2)-1,                   
                     dateString.substring(3,5)                  
                     );

  var yearDob = dob.getYear();
  var monthDob = dob.getMonth();
  var dateDob = dob.getDate();
  var age = {};
  var ageString = "";
  var yearString = "";
  var monthString = "";
  var dayString = "";


  yearAge = yearNow - yearDob;

  if (monthNow >= monthDob)
    var monthAge = monthNow - monthDob;
  else {
    yearAge--;
    var monthAge = 12 + monthNow -monthDob;
  }

  if (dateNow >= dateDob)
    var dateAge = dateNow - dateDob;
  else {
    monthAge--;
    var dateAge = 31 + dateNow - dateDob;

    if (monthAge < 0) {
      monthAge = 11;
      yearAge--;
    }
  }

  age = {
      years: yearAge,
      months: monthAge,
      days: dateAge
      };

  if ( age.years > 1 ) yearString = " years";
  else yearString = " year";
  if ( age.months> 1 ) monthString = " months";
  else monthString = " month";
  if ( age.days > 1 ) dayString = " days";
  else dayString = " day";


  if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
    ageString = age.years + yearString + ", " + age.months + monthString + ", and " + age.days + dayString + " old.";
  else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
    ageString = "Only " + age.days + dayString + " old!";
  else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
    ageString = age.years + yearString + " old. Happy Birthday!!";
  else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
    ageString = age.years + yearString + " and " + age.months + monthString + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
    ageString = age.months + monthString + " and " + age.days + dayString + " old.";
  else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
    ageString = age.years + yearString + " and " + age.days + dayString + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
    ageString = age.months + monthString + " old.";
  else ageString = "Oops! Could not calculate age!";

  return yearAge;
}
        </script>

		              <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/moment/moment.js" ></script>
                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap-datetimepicker4.7.14.min.js" type="text/javascript"></script>
					                    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/bootstrap.min.js" type="text/javascript"></script>
<script src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-functions.js"> //optional!!</script>
<!--<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.4.3.min.js"></script>-->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/overlib_mini.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>

<!-- pop up calendar -->

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/ajax/facility_ajax_jav.inc.php"); ?>
<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
										

<script language="javascript">
//alert = function() {};
/* required for popup calendar 
Calendar.setup({inputField:"form_date", ifFormat:"%Y-%m-%d", button:"img_form_date"});
Calendar.setup({inputField:"form_onset_date", ifFormat:"%Y-%m-%d", button:"img_form_onset_date"});
*/
<?php
if (!$viewmode) { ?>
 function duplicateVisit(enc, datestr) {
	 var p=<?php echo $pid  ?>;
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
    "f.formdir = 'newpatient' AND f.form_id = fe.id AND f.deleted = 0 and fe.pid!=0 " .
    "ORDER BY fe.encounter DESC LIMIT 1",array($pid,date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')));

  if (!empty($erow['encounter'])) {
    // If there is an encounter from today then present the duplicate visit dialog
    echo "duplicateVisit('" . $erow['encounter'] . "', '" .
      oeFormatShortDate(substr($erow['date'], 0, 10)) . "');\n";
  }
}
?>
$('#billing_facility').addClass('form-control');
$('#form_referral_source').addClass('form-control');

</script>
</body>
</html>
