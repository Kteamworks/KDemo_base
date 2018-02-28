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
require_once("$srcdir/encounter.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");
require_once("$srcdir/formatting.inc.php");
if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 }
 $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];

setencounter($encounter);
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


$patient=sqlQuery("select * from t_form_admit where pid=$pid and encounter=$encounter");
$pname=sqlQuery("select fname,sex,age from patient_data where pid=$pid");
$Info=sqlStatement("select * from medication_schduling where pid=$pid and encounter=$encounter");
$count=sqlQuery("select count(*) as count from medication_schduling where pid=$pid and encounter=$encounter");


$listResult = sqlStatement("SELECT  * FROM `ipschdule` where pid=$pid and  result!=''");

$ward = $patient['admit_to_ward'];
if(empty($ward)){
	 echo ("<script>alert('Please select IP visit')
window.location.href='../main/finder/p_dynamic_finder_ip.php';
</script>"); 
}


/*if(($encounter==0)||($encounter=='')){ 
	
 echo ("<script>alert('Please select visit')
window.location.href='../main/finder/p_dynamic_finder_ip.php';
</script>"); 
}*/



//$list1 = sqlStatement("SELECT  * FROM `nurseIP`");

 //$ward = $patient['admit_to_ward'];
 $bed = $patient['admit_to_bed'];

  


if(isset($_POST['submit'])){
	
	
	$hours = $_POST['hours'];
	 $dt = date('Y-m-d');
	if(empty($hours))
	{
		 $hours = 24; 
	}
	
	 $days = $_POST['days'];
	 if(empty($days))
	 {
		  $days = 1;
	 }
	 $newDays = date('Y-m-d', strtotime($time. " + {$days} days")); 

	  $tym = $_POST['service_time'];
	 
	 
	 $med = $_POST['medicine']; 
	 $genServ = $_POST['generalService']; 
	 $speServ = $_POST['specialService']; 
	 $surgicalKits = $_POST['surgeryKit'];	
    $service1 = $_POST['service'];
	
	
	
	 
	
	/* $check = sqlQuery("select count(Service) as chk from ip_services where Service = '$service'");
	 $chk1 = $check['chk'];
	 if($chk1 < 1){
		 $addService = sqlInsert("insert into ip_services(Service) 
		            values('$service')");	
	 } */
	
	if($service1=='Medicine'){
		 
		  $service = $med; 
   }
	else if($service1=='Special Services'){
		 $service = $speServ; 
		 
		 $serv_id = sqlQuery("select service_id from codes where code = '$service'");
		 $price =  sqlQuery("select pr_price from prices where pr_level = 'standard' and pr_id = (select id from codes where code_text = '$service')");
		 $service_price = $price['pr_price'];
		 $speSreId = $serv_id['service_id'];
		 
	$service_charges = sqlInsert("insert into billing(pid,encounter,date,code_type,code,code_text,activity,fee,service_id,servicegrp_id,groupname,authorized,billed,bill_date,payout) 
		          values('$pid','$encounter','$dt','Services','$service','$service','1','$service_price','$speSreId','6','Default','1','0','$dateTime','0')");	 
	 }
	 	else if($service1=='Surgery Kits'){
		 $service = $surgicalKits; 
		 		 $serv_ids = sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='$service' and b.pr_level='standard' and b.pr_price!=0");
	
while($serv_id = sqlFetchArray($serv_ids)) {
	$codetype=sqlStatement("select ct_key from code_types where ct_id=$service");
	$codetype1 = sqlFetchArray($codetype);
	$codetype2 = $codetype1['ct_key'];
	$code=$serv_id['code'];
 $codetext=$serv_id['code_text'];
 $service_id=$serv_id['service_id'];
 $servicegrp_id=$serv_id['code_type'];
   $billed=0;
   $units=1;
   $fee=$serv_id['pr_price'];
 $authrzd=1;
 $modif="";
 $act=1;
 $grpn="Default";
 $onset_date=date('Y-m-d H:i:s');
  sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
   "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype2) . "', " .
    "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
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
		 }
	 }
	// else if($service1=='General Services'){
		else $service= $genServ;
	 
	// else{} */

	

	
		
	 
	 $fee = sqlQuery("select PricePerUnit from drugs where name = '$service' limit 1");
	 $fees = $fee['PricePerUnit'];
	
 	  $noOfHrs = $days*24 ;
	  $times= $noOfHrs/$hours;
	 
	  
     $dateTime =  $dt.' '.$tym;
	  
	  
	    date_default_timezone_set('Asia/Kolkata');
        $time = date('H:i');
		
  		
  $medication = sqlInsert("insert into medication_schduling(pid,encounter,ward,bed,category,service,dated,frequency,days) 
		            values('$pid','$encounter','$ward','$bed','$service1','$service','$dt','$hours','$days')");	

					
	$chkMed = sqlQuery("select count(code) as chkMed from billing where encounter = '$encounter' and code='MEDICINE CHARGES' and activity=1");
	
	
	
	 $chkMed1 = $chkMed['chkMed']; 
	 if($chkMed1 < 1){
		
		$addmedical_charges = sqlInsert("insert into billing(pid,encounter,date,code_type,code,code_text,activity,fee,service_id,servicegrp_id,groupname,authorized,billed,bill_date,payout) 
		          values('$pid','$encounter','$dt','Services','MEDICINE CHARGES','MEDICINE CHARGES','1','0','SRV001162','6','Default','1','0','$dateTime','0')");	
	 }
					
				
					
  						
		
	
	
	$j=1;

	while($j<=$times){
		
		if($newTime==''){
			$newTime = $dateTime;
		}
		
		
		$time = $newTime;
		$t = substr($time,11);
	
		
		 $date= substr($time,0,10);
		 if($date >= $newDays)
		   break;
	
	
		 
		
		
         $addedTime=$hours;

        $newTime = date('Y-m-d H:i', strtotime($time. " + {$addedTime} hours"));
		
		
		if(($service1=='Special Services')||($service1=='Surgery Kits')){
           $Nurse = sqlInsert("insert into ipschdule(pid,encounter,ward,bed,tym,service,dated,fee,activity,result,updatedTime) 
		            values('$pid','$encounter','$ward','$bed','$t','$service','$date','$fees','1','Completed','$t')");

		}
		else{ 
		$Nurse = sqlInsert("insert into ipschdule(pid,encounter,ward,bed,tym,service,dated,fee,updatedTime) 
		            values('$pid','$encounter','$ward','$bed','$t','$service','$date','$fees','$t')");
		}		
		 
	
		
	$j++;}
	
	header('location:timeSchduler.php');
	
	      
 
}





 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scheduler</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
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
	
	 
	 function set_medicine() {
	var sel = $('#serviceName').val();
	if(sel=='Medicine'){
	 $(".hidemedicine").css("display","");
	 $("#reqMed").attr("required","required");
	 $(".hideitem").css("display",'');
	}
	else{
              $(".hidemedicine").css("display","none");
			  $("#reqMed").attr("required",false);
			  
      	 }
	
	if(sel=='Special Services'){
	 $(".hideservice").css("display","");
	 $("#reqSpe").attr("required","required");
	 $(".hideitem").css("display",'none');
	 
	 
	}
	else{
              $(".hideservice").css("display","none");
			  $("#reqSpe").attr("required",false);
			  
      	      }
	if(sel=='Surgery Kits'){
	 $(".surgerykit").css("display","");
	 $("#surgerykit").attr("required","required");
	 $(".hideitem").css("display",'none');
	 
	 
	}
	else{
              $(".surgerykit").css("display","none");
			  $("#surgerykit").attr("required",false);
			  
      	      }
	
	if(sel=='General Services'){
	 $(".hideGenService").css("display","");
	 $("#reqGen").attr("required","required");
	 $(".hideitem").css("display",'');
	 
	}
	else{
          $(".hideGenService").css("display","none");
		  $("#reqGen").attr("required",false);
			  
      	      }
	
 }
 
 

 
 
 
 
 
 
 
 
	$(document).ready(function()
{
	
	$('#toggle_doc').click(function() { 
	
$('#input_dr > input').attr("disabled",false);
	$(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');
	$('#select_dr, #input_dr').toggle();

   });
   
   
});
	
	
    
	
	
  </script>
 <script type="text/javascript">
function setMyPatient() {
<?php if ($GLOBALS['concurrent_layout']) { ?>
 // Avoid race conditions with loading of the left_nav or Title frame.
 if (!parent.allFramesLoaded()) {
  setTimeout("setMyPatient()", 500);
  return;
 }
<?php 
 $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
 if (isset($_GET['set_pid'])) { ?>
 parent.left_nav.setPatient(<?php echo "'" . htmlspecialchars(($result['fname']) . " " . ($result['lname']),ENT_QUOTES) .
   "'," . htmlspecialchars($pid,ENT_QUOTES) . ",'" . htmlspecialchars(($result['genericname1']),ENT_QUOTES) .
   "','', ' " . htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate($result['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result['DOB_YMD']), ENT_QUOTES) . "'"; ?>);
 var EncounterDateArray = new Array;
 var CalendarCategoryArray = new Array;
 var EncounterIdArray = new Array;
 var Count = 0;
<?php
  //Encounter details are stored to javacript as array.
  $result4 = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
  if(sqlNumRows($result4)>0) {
    while($rowresult4 = sqlFetchArray($result4)) {
?>
 EncounterIdArray[Count] = '<?php echo htmlspecialchars($rowresult4['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray[Count] = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray[Count] = '<?php echo htmlspecialchars(xl_appt_category($rowresult4['pc_catname']), ENT_QUOTES); ?>';
 Count++;
<?php
    }
  }
?>

 parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
  <?php
  $test = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? and  fe.encounter=? order by fe.date desc", array($pid,$encounter));
	 $test1=sqlFetchArray($test);
?>
 EncounterIdArray1= '<?php echo htmlspecialchars($test1['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray1 = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($test1['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray1 = '<?php echo htmlspecialchars(xl_appt_category($test1['pc_catname']), ENT_QUOTES); ?>';
 parent.left_nav.setEncounter(EncounterDateArray1,EncounterIdArray1,CalendarCategoryArray1);
<?php } // end setting new pid ?>
 parent.left_nav.setRadio(window.name, 'dem');
 parent.left_nav.syncRadios();
<?php } // end concurrent layout ?>
}
$(window).load(function() {
 setMyPatient();
});
</script>
</head>
<body>
<form name='' method='POST' action=''>

<div class="table-responsive">
<table class="table table-striped table-bordered table-responsive">

      <tr class='active'>
        <th>Patient Name</th>
		<th>Gender</th>
		<th>Age</th>
        <th>Ward</th>
        <th>Bed</th>
		
		<th>Detail</th>
		</tr>
		
		<tr class='info'>
        <td><?php echo $pname['fname'];?></td>
        <td><?php echo $pname['sex'];?></td>
		<td><?php echo $pname['age'];?></td>
		<td><?php echo $patient['admit_to_ward'];?></td>
        <td><?php echo $patient['admit_to_bed'];?></td>
		
		<td><button type="button"  data-toggle="modal" data-target="#myModal">Click Here</button> </td>
		</tr>
		
	
</table>
</div>

<div class="container col-md-offset-10">
 
  <!-- Trigger the modal with a button -->
  

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
	  <div id='div1'>
        <div class="modal-header">
         
          <h4 class="modal-title">Medication Summary of <?php echo $pname['fname'];  ?></h4>
        </div>
        <div  class="modal-body">
		
		
		
		<table class="table table-striped  table-condensed table-responsive">
    <thead>
      <tr class='active'>
	    
		<th>Services</th>
		
        <th>Date</th>
        <th>Time</th>
        
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
	  
	  
       
	   <td class="table-active"><?php echo $listResult1['service']; ?></td>
		<td class="table-active"><?php echo $dated; ?></td>
		<td class="table-active"><?php echo $tym; ?></td>
		
		<td class="table-active"><?php echo $listResult1['result'];   ?></td>
		
		
        
	
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
  
</div>
<!---------------------------------------------------------------modal------------------------------------------------------------->



 
<div class="container col-md-3 col-lg-3 well">


<!--------------------------------------------------------------------------------------------------------->

 <div class="form-group" >
      <label for="service">Service</label>
	  <!--<a href="#" id='toggle_doc' title="Service Not listed? Add Service"><i class="fa fa-plus-circle"></i></a>-->
	  <div class="form-group" id='select_dr'>
	  
	  
        <select class="form-control" onchange='set_medicine()' id="serviceName" name='service' required>
		<option value="">Select</option>
		<option value="General Services">General Services</option>
		<option value="Surgery Kits">Surgery Kits</option>
		<option value="Special Services">Special Services</option>
		<option value="Medicine">Medicines</option>
		
			
              </select>
	  
    
	  </div>
	  
	  
    </div>
	
	
	
	<!--------------------------------------------------------------------------------------------------------->

<div class="form-group" id='input_dr' style="display:none">
     
      <input type="text" class="form-control" id='input_ser' name="service" required disabled >
	  
	  </div>
	   
	
<!------------------------------------------------------------------------------------------------------------>	
	<div class="form-group hidemedicine" style="display:none">
      <label for="email">Medicine List</label>
	  <div class="form-group">
	  
	 

        <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="medicine" id='reqMed'>
		<option value="">Select</option>
		 <?php
          $medList = sqlStatement("select * from drugs order by name");
		  while($medList1 = sqlFetchArray($medList)){
			  
	  ?>
        <option value="<?php echo $medList1['name'];   ?>"> <?php echo $medList1['name'];  ?></option>
		  <?php  }  ?>	
              </select>
	  
     
	  </div>
    </div>
	
<!--------------------------------------------------------------------------------------------------------------->	
<div class="form-group hideservice" style="display:none">
      <label for="Service">Service List</label>
	  <div class="form-group">
	  
	 

        <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="specialService" id='reqSpe'>
		<option value="">Select</option>
		 <?php
          $serList = sqlStatement("SELECT id,code,code_text,code_type,service_id FROM codes WHERE active=1 AND code_type=6 ORDER BY id,code");
		  while($serList1 = sqlFetchArray($serList)){
			  
	  ?>
        <option value="<?php echo $serList1['code']; ?>"> <?php echo $serList1['code_text'];  ?></option>
		  <?php  }  ?>	
              </select>
	  
     
	  </div>
    </div>
	<div class="form-group surgerykit" style="display:none">
      <label for="Surgery Kits">Surgery Kits</label>
	  <div class="form-group">
	  
	 

        <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="surgeryKit" id='surgerykit'>
		<option value="">Select</option>
		 <?php
          $kitList = sqlStatement("SELECT ct_key,ct_id,ct_label,ct_active FROM code_types WHERE ct_active=1 AND ct_key like '%kit%' ORDER BY ct_id");
		  while($kitList1 = sqlFetchArray($kitList)){
	  ?>
        <option value="<?php echo $kitList1['ct_label']; ?>"> <?php echo $kitList1['ct_label'];  ?></option>
		  <?php  }  ?>	
              </select>
	  
     
	  </div>
    </div>
<!--------------------------------------------------------------------------------------------------------------->	

<!--------------------------------------------------------------------------------------------------------------->	
<div class="form-group hideGenService" style="display:none">
      <label for="List">Services List</label>
	  <div class="form-group">
	  
	 

        <select class="form-control"  name="generalService" id = 'reqGen'>
		<option value="">Select</option>
		 <?php
          $serviceList = sqlStatement("select * from ip_services order by Service");
		  while($serviceList1 = sqlFetchArray($serviceList)){
			  
	  ?>
        <option value="<?php echo $serviceList1['Service'];   ?>"> <?php echo $serviceList1['Service'];  ?></option>
		  <?php  }  ?>	
              </select>
	  
     
	  </div>
    </div>
<!----------------------------------------------------------------------------------------------------------------->	
	


	
	
    <div class="form-group">
	<?php
   $tym=date('G:i');
	
        
	?>
      <label for="time">Time</label>
      <input type="time" class="form-control" id="pwd" value='<?php echo $tym;  ?>' placeholder="Enter password" name="service_time">
    </div>
	
	<div class="form-group hideitem">
      <label for="pwd">Repetition Time</label>
      <input type="number" class="form-control"  min='0' max='24' value='' placeholder="Hours"  name="hours">
	  
	  </div>
	
	
	<div class="form-group hideitem">
      <label for="pwd">Days</label>
      <input type="number" class="form-control"   placeholder="No. of days" value='' min='0' id='days' name="days">
    </div>
    
    <button type="submit" name='submit' class="btn btn-default">Add</button>




<!--------------------------------------------------------------------------------------------------------->	 

  
</div>

<div class="container col-md-2">

	<iframe src="patient_note.php"  width = "350" height = "250"></iframe>
	 


</div>



<!-----------------------------------------------right side ------------------------------------------------->
<?php
   if($count['count']>=1){
	   
     ?>


<div class="container col-md-offset-2 col-md-5 well">
<table class="table table-striped  table-condensed">
    <thead>
      <tr class='active'>
        <th>Service</th>
        <th>Date</th>
		<th>Repetition Time</th>
        <th>Days</th>
		
		
      </tr>
    </thead>
    <tbody>
	
	<?php $i=1;
	 while($infoResult=sqlFetchArray($Info))  {  ?>
	 <tr>
	     <?php $rep_time = $infoResult['frequency'].' '.'Hours'; 
		  $category = $infoResult['category'];
		  $no_of_days = $infoResult['days'];
		  if($category=='Special Services' || $category=='Surgery Kits'){
			  $rep_time = '------';
			  $no_of_days = '------';
		  }

$infoService = $infoResult['service'];

		 ?>
	     <td class="table-active"><?php /*if($infoResult['category']=='Surgery Kits'){
			 $sel_service = $infoResult['service'];
			  
	$dcodetype=sqlStatement("select ct_label from code_types where ct_id= '$sel_service'");
	$dcodetype1 = sqlFetchArray($dcodetype);
	echo $dcodetype1['ct_label'];
		  } else */  echo $infoService; ?></td>
         <td class="table-active"><?php echo $infoResult['dated']; ?></td>
	     <td class="table-active"><?php echo $rep_time; ?></td>
         <td class="table-active"><?php echo $no_of_days ?></td>
	 </tr>
	<?php  $i++;  
	 }  ?>
	</tbody>
	</table>
	 


</div>
<?php 
   }
?>
<!-------------------------------------------------end-------------------------------------------------------->

</form>

</body>
</html>



