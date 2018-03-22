<?php
require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/pnotes.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/lists.inc");
//require_once("$srcdir/report.inc");
$result_patient = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
$visit_data = getAdmitData($pid, "*");
$result_visit="SELECT encounter from form_encounter where pid=? order by encounter desc limit 1,1 ";
$result_visit1 = sqlStatement($result_visit, array($pid,$encounter)); 
$result_visit2=sqlFetchArray($result_visit1);
$pserial = $result_patient['genericname1'];
$pfname = $result_patient['fname'];
$plname = $result_patient['lname'];
$pmname = $result_patient['mname'];
$page = $result_patient['age'];
$pmob = $result_patient['phone_cell'];
$plocality = $result_patient['locality'];
$pcity = $result_patient['city'];
$pstate = $result_patient['state'];
$pgender=$result_patient['sex'];
$pstreet = $result_patient['street'];
$visit_encounter=$visit_data['encounter'];
$visit_ward=$visit_data['admit_to_ward'];
$visit_bed=$visit_data['admit_to_bed'];
$admit_date=$visit_data['admit_date'];
$discharge_date=$visit_data['discharge_date'];
$ip_note=$visit_data['ip_note'];
?> 
<html>

<head>
<?php html_header_show();?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
	<script src="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/js/modernizr.js"></script> <!-- Modernizr -->
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
  <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  	<link rel="stylesheet" href="style.css"  />
		<link rel="stylesheet" href="../../../library/css/mycss.css"  />
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
	.label {
	color: black;
	}
	</style>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<script type="text/javascript" src="../../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>

<div class="container-fluid no-margin" id="print-page">

				<div class="body">
<div class="container">
<div class="row auo-mar">
<img WIDTH='500pt' src='../../pic/city_hospital_logo.png' style="display:block"/>
<br/>
	<h1 style="text-align:center">Discharge Summary</h1>

<div class="col-md-4">
<p><label>Name</label>  &nbsp;&nbsp; :   <?php echo $pfname; ?>&nbsp<?php echo $plname ?>&nbsp<?php echo $pmname ?></p>
<p><label>Age</label>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :  <?php echo $page ?> Years</p>
<p><label>Gender</label>    : <?php echo $pgender ?></p>
</div>
	<div class="col-md-4">
<p><label>Hospital ID</label>&nbsp; : <?php echo $pserial ?></p>
<p><label>OP No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : <?php echo $result_visit2['encounter'] ?></p>
<p><label>IP No</label>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : <?php echo $visit_encounter ?></p>
</div>
<?php if($admit_date!=null){?>
<div class="col-md-4">
<p><label>Ward</label>   :  <?php echo $visit_ward ?> <?php echo $visit_bed ?></p>
<p><label>DOA</label>  &nbsp;  :  <?php echo date('Y-m-d H:i:s',strtotime($admit_date)) ?></p>
<p><label>DOD</label>  &nbsp;  :  <?php echo date('Y-m-d H:i:s',strtotime($discharge_date)) ?> </p>
</div>
<?php }?>
</div>

<h1 style="text-align:center">DEPARTMENT OF MEDICINE</h1>
<div class="table-title">
<?php $qry2 = "SELECT title FROM lists WHERE pid = ? AND encounter = ?";
   $notes= sqlStatement($qry2, array($pid,$encounter));
   $note=sqlFetchArray($notes);
   if($note!=null){
   ?>
   
<h3>DIAGNOSIS </h3>
<blockquote>
<?php echo $note['title']?>
</blockquote>
</div>
   <?php }?>
   <div class="table-title">
<?php $reason_qry2 = "SELECT reason FROM form_encounter WHERE pid = ? AND encounter = ?";
   $reason_notes= sqlStatement($reason_qry2, array($pid,$encounter));
   $reason_note=sqlFetchArray($reason_notes);

   if($reason_note!=null){
   ?>
   
<h3>CHIEF COMPLAINTS </h3>
<blockquote>
<?php echo $reason_note['reason']?>
</blockquote>
</div>
   <?php }?>
   <!--  <div class="table-title">
<?php $qry2 = "SELECT reason FROM form_encounter WHERE pid = ? AND encounter = ?";
   $notes= sqlStatement($qry2, array($pid,$encounter));
   $note=sqlFetchArray($notes);
   if($note['reason']!=null){
   ?>
   
<h2>HISTORY OF PRESENTING ILLNESS –</h2>
<blockquote>
<?php echo $note['reason']?>
</blockquote>
</div>
   <?php }?>
   -->
   <?php
   
  echo "<hr />";
            echo "<div class='text history' id='HIS'>\n";
            
                print "<h2>".xl('History Data').":</h2>";
                $result1 = getHistoryData($pid);
                echo "   <table>\n";
                display_layout_rows('HIS', $result1);
                echo "   </table>\n";
            
            echo "</div>";
   ?>
<?php $pi_qry2 = "SELECT * FROM lists WHERE pid = ? AND encounter != ?";
   $issues = sqlStatement($pi_qry2, array($pid,$encounter));
$issues1=sqlFetchArray($issues);
   
   $i=1;
   if($issues1!=null){?>
<div class="table-title">
<h2>Patient History</h2>

<table class="table table-striped table-inverse">
  <thead class="thead-inverse">
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Type</th>
      <th>Description</th>
    </tr>
  </thead>
		 
<tbody class="table-hover">
<?php foreach($issues as $issue) {?>
<tr>
<td class="text-left"><?php echo $i?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($issue['date'])) ?></td>
<td class="text-left"><?php echo $issue['type']?> </td>
<td class="text-left"><?php echo $issue['title']?> </td>
</tr>
<?php $i++;
}?>
</tbody>
</table>

</div>
   <?php }?>

  <?php $ros_qry2 = "SELECT * FROM  form_ros WHERE pid = ? AND encounter = ?";
   $ros = sqlStatement($ros_qry2, array($pid,$encounter));
$ros1=sqlFetchArray($ros);
if($ros1!=null){   ?>
<div class="table-title">
<h2>GENERAL PHYSICAL EXAMINATION</h2>

<table class="table table-striped table-inverse">
  <thead class="thead-inverse">
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Type</th>
      <th>Current status</th>
    </tr>
  </thead>
		 
<tbody class="table-hover">
<?php 
$i=1;
foreach($ros as $ros1) {
	if($ros1['weight_change']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo WeightChange?> </td>
<td class="text-left"><?php echo YES?> </td>
</tr>
	<?php $i++; }?>
	<?php
	if($ros1['weakness']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Weakness?> </td>
<td class="text-left"><?php echo YES?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['fatigue']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Fatigue?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['anorexia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Anorexia?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['fever']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Fever?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++; }?>
	<?php
	if($ros1['chills']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Chills?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++; }?>
	<?php
	if($ros1['night_sweats']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo NightSweats?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['insomnia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Insomnia ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['irritability']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo Irritability ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['heat_or_cold']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Heat Or Cold') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['intolerance']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Intolerance') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['change_in_vision']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Change in Vision') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['glaucoma_history']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Glaucoma History') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['eye_pain']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Eye Pain') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['irritation']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Irritation') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['redness']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Redness') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['excessive_tearing']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Excessive Tearing') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['double_vision']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Double Vision') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['blind_spots']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Blind Spots') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['photophobia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Photophobia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hearing_loss']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hearing Loss') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['discharge']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Discharge') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['pain']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Pain') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['blind_spots']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Blind Spots') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['vertigo']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Vertigo') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['tinnitus']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Tinnitus') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['frequent_colds']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Frequent Colds') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['sore_throat']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Sore Throat') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['sinus_problems']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Sinus Problems') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['post_nasal_drip']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Post Nasal Drip') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['nosebleed']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Nose Bleed') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['snoring']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Snoring') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['apnea']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Apnea') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['breast_discharge']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Breast Discharge') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['breast_mass']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Breast Mass') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['biopsy']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Biopsy') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['abnormal_mammogram']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Abnormal Mammogram') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['cough']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Cough') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['nosebleed']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Nose Bleed') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['sputum']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Sputum') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['shortness_of_breath']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Shortness of Breath') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['wheezing']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Wheezing') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['hemoptsyis']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hemoptsyis') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['asthma']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Asthma') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['copd']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Copd') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['chest_pain']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Chest Pain') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['syncope']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Syncope') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['palpitation']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Palpitation') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['pnd']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('PND') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['doe']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('DOE') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['orthopnea']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Orthopnea') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?><?php
	if($ros1['peripheal']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Peripheal') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	
	<?php
	if($ros1['edema']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Edema') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['legpain_cramping']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Leg Pain Cramping') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['history_murmur']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('History Murmur') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['arrythmia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Arrythmia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['heart_problem']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Heart Problem') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['dysphagia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Dysphagia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['heartburn']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Heart Burn') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['bloating']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Bloating') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['belching']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Belching') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['flatulence']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Flatulence') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['nausea']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Nausea') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['vomiting']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Vomiting') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hematemesis']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hematemesis') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['gastro_pain']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Gastro Pain') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['food_intolerance']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Food Intolerance') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hepatitis']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hepatitis') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['jaundice']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Jaundice') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hematochezia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hematochezia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['changed_bowel']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Changed Bowel') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['diarrhea']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Diarrhea') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['constipation']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Constipation') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['polyuria']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Polyuria') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['polydypsia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Polydypsia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['dysuria']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Dysuria') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hematuria']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hematuria') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['frequency']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Frequency') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['urgency']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Urgency') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['incontinence']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Incontinence') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['renal_stones']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Renal Stones') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['utis']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('UTIS') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['hesitancy']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hesitancy') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['dribbling']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Dribbling') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['stream']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Stream') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['nocturia']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Nocturia') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['erections']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Erections') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['ejaculations']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Ejaculations') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['mearche']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Mearche') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['menopause']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Menopause') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['lmp']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('LMP') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['f_frequency']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Frequency') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['f_flow']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Flow') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['f_symptoms']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Symptoms') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['abnormal_hair_growth']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Abnormal Hair Growth') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['f_hirsutism']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Hirsutism') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['joint_pain']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Joint Pain') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['swelling']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Swelling') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['m_redness']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Redness') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	<?php
	if($ros1['m_warm']=="YES"){
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros1['date'])) ?></td>
<td class="text-left"><?php echo xlt('Warm') ?> </td>
<td class="text-left"><?php echo YES ?> </td>
</tr>
	<?php $i++;}?>
	
	
<?php } ?>
</tbody>
</table>

</div>
<?php }?>

<?php $vit_qry2 = "SELECT * FROM  form_vitals WHERE pid = ?";
   $vitals= sqlStatement($vit_qry2, array($pid));
$vitals1=sqlFetchArray($vitals);
if($vitals1!=null){   ?>
<div class="table-title">
<h2>VITALS</h2>
<?php if($vitals1['temperature']!=null){?>
<p><label>Temperature</label>  &nbsp;&nbsp; :   <?php echo $vitals1['temperature']; ?></p>
<?php }?>
<?php if($vitals1['weight']!=null){?>
<p><label>Weight</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :   <?php echo $vitals1['weight']; ?></p>
<?php }?>
<?php if($vitals1['height']!=null){?>
<p><label>Height</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :   <?php echo $vitals1['height']; ?></p>
<?php }?>
<?php if($vitals1['bps']!=null||$vitals1['bpd']!=null){?>
<p><label>BP</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :   <?php echo $vitals1['bps']; ?>/<?php echo $vitals1['bpd']; ?></p>
<?php }?>
<?php }?>
</div>
   <div class="table-title">
<?php $c_qry2 = "SELECT reason FROM form_encounter WHERE pid = ? AND encounter = ?";
   $c_notes= sqlStatement($c_qry2, array($pid,$encounter));
   $c_note=sqlFetchArray($c_notes);
   if($c_note!=null){
   ?>
   
<h2>COURSE OF STAY IN HOSPITAL –</h2>
<blockquote>
<?php echo $ip_note;?>
</blockquote>
</div>
   <?php }?>
<?php $qry2 = "SELECT *
FROM prescriptions
WHERE patient_id = ?
AND encounter = ?";
          $prescription = sqlStatement($qry2, array($pid,$encounter));
           $pres=sqlFetchArray($prescription);
          if($pres!=null){ ?>

		  <div class="table-title">
<h2>ADVISE ON DISCHARGE</h2>

<table class="table table-striped table-inverse">
<thead>
<tr>
<th class="text-left">Drug</th>
<th class="text-left">Prescription</th>
</tr>
</thead>
		 
<tbody class="table-hover">
 		<?php  foreach($prescription as $pres) {
			  
		 if($pres['form'] == 1) { $drug_form = 'TAB'; }
			else if($pres['form'] == 2) { $drug_form = 'SYR'; }
			else if($pres['form'] == 3) { $drug_form = 'INJ'; }
			$qtyz = str_replace(".00", "", (string)number_format ($pres['dosage'], 2, ".", "")); 
			
			?>
      
	  <tr>
<td class="text-left"><?php echo $pres['drug']; ?>&nbsp;<sub>(<?php echo $drug_form ?>)</sub> <?php echo $qtyz ?> mg</td>
<td class="text-left"><?php echo $pres['drug_intervals']?> (<?php echo $pres['drug_meal_time'] ?>) for <?php echo $pres['duration']?> Weeks</td>
</tr>
<?php
		  }
		  ?>
</tbody>
</table>
</div>
		  <?php }?>
		  <?php 
$qry2 = "SELECT *,datediff( form_date_collected,CURDATE()) days
FROM form_dictation
WHERE pid = ?
AND encounter = ?";
 $plan = sqlStatement($qry2, array($pid,$encounter));
 $plan1 = sqlFetchArray($plan);	
if($plan1[dictation]!=null){
 ?>
 
<div class="table-title">
<h2>Plan –</h2>
<blockquote>
  <?php echo $plan1[dictation]; ?>
</blockquote>
</div>
<?php }?>
<?php if($plan1['days']!=null){?>
<div class="table-title">
<h2>Review After</h2>
Patient is advised to visit after <?php echo $plan1['days']?> Days
</div>
<?php } ?>
<?php
 echo ("<div class='table-title signdiv'>\n");
                echo (xl('<B> Signature') . ":________________________________<br><br>");
				$enc=$_SESSION["encounter"];
			    $prow = sqlQuery("SELECT reason,e.provider_id,u.username un,u.qualification ql,u.upin up FROM form_encounter e,users u " .
			     "WHERE encounter = '$enc' and e.provider_id=u.id ");
			   $consbrief=$prow['reason'];
			$doctor=$prow['un'];
			$upin=$prow['up'];
			$qual=$prow['ql'];
			echo $doctor;
			echo ("</br>");
			echo $qual;
			echo ("</br>");
			echo (xl('Regn No. :&nbsp;').$upin);
			echo ("</br>");
            echo (xl('Date') . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;" . date('d/M/y'));
	        echo ("</div>\n");
              
				
				
			echo ("<div class='finalsigndiv table-title'>\n");
			$su=$_SESSION['authUser'];
			$footerow = sqlQuery("select fname,lname from users where username='$su'");
			$printingperson=$footerow['fname']." ".$footerow['lname'];	
            echo (xl('<B> Printed By: ') .$printingperson. "<br><br>");
			
            echo (xl(''). date('F j, Y, g:i a'));
	        echo ("</div>\n");
              	?>

  </div>
  				<div style="margin-left:15px">
					

										<a href="#" class="css_button" onclick="var prtContent = document.getElementById('print-page');
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();">
						<span>
							Print						</span>
					</a>
									</div>
</div>
</div>
	</div>
  </div>
  </body>
</html>
