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
$past_data = sqlQuery("select * from gyanic_family_history where encounter = '$encounter' and pid= '$pid'");

if(isset($_POST['submit'])){

	
  $cyclic = $_POST['cyclic'];
  if($cyclic=='on') { $cyclic = 1; }  else { $cyclic = 0 ; }
  
  $anaemia = $_POST['anaemia'];
  if($anaemia == 'on') { $anaemia = 1;} else {$anaemia = 0 ; }
  
  $heamophilia = $_POST['heamophilia'];
  if($heamophilia =='on') {$heamophilia = 1;} else {$heamophilia = 0 ; }
  
  $preg_loss_m = $_POST['preg_loss_m'];
  if($preg_loss_m =='on') { $preg_loss_m = 1;} else{ $preg_loss_m = 0;}
  
  
  $preg_loss_s = $_POST['preg_loss_s'];
  if($preg_loss_s== 'on') {$preg_loss_s = 1;} else{$preg_loss_s = 0;}
  
  
  $pre_m = $_POST['pre_m'];
  if($pre_m =='on') { $pre_m = 1;} else { $pre_m = 0; }

 $pre_s = $_POST['pre_s'];
  if($pre_s =='on'){$pre_s = 1;} else { $pre_s = 0;}
  
  $abnormal_m = $_POST['abnormal_m'];
  if($abnormal_m == 'on'){$abnormal_m = 1;} else { $abnormal_m = 0;}
  
  $abnormal_s = $_POST['abnormal_s'];
  if($abnormal_s == 'on'){$abnormal_s = 1;} else { $abnormal_s = 0;}
  
  
  $enc = $past_data['encounter']; 
   if($encounter != $enc){
  
  $past_history = sqlStatement("insert into gyanic_family_history(`Cyclic Fibrosis`,`Sickle Cell Anaemia`,`Heamophilia`	,`preg_loss_mother`	,`preg_loss_sister`,`Pre-eclampsia_mother`,`Pre-eclampsia_sister`,`Abnormal Children_mother`,`Abnormal Children_sister`,`pid`,`encounter`)values('$cyclic','$anaemia','$heamophilia','$preg_loss_m','$preg_loss_s','$pre_m','$pre_s','$abnormal_m','$abnormal_s','$pid','$encounter')");
  
   }
   else {
	   $past_history = sqlStatement("update gyanic_family_history set 
	   `Cyclic Fibrosis` ='$cyclic', 
	   `Sickle Cell Anaemia`= '$anaemia', 
	   `Heamophilia` ='$heamophilia',
	   `preg_loss_mother`='$preg_loss_m',
	   `preg_loss_sister`= '$preg_loss_s', 
	   `Pre-eclampsia_mother` = '$pre_m', 
	   `Pre-eclampsia_sister` = '$pre_s' ,
	   `Abnormal Children_mother` = '$abnormal_m',
	   `Abnormal Children_sister` = '$abnormal_s'
	    where `pid` = '$pid' and `encounter` = '$encounter'  ");
   }
  
  header('location:obstetric_examination.php');
  
  
  
  
  
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
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

 
  
</head>

<body>

<section>
	<nav>
		<ol class="cd-breadcrumb triangle custom-icons">
		
			<!--<li><a href="../summary/stats_full.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Medical Issues</a></li>
			
			
			
			<?php if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			
			<?php } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php } ?>-->
			
			<li><a href="gyanic_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Obstetric History</a></li>
		    <li><a href="prev_preg.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Past History</a></li>
			<li  class="current"></i><em>Family History</em></li>
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
<form action='' method='POST'>
<div class="container-fluid col-md-offset-1 col-md-8">
  <br>
  <?php   $check1 = $past_data['Cyclic Fibrosis']; 
          $check2 = $past_data['Sickle Cell Anaemia']; 	
          $check3 = $past_data['Heamophilia']; 	
          $check4 = $past_data['preg_loss_mother']; 	
          $check5 = $past_data['preg_loss_sister']; 	
          $check6 = $past_data['Pre-eclampsia_mother']; 	
          $check7 = $past_data['Pre-eclampsia_sister']; 	
          $check8 = $past_data['Abnormal Children_mother']; 	
          $check9 = $past_data['Abnormal Children_sister']; 	
           		  
	      
		  
		  if($check1 == 1)  $checked1 = 'checked' ;
		  if($check2 == 1)   $checked2 = 'checked' ;
		  if($check3 == 1)  $checked3 = 'checked' ;
		  if($check4 == 1)  $checked4 = 'checked' ;
		  if($check5 == 1)  $checked5 = 'checked' ;
		  if($check6 == 1)  $checked6 = 'checked' ;
		  if($check7 == 1)  $checked7 = 'checked' ;
		  if($check8 == 1)  $checked8 = 'checked' ;
		  if($check9 == 1)  $checked9 = 'checked' ;
		 
		  ?>
		  
		  
  <div class="row">
    <div class="col-sm-3"><label for="antenatal">Inherited Genetic Conditions:</label></div>
    <div class="col-sm-3" >
	    <input type="checkbox" name='cyclic' <?php echo $checked1;  ?>> Cyclic Fibrosis  <br>
	    <input type="checkbox" name='anaemia' <?php echo $checked2;  ?>> Sickle Cell Anaemia  <br>
	    <input type="checkbox" name='heamophilia' <?php echo $checked3;  ?>> Heamophilia <br>
	    
	</div>
  </div>
 
  <br>
  <div class="row">
    <div class="col-sm-3"><label for="antenatal">Pregnancy Loss :</label></div>
    <div class="col-sm-3" >
	    <input type="checkbox" name='preg_loss_m' <?php echo $checked4;  ?>> Mother   <br>
	    <input type="checkbox" name='preg_loss_s' <?php echo $checked5;  ?>> Sister <br>
	   
	    
	</div>
  </div>
  
  <br>
  <div class="row">
    <div class="col-sm-3"><label for="antenatal">Pre-eclampsia:</label></div>
    <div class="col-sm-3" >
	    <input type="checkbox" name='pre_m' <?php echo $checked6;  ?>> Mother   <br>
	    <input type="checkbox" name='pre_s' <?php echo $checked7;  ?>> Sister  <br>
	    
	    
	</div>
  </div>
  
  <br>
  <div class="row">
    <div class="col-sm-3"><label for="antenatal">Abnormal Children :</label></div>
    <div class="col-sm-4" >
	    <input type="checkbox" name='abnormal_m' <?php echo $checked8;  ?>> Mother   <br>
	    <input type="checkbox" name='abnormal_s' <?php echo $checked9;  ?>> Sister  <br>
	    
	    
	</div>
  </div>
   
   
  
</div>
<!------------------------------------------------------------------------------------------------------------------------------>
<br>
<div class="container-fluid col-md-1 ">
          

	
	


        

 <button type="submit" name='submit' class="btn btn-default">Submit</button>
</div>




	 
	 
	 
    
  </form>
</div>


</body>
</html>
