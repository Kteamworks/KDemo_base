<?php
/**
 * Copyright (C) 2005-2014 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/encounter.inc');
require_once($GLOBALS['srcdir'].'/lists.inc');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once($GLOBALS['fileroot'].'/custom/code_types.inc.php');
require_once($GLOBALS['srcdir'].'/options.inc.php');

  if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
 $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
setencounter($encounter);
 // Check authorization.
 if (acl_check('patients','med')) {
  $tmp = getPatientData($pid, "squad");
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad']))
   die(htmlspecialchars( xl('Not authorized'), ENT_NOQUOTES) );
 }
 else {
  die(htmlspecialchars( xl('Not authorized'), ENT_NOQUOTES) );
 }

 // Collect parameter(s)
 $category = empty($_REQUEST['category']) ? '' : $_REQUEST['category'];

// Get patient's preferred language for the patient education URL.
$tmp = getPatientData($pid, 'language');
$language = $tmp['language'];
?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<title><?php echo xlt('Patient Issues'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
  	
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js"></script>

<script language="JavaScript">

// callback from add_edit_issue.php:
function refreshIssue(issue, title) {
    top.restoreSession();
    location.reload();
}

function dopclick(id,category) {
    <?php if (acl_check('patients','med','','write')): ?>
    if (category == 0) category = '';
    dlgopen('add_edit_issue.php?issue=' + encodeURIComponent(id) + '&thistype=' + encodeURIComponent(category), '_blank', 550, 400);
    <?php else: ?>
    alert("<?php echo addslashes( xl('You are not authorized to add/edit issues') ); ?>");
    <?php endif; ?>
}

// Process click on number of encounters.
function doeclick(id) {
    dlgopen('../problem_encounter.php?issue=' + id, '_blank', 550, 400);
}

// Process click on diagnosis for patient education popup.
function educlick(codetype, codevalue) {
  dlgopen('../education.php?type=' + encodeURIComponent(codetype) +
    '&code=' + encodeURIComponent(codevalue) +
    '&language=<?php echo urlencode($language); ?>',
    '_blank', 1024, 750);
}

// Add Encounter button is clicked.
function newEncounter() {
 var f = document.forms[0];
 top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
 parent.left_nav.setRadio(window.name, 'nen');
 location.href='../../forms/newpatient/new.php?autoloaded=1&calenc=';
<?php } else { ?>
 top.Title.location.href='../encounter/encounter_title.php';
 top.Main.location.href='../encounter/patient_encounter.php?mode=new';
<?php } ?>
}

</script>
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

<?php $rid=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
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
		<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'");
 ?>
  <?php if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?>
<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
			<li class="current"></i><em>Medical Issues</em></li>
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
			<li><a href="../transaction/add_transaction.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>
			<li><a href="../../patient_file/encounter/admit_doctor_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li><a href="summary_print.php">Summary</a></li>
		</ol>
	</nav>
</section>
  <?php } elseif($newcrop_user_role['newcrop_user_role']=='erxnurse'){?>
  <section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
			
			<?php if($vid2 == null) { ?>
			<li class="current"><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
		</ol>
	</nav>
</section>	
  
  <?php } ?>
<!-- <div style="float:right" class="buttons">
  <a href='javascript:;' class='css_button' id='back'><span><?php echo htmlspecialchars( xl('Back'), ENT_NOQUOTES); ?></span></a>
</div>
<div style="float:right;align:right" class="buttons">
  <a href='javascript:;' class='css_button' id='next'><span><?php echo htmlspecialchars( xl('Next'), ENT_NOQUOTES); ?></span></a>
</div> -->
<br>
<br>

<div id='patient_stats'>

<form method='post' action='stats_full.php' onsubmit='return top.restoreSession()'>

<table>

<?php
$encount = 0;
$lasttype = "";
$first = 1; // flag for first section
foreach ($ISSUE_TYPES as $focustype => $focustitles) {

  if ($category) {
    // Only show this category
    if ($focustype != $category) continue;
	
  }

  if ($first) {
    $first = 0;
  }
  else {
    echo "</table>";
  }

  // Show header
  $disptype = $focustitles[0];
  if($focustype!='delivery'&& $focustype!='dental')
{
  if(($focustype=='allergy' || $focustype=='medication') && $GLOBALS['erx_enable'])
  echo "<a href='../../eRx.php?page=medentry' class='css_button_small' onclick='top.restoreSession()' ><span>" . htmlspecialchars( xl('Add'), ENT_NOQUOTES) . "</span></a>\n";
  else
  echo "<a href='javascript:;' class='css_button_small' onclick='dopclick(0,\"" . htmlspecialchars($focustype,ENT_QUOTES)  . "\")'><span>" . htmlspecialchars( xl('Add'), ENT_NOQUOTES) . "</span></a>\n";

  echo "  <span class='title'>" . htmlspecialchars($disptype,ENT_NOQUOTES) . "</span>\n";
  // echo " <table style='margin-bottom:1em;text-align:center'>";
  echo " <table style='margin-bottom:1em;'>";
  ?>
  <tr class='head'>
    <th style='text-align:left'><?php echo xlt('Title'); ?></th>
    <th style='text-align:left'><?php echo xlt('Begin'); ?></th>
    <th style='text-align:left'><?php echo xlt('End'); ?></th>
    <th style='text-align:left'><?php echo xlt('Coding (click for education)'); ?></th>
    <th style='text-align:left'><?php echo xlt('Status'); ?></th>
    <th style='text-align:left'><?php echo xlt('Occurrence'); ?></th>
    <?php if ($focustype == "allergy") { ?>
      <th style='text-align:left'><?php echo xlt('Reaction'); ?></th>
    <?php } ?>
    <?php if ($GLOBALS['athletic_team']) { ?>
      <th style='text-align:left'><?php echo xlt('Missed'); ?></th>
    <?php } else { ?>
      <th style='text-align:left'><?php echo xlt('Referred By'); ?></th>
    <?php } ?>
    <th style='text-align:left'><?php echo xlt('Comments'); ?></th>
    <th><?php echo xlt('Visit'); ?></th>
    </tr>
  <?php

  // collect issues
  $condition = '';
  if($GLOBALS['erx_enable'] && $GLOBALS['erx_medication_display'] && $focustype=='medication')
   $condition .= "and erx_uploaded != '1' ";
  $pres = sqlStatement("SELECT * FROM lists WHERE pid = ? AND type = ? $condition" .
   "ORDER BY begdate", array($pid,$focustype) );

  // if no issues (will place a 'None' text vs. toggle algorithm here)
  if (sqlNumRows($pres) < 1) {
    if ( getListTouch($pid,$focustype) ) {
      // Data entry has happened to this type, so can display an explicit None.
      echo "<tr><td class='text'><b>" . xlt("None") . "</b></td></tr>";
    }
    else {
      // Data entry has not happened to this type, so can show the none selection option.
      echo "<tr><td class='text'><input type='checkbox' class='noneCheck' name='" .
        attr($focustype) . "' value='none' /><b>" . xlt("None") . "</b></td></tr>";
    }
  }

  // display issues
  while ($row = sqlFetchArray($pres)) {

    $rowid = $row['id'];

    $disptitle = trim($row['title']) ? $row['title'] : "[Missing Title]";

    $ierow = sqlQuery("SELECT count(*) AS count FROM issue_encounter WHERE " .
      "list_id = ?", array($rowid) );

    // encount is used to toggle the color of the table-row output below
    ++$encount;
    $bgclass = (($encount & 1) ? "bg1" : "bg2");

    $colorstyle = empty($row['enddate']) ? "style='color:red'" : "";

    // look up the diag codes
    $codetext = "";
    if ($row['diagnosis'] != "") {
        $diags = explode(";", $row['diagnosis']);
        foreach ($diags as $diag) {
            $codedesc = lookup_code_descriptions($diag);
            list($codetype, $code) = explode(':', $diag);
            if ($codetext) $codetext .= "<br />";
            $codetext .= "<a href='javascript:educlick(\"$codetype\",\"$code\")' $colorstyle>" .
              text($diag . " (" . $codedesc . ")") . "</a>";
        }
    }

    // calculate the status
    if ($row['outcome'] == "1" && $row['enddate'] != NULL) {
      // Resolved
      $statusCompute = generate_display_field(array('data_type'=>'1','list_id'=>'outcome'), $row['outcome']);
    }
    else if($row['enddate'] == NULL) {
      $statusCompute = xlt("Active");
    }
    else {
      $statusCompute = xlt("Inactive");
    }
    $click_class='statrow';
    if($row['erx_source']==1 && $focustype=='allergy') $click_class='';
    elseif($row['erx_uploaded']==1 && $focustype=='medication') $click_class='';

    echo " <tr class='$bgclass detail' $colorstyle>\n";
    echo "  <td style='text-align:left' class='$click_class' id='$rowid'>" . text($disptitle) . "</td>\n";
    echo "  <td>" . text($row['begdate']) . "&nbsp;</td>\n";
    echo "  <td>" . text($row['enddate']) . "&nbsp;</td>\n";
    // both codetext and statusCompute have already been escaped above with htmlspecialchars)
    echo "  <td>" . $codetext . "</td>\n";
    echo "  <td>" . $statusCompute . "&nbsp;</td>\n";
    echo "  <td class='nowrap'>";
    echo generate_display_field(array('data_type'=>'1','list_id'=>'occurrence'), $row['occurrence']);
    echo "</td>\n";
    if ($focustype == "allergy") {
      echo "  <td>" . text($row['reaction']) . "&nbsp;</td>\n";
    }
    if ($GLOBALS['athletic_team']) {
        echo "  <td class='center'>" . $row['extrainfo'] . "</td>\n"; // games missed
    }
    else {
        echo "  <td>" . text($row['referredby']) . "</td>\n";
    }
    echo "  <td>" . text($row['comments']) . "</td>\n";
    echo "  <td id='e_$rowid' class='noclick center' title='" . xla('View related encounters') . "'>";
    echo "  <input type='button' value='" . attr($ierow['count']) . "' class='editenc' id='" . attr($rowid) . "' />";
    echo "  </td>";
    echo " </tr>\n";
  }
}
}
echo "</table>";
?>

</table>

</form>
</div> <!-- end patient_stats -->

</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
	<?php 
	$id=sqlStatement("SELECT id from form_encounter where encounter='".$_SESSION['encounter']."'");
	$id1=sqlFetchArray($id);
	$id2=$id1['id'];
	
	?>
    $(".statrow").mouseover(function() { $(this).toggleClass("highlight"); });
    $(".statrow").mouseout(function() { $(this).toggleClass("highlight"); });

    $(".statrow").click(function() { dopclick(this.id,0); });
    $(".editenc").click(function(event) { doeclick(this.id); });
    $("#newencounter").click(function() { newEncounter(); });
    $("#history").click(function() { GotoHistory(); });
    $("#back").click(function() { GoBack(); });
	var id = '<?php echo "$id2";?>';
	$("#next").click(function() { 
	location.href='../encounter/view_form.php?formname=newpatient&id='+id;
	});

    $(".noneCheck").click(function() {
      top.restoreSession();
      $.post( "../../../library/ajax/lists_touch.php", { type: this.name, patient_id: <?php echo htmlspecialchars($pid,ENT_QUOTES); ?> });
      $(this).hide(); 
    });
});

var GotoHistory = function() {
    top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']): ?>
    parent.left_nav.setRadio(window.name,'his');
    location.href='../history/history_full.php';
<?php else: ?>
    location.href='../history/history_full.php';
<?php endif; ?>
}

var GoBack = function () {
    top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']): ?>
    parent.left_nav.setRadio(window.name,'dem');
    location.href='demographics.php';
<?php else: ?>
    location.href="patient_summary.php";
<?php endif; ?>
}

</script>

</html>
