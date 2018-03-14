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

$list1 = sqlStatement("SELECT  * FROM Revenue where approve=0 order by date desc");


$listResult = sqlStatement("SELECT  * FROM Revenue where approve = 1 order by date desc");




/*----------------------------------date time comparision---------------------------------------------------------*/
date_default_timezone_set('Asia/Kolkata');
$time = date('H:i');
$addedTime=1;

//echo $newDate = date('H:i', strtotime($time. " + {$addedTime} hours"));
/*----------------------------------------------------------------------------------------------------------------*/

if(isset($_POST['submit'])){
	$j=0;
	foreach($_POST['id'] as $selected) {
	$result = $_POST['result'][$j]; 
	 $id = $_POST['id'][$j];
	
	
	
	
	 $clinical = sqlQuery("update Revenue set approve='$result' where ID='$id'");
	
	header('Location:collected_amount.php');
	$j++;
	}
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
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<audio id="xyz" src="alert_tones.mp3" preload="auto"></audio>
</head>




<body>
 <h2 align='center' style="background-color:powderblue;">Collection</h2>
<form method='POST' action=''>
<div class="container clo-md-12">
  
  
  <div class='row'>
  <div class='col-md-10'>

<table class="table table-striped  table-condensed   table-responsive">
    <thead>
      <tr class='active'>
	    <th>Date</th>
        <td align='right'><b>Revenue</b></td>
		<td align='right'><b>Payment</b></td>
        <td align='right'><b>Discount</b></td>
        <td align='right'><b>Collection</b></td>
		<td align='right'><b>Difference</b></td>
		<th><b>Approval</th>
		<th></th>
		
		
		
      </tr>
    </thead>
    <tbody>
	<?php $i=1;
	 while($list2=sqlFetchArray($list1))  { ?>
      <tr>
	 
	 
	  
	  <?php
	         
			 
	        $dated=date('d-M-y',strtotime($list2['date']));
            
  

 
	  ?>
	 
	  
        <td class="table-active"><?php echo $dated; ?></td>
	    <td class="table-active" align='right'><?php echo $list2['revenue']; ?></td>
		<td class="table-active" align='right'><?php echo $list2['payment']; ?></td>
		<td class="table-active" align='right'><?php echo $list2['discount']; ?></td>
		<td class="table-active" align='right'><?php echo $list2['collection']; ?></td>
		<td class="table-active" align='right'><?php echo $list2['difference']; ?></td>
		<td id="">
		  <select name='result[]' class="form-control">
              <option value="0">Pending</option>
			  <option value="1">Approved</option>
          </select></td>
		  <td><input type='hidden' name='id[]' value='<?php echo $list2['ID'];   ?>' ></td>
		<!--<td class="table-active"><input type='text'  name='result[]' ></td>-->
		
	  
	 
	
      </tr>  
	  
	    
	<?php $i++; } ?> 
	
	
	<?php $i=1;
	 while($listResult1=sqlFetchArray($listResult))  { 
	  
	         
	
	 ?>
	 
      <tr class='info'>
	  <?php  $dated=date('d-M-y',strtotime($listResult1['date'])); 
	        
                 
	  ?>
	  
	  
        <td class="table-active" ><?php echo $dated; ?></td>
	    <td class="table-active" align='right'><?php echo $listResult1['revenue']; ?></td>
		<td class="table-active" align='right'><?php echo $listResult1['payment'] ?></td>
		<td class="table-active" align='right'><?php echo$listResult1['discount'] ?></td>
		<td class="table-active" align='right'><?php echo $listResult1['collection']; ?></td>
		<td class="table-active" align='right'><?php echo $listResult1['difference'];   ?></td>
		<td class="table-active"><?php echo 'Approved'   ?></td>
		<td></td>
		
		
        
	
      </tr>  
	<?php $i++; } ?> 
	
	
	
    </tbody>
  </table>
</div>
<div class='col-md-2'>
<input type='submit'  class='affix' value='Save' name='submit'>

</div>


</div>
</div>





</form>
</body>
</html>



