<?php
/**
* Encounter form for entering procedure orders.
*
* Copyright (C) 2010-2013 Rod Roark <rod@sunsetsystems.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
*
* @package   OpenEMR
* @author    Rod Roark <rod@sunsetsystems.com>
*/

require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../orders/qoe.inc.php");
require_once("../../orders/gen_hl7_order.inc.php");
require_once("../../../custom/code_types.inc.php");
require_once($GLOBALS['srcdir'].'/encounter.inc');
require_once("$srcdir/patient.inc");

// Defaults for new orders.
$row = array(
  'provider_id' => $_SESSION['authUserID'],
  'date_ordered' => date('Y-m-d'),
  'date_collected' => date('Y-m-d H:i'),
);
if (! $encounter) { // comes from globals.php
 die("Internal error: we do not seem to be in an encounter!");
}

function cbvalue($cbname) {
 return $_POST[$cbname] ? '1' : '0';
}

function cbinput($name, $colname) {
 global $row;
 $ret  = "<input type='checkbox' name='$name' value='1'";
 if ($row[$colname]) $ret .= " checked";
 $ret .= " />";
 return $ret;
}

function cbcell($name, $desc, $colname) {
 return "<td width='25%' nowrap>" . cbinput($name, $colname) . "$desc</td>\n";
}

function QuotedOrNull($fld) {
  if (empty($fld)) return "NULL";
  return "'$fld'";
}

$formid = formData('id', 'G') + 0;

// If Save or Transmit was clicked, save the info.
//
if ($_POST['bn_save'] || $_POST['bn_xmit']) {
  $ppid = formData('form_lab_id') + 0;

  $sets =
    "date_ordered = " . QuotedOrNull(formData('form_date_ordered'))     . ", " .
    "provider_id = " . (formData('form_provider_id') + 0)               . ", " .
    "lab_id = " . $ppid                                                 . ", " .
    "date_collected = " . QuotedOrNull(formData('form_date_collected')) . ", " .
    "order_priority = '" . formData('form_order_priority')              . "', " .
    "order_status = '" . formData('form_order_status')                  . "', " .
    "clinical_hx = '" . formData('form_clinical_hx')                    . "', " .
    "patient_instructions = '" . formData('form_patient_instructions')  . "', " .
    "patient_id = '" . $pid                                             . "', " .
    "encounter_id = '" . $encounter                                     . "'";

  // If updating an existing form...
  //
  if ($formid) {
    $query = "UPDATE procedure_order SET $sets "  .
      "WHERE procedure_order_id = '$formid'";
    sqlStatement($query);
	
	
	
  }

  // If adding a new form...
  //
  else {
    $query = "INSERT INTO procedure_order SET $sets";
    $formid = sqlInsert($query);
    addForm($encounter, "Lab Investigation", $formid, "procedure_order", $pid, $userauthorized);
	
	
	
	
  }

  // Remove any existing procedures and their answers for this order and
  // replace them from the form.

  sqlStatement("DELETE FROM procedure_answers WHERE procedure_order_id = ?",
    array($formid));
  sqlStatement("DELETE FROM procedure_order_code WHERE procedure_order_id = ?",
    array($formid));
	$procedures=$_POST['form_proc_type'];
  //for ($i = 0; isset($_POST['form_proc_type'][$i]); ++$i) {
	  foreach($procedures as $procedure)
	  {
		  //var_dump($procedure);
		  //exit;
		  
    $ptid = $procedure;
	
    if ($ptid <= 0) continue;
	$sqry = sqlStatement("SELECT  name FROM procedure_type WHERE procedure_type_id='".$procedure."'");
	$sqry1=sqlFetchArray($sqry);
	$proc_desc=$sqry1['name'];
	//$ptid=add_escape_custom($ptid);
	//$try='FBS';
	$patient=getPatientData($pid, "rateplan");
    $rate=$patient['rateplan'];
	if($rate=="HosInsurance")
	{
	$qgetfee1=sqlStatement("SELECT a.code as cd ,a.code_text as cd_tx,a.code_type as cd_ty,a.service_id service_id,b.pr_price as fee from codes a,prices b  where a.id=b.pr_id and a.code_type=7 and b.pr_level='HosInsurance' and a.code='".$proc_desc."'");
	$qgetlabfees2=sqlFetchArray($qgetfee1);
	$codetype="Lab Test";
	$servicegrp_id=$qgetlabfees2['cd_ty'];
	$service_id=$qgetlabfees2['service_id'];
	$code=$qgetlabfees2['cd'];
	$codetext=$qgetlabfees2['cd_tx'];
	$fee=$qgetlabfees2['fee'];
	$billed=0;
    $units=1;
   //$fee=$row2['pr_price'];
    $authrzd=1;
    $modif="";
    $act=1;
	$provider_id = formData('form_provider_id');
	$onset_date=date('Y-m-d');
    $grpn="HosInsurance";
	}
	else{
	$qgetfee=sqlStatement("SELECT a.code as cd ,a.code_text as cd_tx,a.code_type as cd_ty,a.service_id service_id,b.pr_price as fee from codes a,prices b  where a.id=b.pr_id and a.code_type=7 and b.pr_level='standard' and a.code='".$proc_desc."'");
    $qgetlabfees=sqlFetchArray($qgetfee);
	$codetype="Lab Test";
	$servicegrp_id=$qgetlabfees['cd_ty'];
	$service_id=$qgetlabfees['service_id'];
	$code=$qgetlabfees['cd'];
	$codetext=$qgetlabfees['cd_tx'];
	$fee=$qgetlabfees['fee'];
	$billed=0;
    $units=1;
   //$fee=$row2['pr_price'];
    $authrzd=1;
    $modif="";
    $act=1;
	$provider_id = formData('form_provider_id');
	$onset_date=date('Y-m-d H:i:s');
    $grpn="Default";
	}
     sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
	  "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
      "service_id = '" . add_escape_custom($service_id) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
      "code = '" . add_escape_custom($code) . "', " .
      "code_text = '" . add_escape_custom($codetext) . "', " .
      "units = '" . add_escape_custom($units) . "', " .
      "billed = '" . add_escape_custom($billed) . "', " .
      "fee = '" . add_escape_custom($fee) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
      "modifier = '" . add_escape_custom($modif) . "', " .
      "authorized = '" . add_escape_custom($authrzd) . "', " .
      "activity = '" . add_escape_custom($act) . "', " .
      "groupname = '" . add_escape_custom($grpn) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'");
    sqlQuery("Update billing_main_copy set total_charges=total_charges + ? where encounter=?",array($fee,$encounter));   
	$prefix = "ans$i" . "_";
  $diagnosis='';
    $poseq = sqlInsert("INSERT INTO procedure_order_code SET ".
      "procedure_order_id = ?, " .
      "diagnoses = ?, " .
      "procedure_code = (SELECT procedure_code FROM procedure_type WHERE procedure_type_id = ?), " .
      "procedure_name = (SELECT name FROM procedure_type WHERE procedure_type_id = ?)",
      array($formid, $diagnosis, $ptid, $ptid));

    $qres = sqlStatement("SELECT " .
      "q.procedure_code, q.question_code, q.options, q.fldtype " .
      "FROM procedure_type AS t " .
      "JOIN procedure_questions AS q ON q.lab_id = t.lab_id " .
      "AND q.procedure_code = t.procedure_code AND q.activity = 1 " .
      "WHERE t.procedure_type_id = ? " .
      "ORDER BY q.seq, q.question_text", array($ptid));

    while ($qrow = sqlFetchArray($qres)) {
      $options = trim($qrow['options']);
      $qcode = trim($qrow['question_code']);
      $fldtype = $qrow['fldtype'];
      $data = '';
      if ($fldtype == 'G') {
        if ($_POST["G1_$prefix$qcode"]) {
          $data = $_POST["G1_$prefix$qcode"] * 7 + $_POST["G2_$prefix$qcode"];
        }
      }
      else {
        $data = $_POST["$prefix$qcode"];
      }
      if (!isset($data) || $data === '') continue;
      if (!is_array($data)) $data = array($data);
      foreach ($data as $datum) {
        // Note this will auto-assign the seq value.
        sqlStatement("INSERT INTO procedure_answers SET ".
          "procedure_order_id = ?, " .
          "procedure_order_seq = ?, " .
          "question_code = ?, " .
          "answer = ?",
          array($formid, $poseq, $qcode, strip_escape_custom($datum)));
      }
    }
  }

  $alertmsg = '';
  if ($_POST['bn_xmit']) {
    $hl7 = '';
    $alertmsg = gen_hl7_order($formid, $hl7);
    if (empty($alertmsg)) {
      $alertmsg = send_hl7_order($ppid, $hl7);
    }
    if (empty($alertmsg)) {
      sqlStatement("UPDATE procedure_order SET date_transmitted = NOW() WHERE " .
        "procedure_order_id = ?", array($formid));
    }
  }

  formHeader("Redirecting....");
  if ($alertmsg) {
    echo "\n<script language='Javascript'>alert('";
    echo addslashes(xl('Transmit failed') . ': ' . $alertmsg);
    echo "')</script>\n";
  }
  $res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
if($res['newcrop_user_role'] == 'erxnurse' || $res['newcrop_user_role'] == 'erxdoctor' ){
  formJump("../../../controller.php?prescription&edit&id=&pid=$pid");
}else
{
	formJump();
}
  formFooter();
  exit;
}

if ($formid) {
  $row = sqlQuery ("SELECT * FROM procedure_order WHERE " .
    "procedure_order_id = ?",
    array($formid)) ;
}

$enrow = sqlQuery("SELECT p.fname, p.mname, p.lname, fe.date FROM " .
  "form_encounter AS fe, forms AS f, patient_data AS p WHERE " .
  "p.pid = ? AND f.pid = p.pid AND f.encounter = ? AND " .
  "f.formdir = 'newpatient' AND f.deleted = 0 AND " .
  "fe.id = f.form_id LIMIT 1",
  array($pid, $encounter));
?>
<html>
<head>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/stylesheet.css">
<?php html_header_show(); ?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css" />

<style>
dl {
	display:none;
}
div.faq:nth-of-type(odd) {
    background: #e0e0e0;
}
td {
 font-size:10pt;
}

.inputtext {
 padding-left:2px;
 padding-right:2px;
}

</style>

<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	
		<!--[if IE 8]><script src="js/es5.js"></script><![endif]-->
	   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="js/selectize.js"></script>
		<script src="js/index.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>

<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->


	<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script> 
<script language='JavaScript'>

// This invokes the find-procedure-type popup.
// formseq = 0-relative index in the form.
var gbl_formseq;
function sel_proc_type(formseq) {
 var f = document.forms[0];
 // if (!f.form_lab_id.value) {
 //  alert('<?php echo xls('Please select a procedure provider'); ?>');
 //  return;
 // }
 gbl_formseq = formseq;
 var ptvarname = 'form_proc_type[' + formseq + ']';
 /********************************************************************
 dlgopen('../../orders/types.php?popup=1' +
  '&labid=' + f.form_lab_id.value +
  '&order=' + f[ptvarname].value +
  '&formid=<?php echo $formid; ?>' +
  '&formseq=' + formseq,
  '_blank', 800, 500);
 ********************************************************************/
 // This replaces the above for an easier/faster order picker tool.
 dlgopen('../../orders/find_order_popup.php' +
  '?labid=' + f.form_lab_id.value +
  '&order=' + f[ptvarname].value +
  '&formid=<?php echo $formid; ?>' +
  '&formseq=' + formseq,
  '_blank', 800, 500);
}

// This is for callback by the find-procedure-type popup.
// Sets both the selected type ID and its descriptive name.
function set_proc_type(typeid, typename) {
 var f = document.forms[0];
 var ptvarname = 'form_proc_type[' + gbl_formseq + ']';
 var ptdescname = 'form_proc_type_desc[' + gbl_formseq + ']';
 f[ptvarname].value = typeid;
 f[ptdescname].value = typename;
}

// This is also for callback by the find-procedure-type popup.
// Sets the contents of the table containing the form fields for questions.
function set_proc_html(s, js) {
 document.getElementById('qoetable[' + gbl_formseq + ']').innerHTML = s;
 eval(js);
}

// New lab selected so clear all procedures and questions from the form.
function lab_id_changed() {
 var f = document.forms[0];
 for (var i = 0; true; ++i) {
  var ix = '[' + i + ']';
  if (!f['form_proc_type' + ix]) break;
  f['form_proc_type' + ix].value = '-1';
  f['form_proc_type_desc' + ix].value = '';
  document.getElementById('qoetable' + ix).innerHTML = '';
 }
}

// Add a line for entry of another procedure.
function addProcLine() {
 var f = document.forms[0];
 var table = document.getElementById('proctable');
 // Compute i = next procedure index.
 var i = 0;
 for (; f['form_proc_type[' + i + ']']; ++i);
 var row = table.insertRow(table.rows.length);
 var cell = row.insertCell(0);
 cell.vAlign = 'top';
 cell.innerHTML = "<b><?php echo xls('Procedure'); ?> " + (i + 1) + ":</b>";
 var cell = row.insertCell(1);
 cell.vAlign = 'top';
 cell.innerHTML =
  "<input type='text' size='50' name='form_proc_type_desc[" + i + "]'" +
  " onclick='sel_proc_type(" + i + ")'" +
  " onfocus='this.blur()'" +
  " title='<?php echo xla('Click to select the desired procedure'); ?>'" +
  "  style='width:100%;cursor:pointer;cursor:hand' readonly />" +
  " <input type='hidden' name='form_proc_type[" + i + "]' value='-1' />" +
  "<br /><?php echo xla('Diagnosis Codes'); ?>: " +
  "<input type='text' size='50' name='form_proc_type_diag[" + i + "]'" +
  " onclick='sel_related(this.name)'" +
  " title='<?php echo xla('Click to add a diagnosis'); ?>'" +
  " onfocus='this.blur()'" +
  " style='cursor:pointer;cursor:hand' readonly />" +
  " <div style='width:95%;' id='qoetable[" + i + "]'></div>";
 sel_proc_type(i);
 return false;
}

// The name of the form field for find-code popup results.
var rcvarname;

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f[rcvarname].value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f[rcvarname].value = s;
}

// This invokes the find-code popup.
function sel_related(varname) {
 rcvarname = varname;
 // codetype is just to make things easier and avoid mistakes.
 // Might be nice to have a lab parameter for acceptable code types.
 // Also note the controlling script here runs from interface/patient_file/encounter/.
 dlgopen('find_code_popup.php?codetype=<?php echo attr(collect_codetypes("diagnosis","csv")) ?>', '_blank', 500, 400);
}

var transmitting = false;

// Issue a Cancel/OK warning if a previously transmitted order is being transmitted again.
function validate(f) {
<?php if (!empty($row['date_transmitted'])) { ?>
 if (transmitting) {
  if (!confirm('<?php echo xls('This order was already transmitted on') . ' ' .
    addslashes($row['date_transmitted']) . '. ' .
    xls('Are you sure you want to transmit it again?'); ?>')) {
    return false;
  }
 }
<?php } ?>
 top.restoreSession();
 return true;
}

</script>

</head>
<body class="body_top" style="background: white">
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
			<li class="current"></i><em>Lab Tests</em></li>
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
  <?php }?>
<form method="post" action="<?php echo $rootdir ?>/forms/procedure_order/new.php?id=<?php echo $formid ?>"
 onsubmit="return validate(this)">

<p class='title' style='margin-top:8px;margin-bottom:8px;text-align:center'>
<?php
  echo xl('Lab Investigation for') . ' ';
  echo $enrow['fname'] . ' ' . $enrow['mname'] . ' ' . $enrow['lname'];
  echo ' ' . xl('on') . ' ' . oeFormatShortDate(substr($enrow['date'], 0, 10));
  $id=sqlStatement("SELECT form_id from forms where encounter='".$_SESSION['encounter']."' and formdir='ros' order by form_id desc limit 1 ");
		$id1=sqlFetchArray($id);
		$id2=$id1['form_id'];
?>
<!--<input type='button' value='<?php echo xla('BACK'); ?>'  style='float: right;
margin-bottom: 10px;' class="pull-right" onclick="top.restoreSession();location='../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $id2 ?>'" />
<input type='button' value='<?php echo xla('NEXT'); ?>'  style='float: right;
margin-bottom: 10px;' class="pull-right" onclick="top.restoreSession();location='../../../controller.php?prescription&edit&id=&pid=<?php echo $pid ?>'" />-->

</p>

<div class="">
<div class="col-md-8">
<?php $newcrop_user_role=sqlQuery("select newcrop_user_role from users where username='".$_SESSION['authUser']."'"); ?>


<table class="table table-bordered" style="width:100%" id='proctable'>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Ordering Provider','e'); ?>:</b></td>
  <td valign='top'>
<?php
generate_form_field(array('data_type'=>10,'field_id'=>'provider_id'),
  $row['provider_id']);
?>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Sending To','e'); ?>:</b></td>
  <td valign='top'>
   <select name='form_lab_id' onchange='lab_id_changed()'>
 <?php
  $ppres = sqlStatement("SELECT ppid, name FROM procedure_providers " .
    "ORDER BY ppid");
  while ($pprow = sqlFetchArray($ppres)) {
    echo "<option value='" . attr($pprow['ppid']) . "'";
    if ($pprow['ppid'] == $row['lab_id']) echo " selected";
    echo ">" . text($pprow['name']) . "</option>";
  }
?>
   </select>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Order Date','e'); ?>:</b></td>
  <td valign='top'>
<?php
    echo "<input type='text' size='10' name='form_date_ordered' id='form_date_ordered'" .
      " value='" . $row['date_ordered'] . "'" .
      " title='" . xl('Date of this order') . "'" .
      " onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'" .
      " />" .
      "<img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'" .
      " id='img_date_ordered' border='0' alt='[?]' style='cursor:pointer'" .
      " title='" . xl('Click here to choose a date') . "' />";
?>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Internal Time Collected','e'); ?>:</b></td>
  <td valign='top'>
<?php
    echo "<input type='text' size='16' name='form_date_collected' id='form_date_collected'" .
      " value='" . substr($row['date_collected'], 0, 16) . "'" .
      " title='" . xl('Date and time that the sample was collected') . "'" .
      // " onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'" .
      " />" .
      "<img src='$rootdir/pic/show_calendar.gif' align='absbottom' width='24' height='22'" .
      " id='img_date_collected' border='0' alt='[?]' style='cursor:pointer'" .
      " title='" . xl('Click here to choose a date and time') . "' />";
?>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Priority','e'); ?>:</b></td>
  <td valign='top'>
<?php
generate_form_field(array('data_type'=>1,'field_id'=>'order_priority',
  'list_id'=>'ord_priority'), $row['order_priority']);
?>
  </td>
 </tr>

 <tr  <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Status','e'); ?>:</b></td>
  <td valign='top'>
<?php
if($row['order_status']!=null)
{
$status=$row['order_status'];
}else
{
	$status="pending";
}
generate_form_field(array('data_type'=>1,'field_id'=>'order_status',
  'list_id'=>'ord_status'), $status);
?>
  </td>
 </tr>

 <tr <?php  if($newcrop_user_role['newcrop_user_role']=='erxdoctor') { ?> style="visibility:hidden;position:absolute" <?php } ?>>
  <td width='1%' valign='top' nowrap><b><?php xl('Clinical History','e'); ?>:</b></td>
  <td valign='top'>
   <input type='text' maxlength='255' name='form_clinical_hx' style='width:100%'
    class='inputtext' value='<?php echo attr($row['clinical_hx']); ?>' />
  </td>
 </tr>

 <!-- Will enable this later, nothing uses it yet. -->
 <tr style='display:none'>
  <td width='1%' valign='top' nowrap><b><?php xl('Patient Instructions','e'); ?>:</b></td>
  <td valign='top'>
   <textarea rows='3' cols='40' name='form_patient_instructions' style='width:100%'
    wrap='virtual' class='inputtext' /><?php echo $row['patient_instructions'] ?></textarea>
  </td>
 </tr>
<tr>

<td>
			<div >
				<div class="control-group">
					<label for="select-tools">Select Investigations:</label>
					<select id="select-tools" multiple="multiple"  name="form_proc_type[]" placeholder="Search here..."></select>
				</div>
				<?php $sqry = sqlStatement("SELECT procedure_type_id, procedure_code, name FROM procedure_type WHERE procedure_type LIKE 'ord' AND activity = 1 ORDER BY seq, procedure_code LIMIT 280"); 

					?>
				
<script>
				// <select id="select-tools"></select>

				$('#select-tools').selectize({
					maxItems: null,
					valueField: 'id',
					labelField: 'title',
					searchField: 'title',
					options: [
					<?php  while ($jarray = sqlFetchArray($sqry)) { ?>
						{id: '<?php echo $jarray['procedure_type_id'] ?>', title: '<?php echo $jarray['name'] ?>'},
					<?php } ?>
					],
					create: false
				});
				</script>
				<label>Frequently ordered tests</label>
			<?php $forderqry = "SELECT a.*,b.* FROM
(select a.provider_id,a.code_Text,count(a.code_text) No_of_tests,a.code
from billing a, codes c,users u
where a.code_type='Lab Test' and a.code_text=c.code and a.provider_id=u.id
and a.provider_id=? 
group by a.code_text
order by No_of_tests desc)a
LEFT JOIN

(
SELECT poc.procedure_name,poc.procedure_order_id,pt.procedure_code,pt.name,pt.procedure_type_id
FROM procedure_order_code poc, procedure_type pt, procedure_order po
WHERE  pt.procedure_code=poc.procedure_code and po.procedure_order_id=poc.procedure_order_id
AND po.provider_id=?
)b ON a.code=b.procedure_name 
group by a.code_text
order by a.no_of_tests desc";
 $newcrop_user_id=sqlQuery("select * from users where username='".$_SESSION['authUser']."'");

$fqry = sqlStatement($forderqry,array($newcrop_user_id['id'],$newcrop_user_id['id']));

while($forders = sqlFetchArray($fqry)) {
	
?>
<div class="faq" style="margin-bottom: 10px;">
<input type="checkbox" value="<?php echo $forders['procedure_type_id'] ?>" name="form_proc_type[]" style="    display: inline-block;
    width: 75%;
    float: right;"><?php echo $forders['code_Text']; ?>
</div>
<?php } ?>
			</div>
			
			</td>
</tr>
<?php

  // This section merits some explanation. :)
  //
  // If any procedures have already been saved for this form, then a top-level table row is
  // created for each of them, and includes the relevant questions and any existing answers.
  // Otherwise a single empty table row is created for entering the first or only procedure.
  //
  // If a new procedure is selected or changed, the questions for it are (re)generated from
  // the dialog window from which the procedure is selected, via JavaScript.  The sel_proc_type
  // function and the types.php script that it invokes collaborate to support this feature.
  //
  // The generate_qoe_html function in qoe.inc.php contains logic to generate the HTML for
  // the questions, and can be invoked either from this script or from types.php.
  //
  // The $i counter that you see below is to resolve the need for unique names for form fields
  // that may occur for each of the multiple procedure requests within the same order.
  // procedure_order_seq serves a similar need for uniqueness at the database level.

  $oparr = array();
  if ($formid) {
    $opres = sqlStatement("SELECT " .
      "pc.procedure_order_seq, pc.procedure_code, pc.procedure_name, " .
      "pc.diagnoses, pt.procedure_type_id " .
      "FROM procedure_order_code AS pc " .
      "LEFT JOIN procedure_type AS pt ON pt.lab_id = ? AND " .
      "pt.procedure_code = pc.procedure_code " .
      "WHERE pc.procedure_order_id = ? " .
      "ORDER BY pc.procedure_order_seq",
      array($row['lab_id'], $formid));
    while ($oprow = sqlFetchArray($opres)) {
      $oparr[] = $oprow;
    }
  }
  if (empty($oparr)) $oparr[] = array('procedure_name' => '');

  $i = 0;
  foreach ($oparr as $oprow) {
    $ptid = -1; // -1 means no procedure is selected yet
    if (!empty($oprow['procedure_type_id'])) {
      $ptid = $oprow['procedure_type_id'];
    }

?>
 <!--<tr>
  <td width='1%' valign='top'><b><?php echo xl('Procedure') . ' ' . ($i + 1); ?>:</b></td>
  <td valign='top'>
   <input type='text' size='50' name='form_proc_type_desc[<?php echo $i; ?>]'
    value='<?php echo attr($oprow['procedure_name']) ?>'
    onclick="sel_proc_type(<?php echo $i; ?>)"
    onfocus='this.blur()'
    title='<?php xla('Click to select the desired procedure','e'); ?>'
    style='width:100%;cursor:pointer;cursor:hand' readonly />
   <input type='hidden' name='form_proc_type[<?php echo $i; ?>]' value='<?php echo $ptid ?>' />
   <br /><?php echo xlt('Diagnosis Codes'); ?>:
   <input type='text' size='50' name='form_proc_type_diag[<?php echo $i; ?>]'
    value='<?php echo attr($oprow['diagnoses']) ?>' onclick='sel_related(this.name)'
    title='<?php echo xla('Click to add a diagnosis'); ?>'
    onfocus='this.blur()'
    style='cursor:pointer;cursor:hand' readonly />
   <div style='width:95%;' id='qoetable[<?php echo $i; ?>]'>
<?php
$qoe_init_javascript = '';
echo generate_qoe_html($ptid, $formid, $oprow['procedure_order_seq'], $i);
if ($qoe_init_javascript)
  echo "<script language='JavaScript'>$qoe_init_javascript</script>";
?>
   </div>
  </td>
 </tr>-->
<?php
    ++$i;
  }
?>

</table>

<p>
<!-- <input type='button' value='<?php echo xla('Add Procedure'); ?>' onclick="addProcLine()" /> -->
&nbsp;
<input type='submit' name='bn_save' value='<?php echo xla('Save'); ?>' onclick='transmitting = false;' />
&nbsp;
<?php 
$pid=$_SESSION['pid'];
?>
<!--<input type='submit' name='bn_xmit' value='<?php echo xla('Save and Transmit'); ?>' onclick='transmitting = true;' />-->
&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick="top.restoreSession();location='<?php echo $GLOBALS['form_next_url']; ?>'" />
</p>

</div>
<div class="col-md-4">
<?php
$provider_id =$_SESSION['authUserID'];   $last_lab_coder = sqlStatement("select * from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and 
a.encounter_id=c.encounter and a.patient_id =$pid and a.provider_id = $provider_id 
group by encounter_id
order by encounter_id desc
limit 1"); $check_enc = sqlFetchArray($last_lab_coder); if($check_enc['encounter_id'] == $_SESSION['encounter'] ) { ?>

<label> Currently ordered:</label>  <?php echo date("F jS, Y g:s a", strtotime($check_enc['date_collected'])); ?>
<ul>
<?php 
$lab_coder = sqlStatement("select * from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and  a.encounter_id=c.encounter and  a.provider_id = $provider_id and c.encounter=".$_SESSION['encounter']);
while($lab_code = sqlFetchArray($lab_coder)) {
?>

<li> <?php echo $lab_code['procedure_name']; ?> </li>
<?php }

?>

</ul><br>
<?php $last_lab_coder = sqlStatement("select * from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and 
a.encounter_id=c.encounter and a.patient_id =$pid and a.provider_id = $provider_id
group by encounter_id
order by encounter_id desc
limit 1,1");
$last_lab_date = sqlStatement("select a.date_collected from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and 
a.encounter_id=c.encounter and a.patient_id =$pid and a.provider_id = $provider_id
group by encounter_id
order by encounter_id desc
limit 1,1"); $date = sqlFetchArray($last_lab_date);
 if(!empty($date)) {
?>
 <label> Last Visit orders: </label>  <?php  echo date("F jS, Y g:s a", strtotime($date['date_collected'])); } ?>
<ul>
<?php
while($last_lab_code = sqlFetchArray($last_lab_coder)) {

?>
<li> <?php echo $last_lab_code['procedure_name']; if($last_lab_code['order_status']='final') { ?> <a href="../../patient_file/summary/labdata_review.php?set_pid=<?php echo $pid ?>&encounter=<?php echo $_SESSION['encounter']?>&orderid=<?php echo $last_lab_code['procedure_order_id'] ?>" title="view results" class='iframe'><i class="fa fa-eye"></i></a> <?php } ?></li>
<script type="text/javascript">
  $(".iframe").fancybox( {
  'left':10,
	'overlayOpacity' : 0.0,
	'showCloseButton' : true,
	'frameHeight' : 550,
	'frameWidth' : 850
  });
</script>
<?php } ?>
<?php } else { ?>
<?php $last_lab_coder = sqlStatement("select * from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and 
a.encounter_id=c.encounter and a.patient_id =$pid and a.provider_id = $provider_id
group by encounter_id
order by encounter_id desc
limit 1"); $last_lab_date = sqlStatement("select a.date_collected from procedure_order a, procedure_order_code b, form_encounter c
where a.procedure_order_id=b.procedure_order_id and 
a.encounter_id=c.encounter and a.patient_id =$pid and a.provider_id = $provider_id
group by encounter_id
order by encounter_id desc
limit 1"); 
	$date_ordered = sqlFetchArray($last_lab_date);  if(!empty($date_ordered)) { ?>
	<label> Last Visit orders: </label>  <?php echo date("F jS, Y g:s a", strtotime($date_ordered['date_collected'])); } ?>
<ul>
<?php
while($last_lab_code = sqlFetchArray($last_lab_coder)) {
?>
<li> <?php echo $last_lab_code['procedure_name']; ?> <a href="../../patient_file/summary/labdata.php?set_pid=<?php echo $pid ?>&encounter=<?php echo $last_lab_code['encounter']?>&orderid=<?php echo $last_lab_code['procedure_order_id'] ?>&view_report=1" title="view results" class='iframe'><i class="fa fa-eye"></i></a> <?php } ?></li>
<script type="text/javascript">
  $(".iframe").fancybox( {
  'left':10,
	'overlayOpacity' : 0.0,
	'showCloseButton' : true,
	'frameHeight' : 550,
	'frameWidth' : 850
  });
</script>

</ul>
<?php } ?>
</div></div>

<script language='JavaScript'>
Calendar.setup({inputField:'form_date_ordered', ifFormat:'%Y-%m-%d',
 button:'img_date_ordered'});
Calendar.setup({inputField:'form_date_collected', ifFormat:'%Y-%m-%d %H:%M',
 button:'img_date_collected', showsTime:true});
</script>

</form>
</body>
</html>

