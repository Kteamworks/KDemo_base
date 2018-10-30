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


$obs_data = sqlQuery("select * from gyanic_obstetric_history where  pid= '$pid'");



/*
GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
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
*/
if(isset($_POST['submit'])){
     $gravidity	=  $_POST['gravidity'];
     $parity    =  $_POST['parity'];
	 $lmp       =  $_POST['lmp'];
	 $edd       =  $_POST['edd'];
	 
    //$enc = $obs_data['encounter'];  
	 
	 if(!$obs_data){
	  
	$obs_history = sqlStatement("insert into gyanic_obstetric_history(gravidity,parity,lmp,edd,encounter,pid)values('$gravidity','$parity','$lmp','$edd','$encounter','$pid')");
	 }
	 else{
	      
		 $obs_history = sqlStatement("update gyanic_obstetric_history set gravidity='$gravidity', parity='$parity', lmp='$lmp',edd='$edd' where pid='$pid'");
	 }
	 
	 
	header('location:prev_preg.php');
	 
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
  <title>Medsmart</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
	<!--<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo $GLOBALS['webroot']; ?>/library/breadcrumbs/css/style.css"> <!-- Resource style -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  
  
 
  
</head>


<script>
$(document).ready(function(){
	$(document).on('change', '#lmp', function() {
		var date = $("#lmp").val();
		//alert(name);
		
		
	});
	
});




$(document).ready(function () {
    $('#lmp').datepicker();
    $('#edd').datepicker();
});

function getdate() {
    var tt = document.getElementById('lmp').value;

    //var date = new Date(tt);
    var newdate = new Date(tt);
	
	newdate.setDate(newdate.getDate() + 280);
	newdate.setMonth(newdate.getMonth()+1);
    
    
	var dd =(newdate.getDate() < 10 ? '0' : '') + newdate.getDate();
	var mm =(newdate.getMonth() < 10 ? '0' : '') + newdate.getMonth();
	var y = newdate.getFullYear();
    var someFormattedDate = y + '-' + mm + '-' + dd;
	
    document.getElementById('edd').value = someFormattedDate;
	
}

</script>


<body>

<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
		
			<!--<li><a href="../summary/stats_full.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			
			
			
			<?php //if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			
			<?php// } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php// }  ?>-->
			
			<li  class="current"></i><em>Obstetric History</em></li>
			<li><a href="prev_preg.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Past History</a></li>
			<li><a href="family_history.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Family History</a></li>
			
			<li><a href="obstetric_examination.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Obstetric Examination</a></li>
			
			
			<!--<?php



  			if($rid2 == null) { ?>
			<li><a href="../../patient_file/encounter/load_form.php?formname=ros"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
						<?php } else { ?>
			<li><a href="../../patient_file/encounter/view_form.php?formname=ros&id=<?php echo $rid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Review of systems</a></li>
			<?php } ?>
			<li><a href="../encounter/view_form.php?formname=newpatient&id=<?php echo $nvid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Visit Notes</a></li>-->
			
			<li><a href="../encounter/load_form.php?formname=procedure_order"><i class="fa fa-note" style="margin-right: 8px;"></i>Lab Tests</a></li>
			<li><a href="../../../controller.php?prescription&edit&id=&pid=<?php echo $pid ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Prescription</a></li>
						<!--<?php if($plid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=dictation"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } else { ?>
									<li><a href="../encounter/view_form.php?formname=dictation&id=<?php echo $plid2 ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Plan</a></li>
									<?php } ?>
			<li><a href="../transaction/add_transaction.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Referral</a></li>-->
			<li><a href="../../patient_file/encounter/admit_doctor_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Admission</a></li>
			<li><a href="../summary/summary_print.php">Summary</a></li>
		</ol>
	</nav>
</section>




<div class="container col-md-offset-3 col-md-4">
  <h3>OBSTETRIC HISTORY</h3>
  <br>
  <form action="" method='POST'>
    <div class="form-group">
      <label for="gravidity">Gravidity</label>
	  <input type='number' class='form-control' name='gravidity' value='<?php echo $obs_data['gravidity']; ?>' min='0'>
      
    </div>
    <div class="form-group">
      <label for="parity">Parity</label>
    <input type='number' class='form-control' name='parity' value='<?php echo $obs_data['parity'];  ?>' min='0'>
    </div>
	 <div class="form-group">
      <label for="LMP">LMP</label>
      <input type="date" class="form-control" name="lmp" id='lmp' value='<?php echo $obs_data['lmp'];  ?>' max='<?php echo date('Y-m-d') ?>'>
    </div>
	
	<div class="form-group">
      <label for="LMP"> Date Calculator</label><br>
      <input type="button" onclick="getdate()" class="btn btn-default col-md-12" value="Click Here to Generate Expected Delivery Date" />
    </div>
	 
	 <div class="form-group">
      <label for="EDD">EDD</label>
      <input type="date" class="form-control"  name="edd" id='edd' value='<?php echo $obs_data['edd'];  ?>' readonly>
    </div>
	
	
   
    <button type="submit" name='submit' class="btn btn-default">Submit</button>
  </form>
</div>

</body>
</html>
