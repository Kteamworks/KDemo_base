<?php
include_once('function.php');
require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
$result_patient = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
$pserial = $result_patient['genericname1'];
$pfname = $result_patient['fname'];
$plname = $result_patient['lname'];
$pmname = $result_patient['mname'];
$page = $result_patient['age'];
$pmob = $result_patient['phone_cell'];
$plocality = $result_patient['locality'];
$pcity = $result_patient['city'];
$pstate = $result_patient['state'];
if($result_patient['sex'] == 1) { 
$pgender = 'Male';
}
elseif($result_patient['sex'] == 2) { 
$pgender = 'Female';
}
else {
	$pgender = 'Unknown';
}
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
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<script type="text/javascript" src="../../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
				<div class="body">
<div class="table-title">
<div class="row auo-mar">
<p style="display:inline"><b>Serial No:</b>&nbsp;</th><td><?php$pserial?></p>
<p class="pull-right"><b>Date:</b>&nbsp;</th><td><?php$strip?></p>
</div>
<div style="text-align: center">
<p class="doc-head"><?php $doctor ?>, MBBS;DPM;MD; FRCPsych</p>
<p>Registration No: 13954 (T C Medical Council)</p>
</div>
<div class="row pdata">
<p>Patient Full Name: <?php$pfname?>&nbsp<?php$plname?>&nbsp<?php$pmname?></p><p class="pull-right">Gender: <?php$pgender?></p>
</div>
<div class="row pdata">
<p>Patient’s Address and Phone number: <?php$pstreet?>, <?php$pmob?></p><p class="pull-right">Age: <?php$page?> Years</p>
</div>
</div>
<table class="table-fill">
<thead>
<tr>
<th class="text-left">Drug</th>
<th class="text-left">Prescription</th>
</tr>
</thead>
<?php $qry2 = "SELECT *
FROM prescriptions
WHERE patient_id = ?
AND encounter = ?";
          $prescription = sqlStatement($qry2, array($pid,$encounter)); ?>
		 
<tbody class="table-hover">
 		<?php  foreach($prescription as $pres) {
			  
		 if($pres['form'] == 1) { $drug_form = 'TAB'; }
			else if($pres['form'] == 2) { $drug_form = 'SYR'; }
			else if($pres['form'] == 3) { $drug_form = 'INJ'; }
			$qtyz = str_replace(".00", "", (string)number_format ($pres['dosage'], 2, ".", "")); ?>
<tr>
<td class="text-left"><?php$pres['drug']?>&nbsp;<sub>(<?php $drug_form?>)</sub> <?php$qtyz?> mg</td>
<td class="text-left"><?php$pres['drug_intervals']?> (<?php $pres['drug_meal_time'] ?>) for <?php$pres['duration']?> Weeks</td>
</tr>
<?php
		  }
		  ?>
</tbody>
</table>
  
</div>
	</div>
</div>
  </div>
  </body>
</html>
