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

$past_data = sqlQuery("select * from gyanic_past_history where pid= '$pid'");

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
	
  $diabetes = $_POST['diabetes'];
  if($diabetes=='on') { $diabetes = 1; }  else { $diabetes = 0 ; }
  $hypertension = $_POST['hypertension'];
  if($hypertension == 'on') { $hypertension = 1;} else {$hypertension = 0 ; }
  $hypo_thyroid = $_POST['hypo_thyroid'];
  if($hypo_thyroid =='on') {$hypo_thyroid = 1;} else {$hypo_thyroid = 0 ; }
  $pre_eclampsia = $_POST['pre_eclampsia'];
  if($pre_eclampsia =='on') { $pre_eclampsia = 1;} else{ $pre_eclampsia = 0;}
  $iugr = $_POST['iugr'];
  if($iugr== 'on') {$iugr = 1;} else{$iugr = 0;}
  $unevent_delivery = $_POST['unevent_delivery'];
  if($unevent_delivery =='on') { $unevent_delivery = 1;} else { $unevent_delivery = 0; }
  $birth_injury = $_POST['birth_injury'];
  if($birth_injury =='on'){$birth_injury = 1;} else { $birth_injury = 0;}
  
  $pph = $_POST['pph'];
  if($pph == 'on'){$pph = 1;} else { $pph = 0;}
  $excessive_bleeding = $_POST['excessive_bleeding'];
  if($excessive_bleeding=='on') { $excessive_bleeding = 1 ;} else { $excessive_bleeding=0;}
  $fever = $_POST['fever'];
  if($fever == 'on'){ $fever = 1; } else { $fever = 0 ; }
  
  $gestation = $_POST['gestation'];
  $dod = $_POST['dod']; 
  $mode = $_POST['mode']; 
  
  $enc = $past_data['encounter']; 
   if(!$past_data){
  
  $past_history = sqlStatement("insert into gyanic_past_history(`Diabetes Mellitus`,`Hypertension`,`Hypo Thyroid`	,`Pre-eclampsia`	,`IUGR`,`Uneventful Delivery`,`Birth Injuries`,`PPH`,`Excessive Bleeding`,`Fever`,`pid`,`encounter`,`Length of Gestation`,`Date of Delivery`,`Mode of Delivery`)values('$diabetes','$hypertension','$hypo_thyroid','$pre_eclampsia','$iugr','$unevent_delivery','$birth_injury','$pph','$excessive_bleeding','$fever','$pid','$encounter','$gestation','$dod','$mode')");
  
   }
   else {
	   $past_history = sqlStatement("update gyanic_past_history set `Diabetes Mellitus` ='$diabetes', `Hypertension`= '$hypertension', `Pre-eclampsia` ='$pre_eclampsia',`IUGR`='$iugr', `Uneventful Delivery`= '$unevent_delivery', `Birth Injuries` = '$birth_injury', `Birth Injuries` = '$birth_injury' , `PPH` = '$pph', `Excessive Bleeding` = '$excessive_bleeding' ,`Fever`= '$fever', `Length of Gestation` = '$gestation',`Date of Delivery`='$dod',`Mode of Delivery` = '$mode', `Hypo Thyroid`='$hypo_thyroid' where `pid` = '$pid' ");
   }
  
  header('location:family_history.php');
  
  
  
  
  
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
			
			
			
			<?php// if($vid2 == null) { ?>
			<li><a href="../encounter/load_form.php?formname=vitals"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			
			<?php// } else { ?>
			<li><a href="../encounter/view_form.php?formname=vitals&id=<?php echo $vid2; ?>"><i class="fa fa-note" style="margin-right: 8px;"></i>Vitals</a></li>
			<?php// } ?>-->
			
			<li><a href="gyanic_form.php"><i class="fa fa-note" style="margin-right: 8px;"></i>Obstetric History</a></li>
			<li  class="current"></i><em>Past History</em></li>
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
<form action='' method='POST'>
<div class="container-fluid col-md-offset-1 col-md-6">
  <br>
  <div class="row">
    <div class="col-sm-2"><label for="antenatal">Antenatal:</label></div>
    <div class="col-sm-4" >
	<?php $check1 = $past_data['Diabetes Mellitus']; 
          $check2 = $past_data['Hypertension']; 	
          $check3 = $past_data['Hypo Thyroid']; 	
          $check4 = $past_data['Pre-eclampsia']; 	
          $check5 = $past_data['IUGR']; 	
          $check6 = $past_data['Uneventful Delivery']; 	
          $check7 = $past_data['Birth Injuries']; 	
          $check8 = $past_data['PPH']; 	
          $check9 = $past_data['Excessive Bleeding']; 	
          $check10 = $past_data['Fever']; 
          $check11 = $past_data['Mode of Delivery']; 		  
	      
		  
		  if($check1 == 1)  $checked1 = 'checked' ;
		  if($check2== 1)   $checked2 = 'checked' ;
		  if($check3 == 1)  $checked3 = 'checked' ;
		  if($check4 == 1)  $checked4 = 'checked' ;
		  if($check5 == 1)  $checked5 = 'checked' ;
		  if($check6 == 1)  $checked6 = 'checked' ;
		  if($check7 == 1)  $checked7 = 'checked' ;
		  if($check8 == 1)  $checked8 = 'checked' ;
		  if($check9 == 1)  $checked9 = 'checked' ;
		  if($check10 == 1) $checked10 = 'checked' ;
		  if($check11 == 'Cesarean') {$checked11 = 'checked'; }
		    else { $checked12 = 'checked' ;}
		  
	?>
	    <input type="checkbox" name='diabetes' <?php echo $checked1;  ?>> Diabetes Mellitus  <br>
	    <input type="checkbox" name='hypertension' <?php echo $checked2;  ?>> Hypertension <br>
	    <input type="checkbox" name='hypo_thyroid' <?php echo $checked3;  ?>> Hypo Thyroid <br>
	    <input type="checkbox" name='pre_eclampsia' <?php echo $checked4;  ?>> Pre-eclampsia <br>
	    <input type="checkbox" name='iugr' <?php echo $checked5;  ?>> IUGR <br>
	</div>
  </div>
 
  <br>
  <div class="row">
    <div class="col-sm-2"><label for="antenatal">Intranatal:</label></div>
    <div class="col-sm-4" >
	    <input type="checkbox" name='unevent_delivery' <?php echo $checked6;  ?>> Uneventful Delivery   <br>
	    <input type="checkbox" name='birth_injury' <?php echo $checked7;  ?>> Birth Injuries <br>
	    <input type="checkbox" name='pph' <?php echo $checked8;  ?>> PPH  <br>
	    
	</div>
  </div>
  
  <br>
  <div class="row">
    <div class="col-sm-2"><label for="antenatal">Postnatal:</label></div>
    <div class="col-sm-4" >
	    <input type="checkbox" name='excessive_bleeding' <?php echo $checked9;  ?>> Excessive Bleeding   <br>
	    <input type="checkbox" name='fever' <?php echo $checked10;  ?>> Fever  <br>
	    
	    
	</div>
  </div>
  
  
</div>
<!------------------------------------------------------------------------------------------------------------------------------>
<br>
<div class="container-fluid col-md-offset-1 col-md-3 ">
           <div class="form-group">
      <label for="gestation">Length of Gestation(in-weeks)</label>
	  <input type='number' class='form-control' name='gestation' value='<?php  echo $past_data['Length of Gestation']; ?>' min='0'>
      <!--<select class="form-control" id="sel1">
        <option>40 Weeks</option>
		<option>37 Weeks</option>
        <option>42 Weeks</option>
        
      </select>-->
    </div>
	
	
<div class="form-group">
      <label for="LMP">Date of Delivery</label>
      <input type="date" class="form-control" id="dod"  name="dod"value='<?php  echo $past_data['Date of Delivery']; ?>' max='<?php echo date('Y-m-d') ?>'>
    </div>
	
	
	


           <div class="form-group">
      <label for="email">Mode of Delivery</label></div>
	  <div>
	  <input type="radio" name='mode' value ='Normal' <?php echo $checked12 ;   ?>> Normal   <br>
	    <input type="radio" name='mode' value = 'Cesarean'  <?php echo $checked11 ;  ?>> Cesarean  <br>
      <!--<select class="form-control" name='mode'>
        <option>Normal</option>
		<option>Cesarian </option>
        
        
      </select>-->
    </div><br>

 <button type="submit" name='submit' class="btn btn-default">Submit</button>
</div>




	 
	 
	 
    
  </form>



</body>
</html>
