<?php
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
<p class="pull-right"><b>Date:</b>&nbsp;</th><td><?php echo $strip ?></p>
</div>
<div style="text-align: center">
<p class="doc-head"><?php echo $doctor ?>, MBBS;DPM;MD; FRCPsych</p>
<p>Registration No: 13954 (T C Medical Council)</p>
</div>
<div class="row pdata">
<p style='display: inline;'>Patient Full Name: <?php echo $pfname; ?>&nbsp<?php echo $plname ?>&nbsp<?php echo $pmname ?></p><p class="pull-right">Gender: <?php echo $pgender ?></p>
</div>
<div class="row pdata">
<p style='display: inline;'>Patient’s Address and Phone number: <?php echo $pstreet ?>, <?php echo $pmob ?></p><p class="pull-right">Age: <?php echo $page ?> Years</p>
</div>
</div>
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
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>

</div>
<div class="table-title">
<h2>Notes –</h2>
<blockquote>
Patient has fever and complaints on pain in joints.
</blockquote>
</div>
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
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>

</div>
<div class="table-title">
<h2>Lab Investigations</h2>

<table class="table table-striped table-inverse">
  <thead class="thead-inverse">
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Test Name</th>
      <th>Current status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>

</div>
<div class="table-title">
<h2>Prescription</h2>

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
<td class="text-left"><?php echo $pres['drug']; ?>&nbsp;<sub>(<?php echo $drug_form ?>)</sub> <?php echo $qtyz ?> mg</td>
<td class="text-left"><?php echo $pres['drug_intervals']?> (<?php echo $pres['drug_meal_time'] ?>) for <?php echo $pres['duration']?> Weeks</td>
</tr>
<?php
		  }
		  ?>
</tbody>
</table>
<div class="table-title">
<h2>Plan –</h2>
<blockquote>
   Your plan for the patient based on the problems you’ve identified
Develop a diagno
stic and treatment plan for each differential diagnosis.
Your diagnostic plan may include tests, procedures, other laboratory studies, 
consultations, etc.
Your treatment plan should include: patient education, pharmacotherapy if any, 
other therapeutic proc
edures. You must also address plans for follow
-up (next 
scheduled visit, etc.).
Also see your Bates Guide to Physical Examination for excellent examples of 
complete H & P and SOAP note formats
</blockquote>
</div>
<div class="table-title">
<h2>Review After</h2>
Patient is advised to visit after 20 Days
</div>
  </div>
  </div>
</div>
	</div>
</div>
  </div>
  </body>
</html>
