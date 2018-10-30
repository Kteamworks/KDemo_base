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


$obs_data = sqlQuery("select * from gyanic_obstetric_examination where encounter= '$encounter' and pid= '$pid'");
$examination  = sqlStatement("select * from gyanic_obstetric_examination where  pid= '$pid'");


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
     $doe	       =  $_POST['doe'];
     $complaints   =  $_POST['complaints'];
	 $pallor       =  $_POST['pallor'];
	 //if($pallor =='on'){$pallor = 1;} else { $pallor = 0;}
	 $weight       =  $_POST['weight'];
	 $bp           =  $_POST['bp'];
	 $oedema       =  $_POST['oedema'];
	 //if($oedema =='on'){$oedema = 1;} else { $oedema = 0;}
	 $examination  =  $_POST['examination'];
	 $treat        =  $_POST['treat'];
	 
     $enc = $obs_data['encounter']; 
	 if($encounter != $enc){
	
	$obs_history = sqlStatement("insert into gyanic_obstetric_examination(`date`,`Complaint`,`Pallor`,`Weight`,`Blood Pressure`,`Oedema`,`Examination`,`Treatment`,`encounter`,`pid`)values('$doe','$complaints','$pallor','$weight','$bp','$oedema','$examination','$treat','$encounter','$pid')");
	 }
	 else{
	       $obs_history = sqlStatement("update gyanic_obstetric_examination set `date`= '$doe' ,`Complaint`='$complaints', `Pallor`='$pallor', `Weight`='$weight',`Blood Pressure`='$bp',`Oedema` = '$oedema',`Examination` = '$examination', `Treatment` = '$treat' where `pid`='$pid'and `encounter`='$encounter'");
	 }
	 
	 
	header('location:../encounter/load_form.php?formname=procedure_order');
	
	 
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
  <link rel="stylesheet" href="bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  
  
 
  
</head>


<script>

function printContent(el){
	var restorePage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorePage;
	location.reload(true);
	//location.href = "timeSchduler.php";
	}



$(document).ready(function(){
	$(document).on('change', '#lmp', function() {
		var name = $("#lmp").val();
		 
		
	});
	
});

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
			<li><a href="gyanic_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Obstetric History</a></li>
		    <li><a href="prev_preg.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Past History</a></li>
			<li><a href="family_history.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Family History</a></li>
			<li  class="current"></i><em>Obstetric Examination</em></li>
			
			
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
  <h3>Obstetric Examination <button type="button"  data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-xs">History</button></h3>
  <!------------------------------------------------------------Modal------------------------------------------------------------>
   <div class="modal fade"  id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
	  <div id='div1'>
        <div class="modal-header">
         
          <h4 class="modal-title">History of Previous Visits </h4>
        </div>
        <div  class="modal-body table-responsive">
		
		
		
		<table class="table table-striped  table-condensed table-responsive">
    <thead>
      <tr class='active'>
	    
		<th>Date</th>
		<th>Complaint</th>
        <th>Pallor</th>
        <th>Weight</th>
		<th>B.P.</th>
		<th>Oedema</th>
		<th>Examination</th>
		<th>Treatment </th>
		
		
		
      </tr>
    </thead>
	<tbody>
	
		
         
		 <?php $i=1;
	 While($data1 =sqlFetchArray($examination)) {
	
	 ?>
	 
      <tr>
	  <?php $pallor_data= $data1['Pallor'];
	        if($pallor_data==1) $pallor_data = 'Yes';
			else { $pallor_data = 'No';}
			
			$oedema_data= $data1['Oedema'];
			if($oedema_data==1) $oedema_data = 'Yes';
			else { $oedema_data = 'No';}
			
	        // $tym=date('h:i:s A',strtotime($listResult1['updatedTime'])); 
                 
	  ?>
	  
	  
       
	   <td class="table-active"><?php echo $data1['date']; ?></td>
		<td class="table-active"><?php echo $data1['Complaint']; ?></td>
		<td class="table-active"><?php  ; echo $pallor_data ?></td>
		
		<td class="table-active"><?php echo $data1['Weight'];  ?></td>
		<td class="table-active"><?php echo $data1['Blood Pressure'];  ?></td>
		<td class="table-active"><?php echo $oedema_data;  ?></td>
		<td class="table-active"><?php echo $data1['Examination'];  ?></td>
		<td class="table-active"><?php echo $data1['Treatment'];  ?></td>
		
		
        
	
      </tr>  
	<?php $i++; } ?> 
	</tbody>
	</table>
	
		 
        </div> </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
		  <button type="button" class="btn btn-default" onclick="printContent('div1')">Print</button>
        </div>
      </div>
      
    </div>
  </div> 
  <!---------------------------------------------Model End------------------------------------------------------------------------>
  
  <br>
  <form action="" method='POST'>
    <div class="form-group">
      <label for="date">Date</label>
	  <input type='date' class='form-control' name='doe' value='<?php echo date('Y-m-d');   ?>' readonly>
      </div>
	  
    <div class="form-group">
      <label for="complaints">Complaint</label>
    <textarea class="form-control txt" rows="5" name='complaints'><?php echo $obs_data['Complaint'];  ?></textarea>
    </div>
	 <?php   $check1 = $obs_data['Pallor']; 
	         if($check1 == 1)  $checked1 = 'checked' ;
			 else { $checked2='checked';}
			 
			 $check2 = $obs_data['Oedema'];
			 if($check2 == 1) $checked3 = 'checked';
			 else {$checked4 = 'checked';}
	 
	 ?>
	 <div class="form-group">
      <label for="pallor">Pallor:</label>&nbsp;&nbsp;
      <input type="radio" name='pallor' value ='1' <?php echo $checked1;  ?>> Yes &nbsp;
	  <input type="radio" name='pallor'  value='0' <?php echo $checked2; ?>> No
    </div>
	
	 <div class="form-group">
      <label for="wt">Weight</label>
	  <small> in kg </small>
      <input type="Number" class="form-control"  name="weight" value="<?php echo $obs_data['Weight'];  ?>">
    </div>
	
	
	<div class="form-group">
      <label for="bp">Blood Pressure</label>
      <input type="text" class="form-control"  name="bp" value='<?php echo $obs_data['Blood Pressure'];   ?>' pattern='^\d{1,3}\/\d{1,3}$' placeholder='Format should be like 120/80' >
    </div>
	
	<div class="form-group">
      <label for="oedema">Oedema: &nbsp; &nbsp;</label> 
      <input type="radio" name='oedema' value='1' <?php echo $checked3;  ?>> Yes &nbsp;&nbsp;
	  <input type="radio" name='oedema' value='0' <?php echo $checked4;  ?>> No <br>
    </div>
	
	<div class="form-group">
      <label for="examination">Examination Findings</label>
      <textarea class="form-control txt" rows="5" name='examination'><?php echo $obs_data['Examination'];  ?></textarea>
    </div>
	
	<div class="form-group">
      <label for="treatment">Treatment And Advice</label>
      <textarea class="form-control txt" rows="5" name='treat'><?php echo $obs_data['Treatment'];   ?></textarea>
	
    <button type="submit" name='submit' class="btn btn-default">Submit</button>
  </form>
</div>

</body>
<script>
$(function(){
  $('.txt').keypress(function(e){
    if(e.which == 34 || e.which == 39 || e.which == 94 || e.which == 96 || e.which == 126 || e.which == 60 || e.which == 62 || e.which == 40 || e.which == 41 || e.which == 91 || e.which == 93 || e.which == 123 || e.which == 125 ){
		return false;
    } 
  });
});
</script>


</html>
