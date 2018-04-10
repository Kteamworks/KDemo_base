 <?php
 
 
// Copyright (C) 2005-2011 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/api.inc");
require_once("codes.php");
require_once("../../../custom/code_types.inc.php");
require_once("../../drugs/drugs.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formdata.inc.php");

$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
 include_once("$srcdir/pid.inc");
 if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
  $gchid = $_GET['set_pid'];
 }
 $user=$_SESSION['authUser'];
$prescription_id=$encounter;
$inventory_id=111;
 $tmp1 = sqlQuery("SELECT id from users WHERE username='$user'");
 $user_id=$tmp1['id'];
 //$conn = mysqli_connect('localhost', 'root','','greencity');
 include_once('dbconnect.php');

//sqlQuery("UPDATE patient_data SET pricelevel='standard' where pid=$pid");  
 $tmpid=sqlQuery("select max(id) as id from billing "); 
 $tmpid1=sqlQuery("select max(sequence_no) as id1 from ar_activity "); 
 $_SESSION['maxId1']=$tmpid1['id1']; 
 $_SESSION['maxId']=$tmpid['id'];
if (isset($_POST['submit_val'])) {
 $gch=$_POST['gch'];
  $mode = $_POST['mode'];
  $rrn = $_POST['rrn'];
  $subtotal= $_POST['old_price'];
 $discounts=$_SESSION['dcnt']=$_POST['discount'];
 $discount = ($discounts/100)*$subtotal;
 $total = $subtotal-$discount;
 $patient=$_POST['patname'];
 $encounter=$_SESSION['visit']=$_POST['visit'];
 $pid=$_SESSION['patId']=$_POST['pid'];
 $prescription_id=$encounter;

/*if ($_POST['name']) {

foreach ( $_POST['name'] as $key=>$value ) {
echo $value;
$values = mysql_real_escape_string($value);

//$query = mysql_query("INSERT INTO my_hobbies (hobbies) VALUES ('$values')", $connection );
  
}
} */

sqlQuery("UPDATE form_encounter SET provider_id='4',supervisor_id='0' where pid='$pid' and encounter='$ecnounter'"); 
sqlQuery("insert into ar_activity(pid,encounter,code_type,post_time,adj_amount,memo)values('$pid','$encounter','Pharmacy Charge',NOW(),'$discount','Discount')"); 
sqlQuery("insert into payments(pid,encounter,amount1,dtime,user,towards,method,source,stage)
            values('$pid','$encounter','$total',NOW(),'$user',2,'$mode','$rrn','pharm')");


$j=0;
foreach($_POST['name'] as $selected){
		
		
		 $batch= $_POST['batch'][$j];
		  $schedule_h= $_POST['schedule_h'][$j];
		 if($schedule_h=='NO')
		 { $schedule_h = 0; }
	     else { $schedule_h = 1; }
		 $qty = $_POST['qty'][$j];
		 $price = $_POST['price'][$j];
		  $a= $qty + 1; 
		  $ar_activity =  $a * $price ; 
		  $fee = $price * $qty ;
		 
		// $expdate = $_POST['expdate'][$j];
		  $fee = $price * $qty ;
		
		
        if(empty($selected))
			continue;
		
		
		
		
		
		
        $res =sqlQuery("SELECT * FROM `codes` WHERE `code_type`=11 and code=(select name from drugs where drug_id=$selected)");
  
     $servicegrp_id = $res['code_type'];
	 
	 $cod = $res['code']; 
	 $code=str_replace("'", "", $cod);
     //$code=mysqli_real_escape_string($conn,$res['code']); 
	 $service_id=$res['service_id'];
	 $cod_text = $res['code_text'];
	 $code_text=str_replace("'", "", $cod_text);
    
  
	sqlQuery("insert into ar_activity(pid,encounter,code_type,post_time,pay_amount)values('$pid','$encounter','Pharmacy Charge',NOW(),'$ar_activity')");	
		
  // $expupdate = sqlInsert("update drugs set expdate='$expdate-28'  where drug_id=$selected ");		
		

		
 $bil = sqlInsert("insert into billing (date,encounter,servicegrp_id,service_id, code_type, code, code_text, pid, authorized, user, groupname,units,fee,activity,modifier,schedule_h)
 values
 (NOW(),'$encounter', '$servicegrp_id', '$service_id', 'Pharmacy Charge', '$code' ,'$code_text', '$pid','1','$user_id','Default','$qty','$fee',1,1,
 '$schedule_h')");
 
 
		
	
 $drug_id = sqlInsert("INSERT INTO drug_sales(drug_id, inventory_id, prescription_id, pid, user, sale_date, quantity, fee,encounter)
 values
 ( '$selected', '$inventory_id', '$prescription_id', '$pid', '$user', Now(),'$qty', '$price','$encounter')");
   
      sqlQuery("Update drugs set totalStock= totalStock - ? where drug_id=?",array($qty,$selected));   
	  sqlQuery("Update drug_templates set quantity= quantity - ? where drug_id=?",array($qty,$selected));   
	  sqlQuery("Update drug_inventory set on_hand= on_hand - ? where drug_id=?",array($qty,$selected));  
	  sqlQuery("Update billing_main_copy set total_charges=total_charges + ? where encounter=?",array($price,$encounter));  
  
$j++;

}
header('location:../../patient_file/front_payment_pharmacy.php');

}

  ?>
  
 <!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Selectize.js Demo</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/stylesheet.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="js/jquery.js"></script>
		<script src="../dist/js/standalone/selectize.js"></script>
		<script src="js/index.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
		
		
		
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	</head>
  

  
<script>

 $(document).ready(function(){
	 
	 $("#pname").focus(function()
{
	
var id=$("#gchid").val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=detail",
cache: false,
success: function(html)
{
$("#visitid").val(html);

} 
});
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=detail1",
cache: false,
success: function(html)
{
$("#pname").val(html);

} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=pid",
cache: false,
success: function(html)
{
$("#pid").val(html);

} 
});



});	

	 
	 
	 
	 
	 <?php  $a=1;
	 while($a<=15) { ?>
 $("#<?php echo 'select-tools'.$a ?>").change(function()
{
	
var id=$(this).val();
var dataString = 'id='+ id;
var tmp = 1;
$("#<?php echo 'qty'.$a ?>").val(tmp);
$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=med",
cache: false,
success: function(html)
{
$("#<?php echo 'batch'.$a ?>").html(html);

} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=medPrice",
cache: false,
success: function(html)
{
$("#<?php echo 'price'.$a ?>").val(html);

} 
});

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=schedule_h",
cache: false,
success: function(html)
{
$("#<?php echo 'schedule_h'.$a ?>").val(html);

} 
});



});


$("#<?php echo 'batch'.$a ?>").change(function()
{
	
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",

url: "ajaxMed.php",
data: dataString+"&action=medRate",
cache: false,
success: function(html)
{
$("#<?php echo 'price'.$a ?>").val(html);

} 
});


});


$(document).on("focus", "#<?php echo 'sum'.$a ?>", function() {
   // var d = 0;
   
    var p = $("#<?php echo 'price'.$a ?>").val();
	var q = $("#<?php echo 'qty'.$a ?>").val();
	var sum = (+p)*(+q);
	$("#<?php echo 'sum'.$a ?>").val(sum.toFixed(2));
	
});
	 
	 <?php $a++; } ?>		 
		 
		 
      var i=1;
     $("#add_row").click(function(){
      $('#addr'+i).html("<td>"+ (i+1) +"</td><td><div class='demo'><div class='control-group'><select class='select-tools' placeholder='Select Madicine' name='name[]'></select></div></div></td><td><input  name='mail"+i+"' type='text' placeholder='Mail'  class='form-control input-md'></td><td><input  name='mobile"+i+"' type='text' placeholder='Mobile'  class='form-control input-md'></td>");

      $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
      i++; 
  });
     $("#delete_row").click(function(){
    	 if(i>1){
		 $("#addr"+(i-1)).html('');
		 i--;
		 }
	 });

});


$(document).on('keypress', 'input', function(e) {

  if(e.keyCode == 13 && e.target.type !== 'submit') {
    e.preventDefault();
    return $(e.target).blur().focus();
  }

});


$(document).on("focus", ".total", function() {
    var sum = 0;
    $(".sum").each(function(){
        sum += +$(this).val();
		
    });
    $(".total").val(sum.toFixed(2));
});

$(document).on("focus", ".net", function() {
//    var sum = $(".total").val();
//	var dis= $(".discount").val();
//	var net =  (+sum)-(+dis)
//    $(".net").val(net);
				var oldPrice = document.getElementsByName("old_price")[0].value;
			var discountPrct = document.getElementsByName("discount")[0].value;	
			if (!isNaN(oldPrice) && !isNaN(discountPrct)) {
				//var discount = (oldPrice / 100) * discountPrct;
				var count = (discountPrct / 100) * oldPrice;
				var discount = oldPrice - count;
				if (discount > 0)
					document.getElementsByName("new_price")[0].value = discount.toFixed(2);
			}
});


function set_rrn() {
	var mode = $('#payment_mode').val();
	if(mode=='card_payment'){
		$("#rrn").css("display",'');
	}
	else{
		 $("#rrn").css("display","none");
	}
	
	
}



</script>


<body>
<form method="post" action="">



       <!-- <button type="button" class="btn affix">Basic</button>-->
	  
     

<div class="container-fluid" style="    margin-top: 20px;">
    <div class="row">
		<div class="col-md-9">
		<table class="table table-bordered table-fixed" id="tab_logic">
		<tr><th>ID</th><th>Patient Name</th><th>Visit ID</th><th>Pid</th><tr>
		<tr><td><input type="text" style="text-align:left;" id='gchid' name='gch'  value="<?php echo $gchid ?>" class="form-control" required/></td>
		<td><input type="text" style="text-align:left;" id='pname' name='patname'  value="" class="form-control" required/></td>
		<td><input type="text" style="text-align:left;" id='visitid' name='visit'  value="" class="form-control" required/></td>
		<td><input type="text" style="text-align:left;" id='pid' name='pid'  value="" class="form-control" required/></td>
		
		</tr>
		</table>
			<table class="table table-bordered table-fixed" id="tab_logic">
				<thead>
					<tr class="danger">
						
						<th class="text-left col-sm-3">
							Medicine
						</th>
						<th class="text-left col-sm-2">
							Batch
						</th>
						
						<th class="text-left col-sm-2">
							Schedule H
						</th>
						<th class="text-right col-sm-2">
							Price
						</th>
						<th class="text-right col-sm-1">
							Quantity
						</th>
						<th class="text-right col-sm-2">
							Amount
						</th>
					</tr>
				</thead>
				
				<tbody>

			<?php 
			//include_once('dbconnect.php');
			$exp = date('Y-m-d', strtotime('+1 month'));
			 $qry = "SELECT name, drug_id FROM drugs"; 
			  $result = sqlStatement($qry);
			$i=1;
			while ($jarray = sqlFetchArray($result))
             {
              $rows[] = $jarray;
	
             }
 
            $rowCount = count($rows);
  
                 for($k=0;$k<$rowCount;$k++){
                            $id=$rows[$k]['drug_id'];
						    $id1=str_replace("'", "", $id);
						    $title=$rows[$k]['name'];
						    $title1=str_replace("'", "", $title);
			
                      }
			
			
			while($i<=15){ ?>
					<tr id='addr0'>
						
						<td>
						<div>
				<div>
					
				
			     
               

			
					<select id="<?php echo 'select-tools'.$i ?>" placeholder="Select Medicine" name='name[]' ></select>
				</div>
				
				<script>
				
				// <select id="select-tools"></select>

				$('#<?php echo 'select-tools'.$i ?>').selectize({
					maxItems: 1,
					valueField: 'id',
					labelField: 'title',
					searchField: 'title',
					options: 
                      [
					  
					   <?php
        
                            for($k=0;$k<$rowCount;$k++){
                            $id=$rows[$k]['drug_id'];
						    $id1=str_replace("'", "", $id);
						    $title=$rows[$k]['name'];
						    $title1=str_replace("'", "", $title);
				  
                        ?>  
						{id: '<?php echo $id1; ?>', title: '<?php echo $title1; ?>'},
					<?php } ?>
					],
					create: false
				});
				</script>
			</div>
					</td>
                        
						    <td>
                       <select class="form-control" name="batch[]"  id="<?php echo 'batch'.$i?>">
    
       
                           </select>
 
                          </td>
						  
						 
						  
						  <td>
						<input type="text" id='<?php echo 'schedule_h'.$i ?>' name='schedule_h[]'  value="" class="form-control" readonly />
						</td>
                          						
 					       <td>
						<input type="text" style="text-align:right;" id='<?php echo 'price'.$i ?>' name='price[]'  value="" class="form-control"/>
						</td>
						<td>
						<input type="text" name='qty[]' style="text-align:right;" id="<?php echo 'qty'.$i ?>" class="form-control"/>
						</td>
						
						<td>
						<input type="text" name='sum[]' style="text-align:right;" class="form-control sum" id="<?php echo 'sum'.$i ?>" />
						</td>
						
					</tr>
					<?php $i++; }  ?>
                  
				</tbody>
			</table>
		</div>
	
	
	
	

<div class="col-sm-2">


 <table class="affix">
 <tbody>
 <tr>
 <th class="danger">Subtotal:</th>
 <td>
 <input type="number" style="text-align:right;" class="form-control total" onkeydown="updateNewPrice()" step='0.01'  name="old_price" value=""  /></td>
 </tr>
 <tr>
 <th class="danger">Discount:</th>
 <td>
<input type="number" style="text-align:right;" class="form-control discount" onkeydown="updateNewPrice()" name="discount" value="" placeholder="%" /></td>
 </tr>
 <tr>
 <th class="danger">Total:</th>
 <td>
<input type="number" style="text-align:right;" class="form-control net" step='0.01'   name="new_price" value=""  />
 </td>
 </tr>
  <tr>
 <th class="danger">Mode:</th>
 <td>
<select class="form-control" name='mode' id='payment_mode' onchange='set_rrn()'>
  <option value="cash">Cash Payment</option>
  <option value="card_payment">Card Payment</option>

  
</select>
 </td>
 </tr>
 <tr style="display:none" id='rrn'>
 <th class="danger">RRN :</th>
 <td>
<input type="Text" style="text-align:right;" class="form-control net" name="rrn" value=""  />
 </td>
 </tr>
</tbody>
 </table>
  <input type="submit"  class="btn btn-primary affix" name="submit_val" value="Take Payment" style="margin: 20% 6%;" />
 
</div>
 </div></div>
 </form>
  <script>
$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
 </script>
 </body>
 </html>
