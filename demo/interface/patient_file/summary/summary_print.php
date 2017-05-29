<?php
require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
$result_patient = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
$result_visit="SELECT * from form_encounter where pid=? and encounter=?";
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
?> 
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
  <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  	<link rel="stylesheet" href="style.css"  />
		<link rel="stylesheet" href="../../../library/css/mycss.css"  />
	
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<script type="text/javascript" src="../../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<div class="container-fluid no-margin">
	<div class="row">
		<div class="col-md-12">
				<div class="body">
<div class="table-title">
<div class="row auo-mar">
<p style="display:inline"><b>Serial No:</b>&nbsp;</th><td><?php echo $pserial ?></p>
<p class="pull-right"><b>Date:</b>&nbsp;</th><td><?php echo date("d-M-y",strtotime($result_visit2['date'])) ?></p>
</div>
<!--<div style="text-align: center">
<p class="doc-head"><?php //echo $doctor ?>, MBBS;DPM;MD; FRCPsych</p>
<p>Registration No: 13954 (T C Medical Council)</p>
</div>-->
<div class="row pdata">
<p style='display: inline;'>Patient Full Name: <?php echo $pfname; ?>&nbsp<?php echo $plname ?>&nbsp<?php echo $pmname ?></p><p class="pull-right">Gender: <?php echo $pgender ?></p>
</div>
<div class="row pdata">
<p style='display: inline;'>Patient’s Address and Phone number: <?php echo $pstreet ?>, <?php echo $pmob ?></p><p class="pull-right">Age: <?php echo $page ?> Years</p>
</div>
</div>
<?php $qry2 = "SELECT * FROM lists WHERE pid = ? AND encounter = ?";
   $issues = sqlStatement($qry2, array($pid,$encounter));
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
<div class="table-title">
<?php $qry2 = "SELECT reason FROM form_encounter WHERE pid = ? AND encounter = ?";
   $notes= sqlStatement($qry2, array($pid,$encounter));
   $note=sqlFetchArray($notes);
   if($note!=null){
   ?>
   
<h2>Notes –</h2>
<blockquote>
<?php echo $note['reason']?>
</blockquote>
</div>
   <?php }?>
  <?php $qry2 = "SELECT * FROM  form_ros WHERE pid = ? AND encounter = ?";
   $ros = sqlStatement($qry2, array($pid,$encounter));
$ros1=sqlFetchArray($ros);
if($ros1!=null){   ?>
<div class="table-title">
<h2>Review of systems</h2>

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
	
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($ros['date'])) ?></td>
<td class="text-left"><?php echo $ros['type']?> </td>
<td class="text-left"><?php echo $ros['title']?> </td>
</tr>
<?php $i++;} ?>
</tbody>
</table>

</div>
<?php }?>
 <?php $qry2 = "SELECT * FROM  procedure_order a,procedure_order_code b WHERE a.procedure_order_id=b.procedure_order_id and a.patient_id = ? AND a.encounter_id = ?";
   $lab = sqlStatement($qry2, array($pid,$encounter)); 
   $lab2=sqlFetchArray($lab);
   if($lab2!=null){
   ?>
<div class="table-title">
<h2>Lab Investigations</h2>

<table class="table table-striped table-inverse">
  <thead class="thead-inverse">
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Test Name</th>
    </tr>
  </thead>
		 
<tbody class="table-hover">
<?php 
$i=1;
foreach($lab as $lab1) {
	
	?>
<tr>
<td class="text-left"><?php echo $i ?></td>
<td class="text-left"><?php echo date("d-M-Y",strtotime($lab1['date_ordered'])) ?></td>
<td class="text-left"><?php echo $lab1['procedure_name']?> </td>
</tr>
<?php $i++;} ?>
</tbody>
</table>

</div>
<?php }?>
<?php $qry2 = "SELECT *
FROM prescriptions
WHERE patient_id = ?
AND encounter = ?";
          $prescription = sqlStatement($qry2, array($pid,$encounter));
           $pres=sqlFetchArray($prescription);
          if($pres!=null){		   
  ?>
<div class="table-title">
<h2>Prescription</h2>

<table class="table-fill">
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
			$qtyz = str_replace(".00", "", (string)number_format ($pres['dosage'], 2, ".", "")); ?>
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
  </div>
  </div>
</div>
	</div>
</div>
  </div>
  </body>
</html>
