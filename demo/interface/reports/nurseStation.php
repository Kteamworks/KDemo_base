<?php 
/**
 * 
 * Superbill Report
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once(dirname(__file__)."/../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");
require_once("$srcdir/formatting.inc.php");

$startdate = $enddate = "";
if(empty($_POST['start']) || empty($_POST['end'])) {
    // set some default dates
    $startdate = date('Y-m-d', (time() - 10*24*60*60));
    $enddate = date('Y-m-d', time());
}
else {
    // set dates
    $startdate = $_POST['start'];
    $enddate = $_POST['end'];
}
//Patient related stuff
if ($_POST["form_patient"])
$form_patient = isset($_POST['form_patient']) ? $_POST['form_patient'] : '';
//$form_pid = isset($_POST['form_pid']) ? $_POST['form_pid'] : '';
$form_pid= $_SESSION["pid"];
if ($form_patient == '' ) $form_pid =  $_SESSION["pid"];
//if ($form_patient == '' ) $form_pid = '';

$dmy= date('Y-m-d');

$list1 = sqlStatement("SELECT  * FROM `ipschdule` where result='' and dated<='$dmy' group by bed");

//$listResult = sqlStatement("SELECT  * FROM `ipschdule` where result!='' group by bed order by dated, tym");
$totalMedBill = sqlStatement("SELECT pid,encounter, sum(fee) as fees from ipschdule where activity = 1 group by encounter");
 $p = 0;
while($totalMBill = sqlFetchArray($totalMedBill)){
	
	 $totalBillingValue = $totalMBill['fees'];
     $BillingPid = $totalMBill['pid'];
	 $billingEncounter = $totalMBill['encounter'];
	$updateBilling = sqlQuery("update billing set fee='$totalBillingValue' where pid = $BillingPid and encounter = $billingEncounter and code = 'MEDICINE CHARGES' and activity=1");
	
	
	
$p++;	
}


/*----------------------------------date time comparision---------------------------------------------------------*/
date_default_timezone_set('Asia/Kolkata');
$time = date('H:i');
$addedTime=1;

//echo $newDate = date('H:i', strtotime($time. " + {$addedTime} hours"));
/*----------------------------------------------------------------------------------------------------------------*/

if(isset($_POST['submit'])){

	$j=0;
foreach($_POST['id'] as $selected) {
        
		
		  $id = $_POST['id'][$j];
		 
		
		  $visit = $_POST['visit'][$j];
		
        $res = $_POST['res'][$j]; 
	   if($res!=""){
		   //echo "update ipschdule set result='$res',updatedTime='$time', activity=1 where ID='$id'"; exit;
		  $clinical = sqlQuery("update ipschdule set result='$res',updatedTime='$time', activity=1 where ID='$id'");
	   }
	   
	   $result = $_POST['result'][$j]; 
	   
	   if($result!=''){
		   //echo "update ipschdule set result='$result',updatedTime='$time', activity=1 where ID='$id'"; exit;
		   $clinical = sqlQuery("update ipschdule set result='$result',updatedTime='$time', activity=1 where ID='$id'");
	   }

   $j++; } 
   
   
  
	session_start();
	// Storing session data
	$bed_no = sqlQuery("select bed from ipschdule where ID='$id'");
	$_SESSION["bed_id"] = $bed_no['ward'].'/'.$bed_no['bed'];
	$_SESSION['LAST_BED_ACTIVITY'] = time();
  header('Location:nurseStation.php');
}





 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta http-equiv="refresh" content="100">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet" />
	
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
  .fix { 
      background: bisque;
    padding: 5px;
  }
  .max-list-height {
  height: 250px;
  overflow-y: scroll;
  margin-bottom: 15px;
  }
  body {
    overflow-y: auto !important;
}
  </style>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js" type="text/javascript"></script>

<script type="text/javascript">
        BootstrapDialog.show({
            title: 'Say-hello dialog',
            message: 'Hi Apple!'
        });
</script>
<audio id="xyz" src="alert_tones.mp3" preload="auto"></audio>
</head>




<body>

<div class="container-fluid">
  <h2>Nurse Station</h2>
  <?php 
  if (isset($_SESSION['LAST_BED_ACTIVITY']) && (time() - $_SESSION['LAST_BED_ACTIVITY'] > 120)) 
{
    unset($_SESSION['bed_id']);
    unset($_SESSION['LAST_BED_ACTIVITY']);

}
?>
<?php if(isset($_SESSION["bed_id"])) { ?>
<div class="alert alert-info alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Info!</strong> <?php echo $_SESSION["bed_id"] ?> is service completed.
</div>
<script type='text/javascript'>  setTimeout(function() {
    $('.alert').fadeOut('slow');}, 920
  );</script>
<?php } ?>
  <div class='row'>
  	<?php $i=1;
	 while($list2=sqlFetchArray($list1))  {  ?>
      
	  
	  <?php
	         $patID = $list2['pid'];
	         $pname = sqlQuery("SELECT  * FROM patient_data where pid='$patID'");
	         $dated=date('d-M-y',strtotime($list2['dated']));
			 $bed = $list2['bed'];
             $tym=date('h:i:s A',strtotime($list2['tym']));
			 if($tym == '12:00:00 AM')
				 continue;
	  
	   
	  date_default_timezone_set('Asia/Kolkata');
	 $currentTime = date("H:i");
	  $list2['tym'];
	if($currentTime>=$list2['tym'])
	 { 
 
 
 
   echo "<script type='text/javascript'>";
    echo "document.getElementById('xyz').play();";
	//echo "setTimeout(function(){alert('Thank you!')},6000);";
	echo "setTimeout(function(){";
   echo "BootstrapDialog.show({
            title: 'Alert!',
            message: '";
   echo $list2['service'];
   echo " time for Bed No. ";
   echo $list2['bed'];
   echo "'}); },100);";
   echo "</script>";
 }
 
	  ?>
  	  
	  

	
	

  <div class="col-md-6 max-list-height">
  <form method='POST' action=''>
  <div class="fix">
  <label>Name:</label> <?php echo $pname['fname']; ?> &nbsp;
  <label>Ward / Bed No: </label> <?php echo  $list2['ward'].' / '.$list2['bed']; ?>

<input type='submit'  class=' pull-right' value='Save' name='submit'>
</div>
  <table class="table table-striped  table-condensed table-responsive">
    <thead>
      <tr class='active'>
	    <th>Date</th>
        <th>Time</th>
		<th>Service</th>
		<th>Value / Status </th>
		<th><i class="fa fa-cog fa-spin fa-fw"></i>
<span class="sr-only">Loading...</span></th>
		</tr>
		</thead>
		<tbody>
		<?php	$service_list_qry = sqlStatement("SELECT  * FROM `ipschdule` where bed='$bed' and activity=0 order by dated, tym" ); $a =1; while($service_list = sqlFetchArray($service_list_qry)) { ?>

		<tr class="<?php if($currentTime>=$service_list['tym'])
	 {  echo "danger"; } ?>">
		<td class="table-active"><?php echo $service_list['dated']; ?></td>
		<td class="table-active"><?php echo $service_list['tym']; ?></td>
		<td class="table-active"><?php echo $service_list['service']; ?></td>
		<td id="<?php echo 'select_dr'.$i.$a ?>">
		  <select name='result[]' class="form-control">
              <option value="">Pending</option>
			  <option value="Completed">Completed</option>
          </select></td>
	   <td class="form-group" id="<?php echo 'input_dr'.$i.$a ?>" style="display:none">
	 <input type="text" name="res[]" class='form-control' style="width: 100%" >
	 </td>
	    <td><a href="#" id="<?php echo 'toggle_doc'.$i.$a ?>" title="Add Result"><i class="fa fa-plus-circle"></i></a></td>
		
		
		<input type='hidden' value='<?php echo $service_list['encounter']; ?>' name='visit[]'>
		<input type='hidden' value='<?php echo $service_list['ID']; ?>' name='id[]'>
		</tr>
		<script language="JavaScript">


$(document).ready(function()
{
	
	$('#<?php echo "toggle_doc".$i.$a ?>').click(function() { 
	
		
$('#<?php echo "input_dr".$i.$a ?> > input').attr("disabled",false);
	$(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');
	$('#<?php echo "select_dr".$i.$a ?>, #<?php echo "input_dr".$i.$a ?>').toggle();

   });
	
});
</script>	 
		<?php $a++; } ?>
			
		</tbody>
		</table>
		  </form>
  </div>

	<?php $i++; } ?> 

  </div>

</div>


<!--------------------------------------------------Output------------------------------------------------------------------------------------>

<!--<div class="container clo-md-12">
  
  
  <div class='row'>
  <div class='col-md-10'>
 
<table class="table table-striped  table-condensed">
    <thead>
      <tr class='active'>
        <th>Ward/Bed</th>
        <th>Date</th>
		<th>Time</th>
        <th>Services</th>
		<th>Result</th>
		
		
      </tr>
    </thead>
    <tbody>
	<?php $i=1;
	 while($listResult1=sqlFetchArray($listResult))  { 
	
	 
	 ?>
	 
      <tr>
	  <?php  $dated=date('d-M-y',strtotime($listResult1['updatedTime'])); 
	         $tym=date('h:i:s A',strtotime($listResult1['updatedTime'])); 
                 
	  ?>
	  
	  
        
	    <td class="table-active"><?php echo $listResult1['ward'].'/'. $listResult1['bed']; ?></td>
		<td class="table-active"><?php echo $dated; ?></td>
		<td class="table-active"><?php echo $tym; ?></td>
		<td class="table-active"><?php echo $listResult1['service']; ?></td>
		<td class="table-active"><?php echo $listResult1['result'];   ?></td>
		
		
        
	
      </tr>  
	<?php $i++; } ?> 
    </tbody>
  </table>
</div>


</div>
</div> -->




</body>
</html>



