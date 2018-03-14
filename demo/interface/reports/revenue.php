<?php

// Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is a report of sales by item description.  It's driven from
// SQL-Ledger so as to include all types of invoice items.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";


$user = $_SESSION['authUser'];

 

if(isset($_POST['submit']))
  {
	  
	 
	$revenue = $_POST['revenue'];
	$payment = $_POST['payment'];
    $discount = $_POST['discount'];
	$collection = $_POST['collection'];
	$difference = $_POST['difference'];
    $collection_date = $_POST['collection_date']; 
	
	
	 $collection_exist = sqlQuery("select count(collection) as num from revenue where date='$collection_date'"); 
	$col_exist = $collection_exist['num']; 
	?>
	
	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
	.bs-example{
		margin: 20px;
	}
</style>
	
	<?php
	
	
	if($col_exist >=1) {
		sqlStatement("update revenue set collection = '$collection',difference = '$difference', user='$user' where date = '$collection_date' ");
	?>
	
	<div class="bs-example">
    <div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
       <font size="3"> <strong>Success!</strong> Data updated successfully.</font>
    </div>
	
	<?php
	
	}
	 
	else {
	 
	
	 sqlInsert("INSERT INTO revenue(revenue,payment,discount,collection,difference,date,user) values('$revenue','$payment','$discount','$collection',
	 '$difference','$collection_date','$user')");
	 ?>
	 
	 <div class="bs-example">
    <div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <font size="3"> <strong>Success!</strong> Data updated successfully.</font>
    </div>
	<?php
	 
	 
	}
	
  }




function bucks($amount) {
  if ($amount) echo oeFormatMoney($amount);
}
function qescape($str) {
  $str = str_replace('\\', '\\\\', $str);
  return str_replace('"', '\\"', $str);
} 
function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

function thisLineItem($patient_id, $encounter_id, $rowcat, $description, $transdate, $qty, $amount, $pc_catname) {
  global $product, $category, $producttotal, $productqty, $cattotal, $catqty, $grandtotal, $grandqty;
  global $productleft, $catleft;

  //$invnumber = $irnumber ? $irnumber : "$patient_id.$encounter_id";
  $rowamount = sprintf('%01.2f', $amount);
  
  
  

    
?>
 <tr bgcolor="#ddddff">
  <td class="detail">
   <?php echo display_desc($pc_catname); ?>
  </td>
  <td class="detail">
   <?php  echo display_desc($patient_id); ?>
  </td>
  <td align="detail">
   <?php echo $encounter_id; ?>
  </td>
  <td align="right">
   <?php bucks($producttotal); ?>
  </td>
 </tr>
<?php

       // End not csv export
    
    $producttotal = 0;
    $productqty = 0;
    $product = $rowproduct;
    $productleft = $product;
   
  
  $producttotal += $rowamount;
  $cattotal     += $rowamount;
  $grandtotal   += $rowamount;
  $productqty   += $qty;
  $catqty       += $qty;
  $grandqty     += $qty;
} // end function

  if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

  $INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;

  if (!$INTEGRATED_AR) SLConnect();

  $form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
  $form_to_date   = fixDate($_POST['form_from_date']  , date('Y-m-d'));
  $form_facility  = $_POST['form_facility'];

  if ($_POST['form_csvexport']) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=dashboard.csv");
    header("Content-Description: File Transfer");
    // CSV headers:
 
    // CSV headers:
	
    echo '"' .xl('From Date:'). '",';
    echo '"' . text(date('d/M/y',strtotime($form_from_date))) . '",';
    echo '"' .xl(''). '",'; 
	echo '"' .xl('To Date:'). '",';
    echo '"' .text(date('d/M/y',strtotime($form_to_date))). '"'."\n\n";
    echo '"' . xl('Name') . '",';
    echo '"' . xl('Amount') . '"'."\n";
    
	  
    
  } // end export
  else {
?>
<html>
<head>
<?php html_header_show();?>
<style type="text/css">
/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results {
       margin-top: 30px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).on("focus", "#difference", function() {
   var pay = $("#payment").val();
   var col = $("#collection").val();
   var result = (+pay)-(+col);
   $("#difference").val(result);
   
   
   
});
</script>

<title><?php xl('Dashboard','e') ?></title>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Dashboard','e'); ?></span>

<form method='POST' action='' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <td width='630px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<td class='label'>
				<?php xl('Facility','e'); ?>:
			</td>
			<td>
			<?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', true); ?>
			</td>
			<td class='label'>
			   <?php xl('Date','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>
			<!--<td class='label'>
			   <?php xl('To','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>-->
		</tr>
	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#form_csvexport").attr("value",""); $("#theform").submit();'>
					<span>
						<?php xl('Submit','e'); ?>
					</span>
					</a>

					<?php if ($_POST['form_refresh'] || $_POST['form_csvexport']) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php xl('Print','e'); ?>
						</span>
					</a>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value",""); $("#form_csvexport").attr("value","true"); $("#theform").submit();'>
						<span>
							<?php xl('CSV Export','e'); ?>
						</span>
					</a>
					<?php } ?>
				</div>
			</td>
		</tr>
	</table>
  </td>
 </tr>
</table>

</div> <!-- end of parameters -->

<?php
 if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
?>
<div id="report_parameters_daterange">
<table>
<tr>
<tr>
<td><b> <?php xl('From Date:','e'); ?></b></td>
<td><?php echo text(date('d/M/y', strtotime($form_from_date)))?></td>
</tr>
<tr>
<td><b> <?php xl('To Date:','e'); ?></b></td>
<td><?php echo text(date('d/M/y', strtotime($form_to_date)))?></td>
</tr>
</table>
</div>



<div id="report_results">
<table >
 <thead>
  <th><b>
   <?php xl('Name','e'); ?></b>
  </th>
   <th><b>
  <?php xl('Amount','e'); ?></b>
  </th>
 </thead>
<?php
  } // end not export
  }
  if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
    $from_date = $form_from_date;
    $to_date   = $form_to_date;

    $category = "";
    $catleft = "";
    $cattotal = 0;
    $catqty = 0;
    $product = "";
    $productleft = "";
    $producttotal = 0;
    $productqty = 0;
    $grandtotal = 0;
    $grandqty = 0;

    if ($INTEGRATED_AR) {
		
  
		
		$query="SELECT ".
                    "(SELECT SUM(b.fee) FROM billing AS b,form_encounter c".
                       " WHERE b.activity = 1 AND b.code not in ('INSURANCE DIFFERENCE AMOUNT','INSURANCE CO PAYMENT') 
					   and b.code_type not in ('Pharmacy Charge') and c.pid=b.pid and c.encounter=b.encounter ".
                     " AND b.date >= '$from_date 00:00:00' AND b.date <= '$to_date 23:59:59')AS REV,".
                    " (SELECT sum(amount1+amount2) as pay FROM payments a ".
                      " where activity=1 and a.stage not in ('pharm') ".
                     " AND a.dtime>= '$from_date 00:00:00' AND a.dtime <= '$to_date 23:59:59'  ) AS payments, ".
                     " (select sum(adj_amount) from ar_activity d ".
                       " where post_time >='$from_date 00:00:00' and post_time<='$to_date 23:59:59' and memo='Discount' and d.code_type not in ('Pharmacy Charge')) AS discounts " ;
                   /* " (select sum(amount) from vouchers  ".
                     " where posted_date >= '$from_date 00:00:00' and  posted_date<='$to_date 23:59:59') AS expenses"; */
		
      
      //
      $res = sqlStatement($query);
	
      while ($row = sqlFetchArray($res)) {
		  	if ($_POST['form_csvexport']) {
   
    echo '"' .xl('Revenue:'). '",';
	echo '"' .oeFormatMoney($row['REV']) . '"'."\n";
    echo '"' .xl('Payments:'). '",';
	 echo '"' .oeFormatMoney($row['payments']) . '"'."\n";
    echo '"' .xl('Discounts:'). '",';
	 echo '"' . oeFormatMoney($row['discounts']) . '"'."\n";
    echo '"' . xl('Expenses:'). '",';
	echo '"' .oeFormatMoney($row['expenses']) . '"'."\n";
	echo '"' . xl('Margin:'). '",';
	echo '"' .oeFormatMoney($row['REV']-$row['expenses']-$row['discounts']) . '"'."\n";
   } else{?>
	  
<tr bgcolor="#ddddff">
<td class="detail"><b><?php xl('Total Revenue','e'); ?></b></td>
  <td>
  <input type='number' value='<?php echo $row['REV'] ?>' name='revenue' style="text-align:right; width: 8em;" readonly>
   
  </td>
  </tr>
  <tr bgcolor="#ddddff">
  <td class="detail"><b><?php xl('Payments','e'); ?></b></td>
  <td>
  <input type='number' value='<?php echo $row['payments'] ?>' id='payment' name='payment' style="text-align:right; width: 8em;" readonly>
   
  </td>
  </tr>
 
 <tr bgcolor="#ddddff">
  <td class="detail"><b><?php xl('Discounts','e'); ?></b></td>
  <td >
  <input type='number' value='<?php echo $row['discounts'] ?>' name='discount' style="text-align:right; width: 8em;" readonly>
  
  </td>
  </tr>
  <?php  $prev_amt = sqlQuery("select collection,approve from revenue where date='$form_from_date'");  
          $aprv = $prev_amt['approve'];  
  ?>
  
  
  <tr bgcolor="#ddddff">
  <td class="detail"><b><?php xl('Collection','e'); ?></b></td>
  <?php  if($aprv ==1)  { ?>
  <td>
  <input type='number' name='collection' id='collection' value='<?php echo $prev_amt['collection']; ?>' style="text-align:right; width: 8em;" required readonly>
  </td>
  <?php  }  else {  ?>
  
  <td>
  <input type='number' name='collection' id='collection' value='<?php echo $prev_amt['collection']; ?>' style="text-align:right; width: 8em;" required >
  </td>
  <?php  }  ?>
 </tr>
  <tr bgcolor="#ddddff">
  <td class="detail"><b><?php xl('Difference','e'); ?></b></td>
  <td>
   <input type='number' name='difference' id='difference' value='' style="text-align:right; width: 8em;" required>
  </td>
 </tr>
 <tr><th><input type='hidden' name='collection_date' value='<?php echo $form_from_date ?>'> </th></tr>
 <tr><th><input type='submit' name='submit' value='Save'></th></tr>
 
       
     <?php
 $total1 += $row['fee']; 	
 }
	  }     
  
    }
    if (! $_POST['form_csvexport']){
?>



<!-- <tr>
  <td class="detail" colspan="2">
   <?php xl('Grand Total','e'); ?>
  </td>
  <td align="detail">
  </td>
  <td align="right">
   <?php bucks($total1); ?>
  </td>
 </tr> -->

<?php

    }
  }
  if (!$INTEGRATED_AR) SLClose();

  if (! $_POST['form_csvexport']) {if($_POST['form_refresh']){
?>

</table>
</div> <!-- report results -->
<?php } else { ?>
<div class='text'>
 	<?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>

</form>

</body>

<!-- stuff for the popup calendar -->
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 //Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>


</html>
<?php
  } // End not csv export
?>
