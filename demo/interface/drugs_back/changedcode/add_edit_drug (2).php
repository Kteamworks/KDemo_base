<?php

 // Copyright (C) 2006-2011 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

$sanitize_all_escapes  = true;
$fake_register_globals = false;

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("drugs.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 $alertmsg = '';
 $drug_id = $_REQUEST['drug'];
 $info_msg = "";
 $tmpl_line_no = 0;

 if (!acl_check('admin', 'drugs')) die(xlt('Not authorized'));

// Format dollars for display.
//
function bucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    if ($amount != 0.00) return $amount;
  }
  return '';
}

// Write a line of data for one template to the form.
//
function writeTemplateLine($selector, $dosage, $period, $quantity, $refills, $prices, $taxrates) {
  global $tmpl_line_no;
  ++$tmpl_line_no;

  echo " <tr>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][selector]' value='" . attr($selector) . "' size='8' maxlength='100'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][dosage]' value='" . attr($dosage) . "' size='6' maxlength='10'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  generate_form_field(array(
    'data_type'   => 1,
    'field_id'    => 'tmpl[' . $tmpl_line_no . '][period]',
    'list_id'     => 'drug_interval',
    'empty_title' => 'SKIP'
    ), $period);
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][quantity]' value='" . attr($quantity) . "' size='3' maxlength='7'>";
  echo "</td>\n";
  echo "  <td class='tmplcell drugsonly'>";
  echo "<input type='text' name='form_tmpl[$tmpl_line_no][refills]' value='" . attr($refills) . "' size='3' maxlength='5'>";
  echo "</td>\n";
  foreach ($prices as $pricelevel => $price) {
    echo "  <td class='tmplcell'>";
    echo "<input type='text' name='form_tmpl[$tmpl_line_no][price][" . attr($pricelevel) . "]' value='" . attr($price) . "' size='6' maxlength='12'>";
    echo "</td>\n";
  }
  $pres = sqlStatement("SELECT option_id FROM list_options " .
    "WHERE list_id = 'taxrate' ORDER BY seq");
  while ($prow = sqlFetchArray($pres)) {
    echo "  <td class='tmplcell'>";
    echo "<input type='checkbox' name='form_tmpl[$tmpl_line_no][taxrate][" . attr($prow['option_id']) . "]' value='1'";
    if (strpos(":$taxrates", $prow['option_id']) !== false) echo " checked";
    echo " /></td>\n";
  }
  echo " </tr>\n";
}

// Translation for form fields used in SQL queries.
//
function escapedff($name) {
  return add_escape_custom(trim($_POST[$name]));
}
function numericff($name) {
  $field = trim($_POST[$name]) + 0;
  return add_escape_custom($field);
}
?>
<html>
<head>
        
        <link rel="stylesheet" href="public/css/default.css" type="text/css">
        <link rel="stylesheet" href="datepicker/public/css/style.css" type="text/css">
		<link type="text/css" rel="stylesheet" href="datepicker/libraries/syntaxhighlighter/public/css/shCoreDefault.css">

<?php html_header_show(); ?>
<title><?php echo $drug_id ? xlt("Edit") : xlt("Add New"); echo ' ' . xlt('Drug'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>

 



input[class=rgt] { text-align:right }

td { font-size:10pt; }


input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;

}


<?php if ($GLOBALS['sell_non_drug_products'] == 2) { ?>
.drugsonly { display:none; }
<?php } else { ?>
.drugsonly { }
<?php } ?>

<?php if (empty($GLOBALS['ippf_specific'])) { ?>
.ippfonly { display:none; }
<?php } else { ?>
.ippfonly { }
<?php } ?>




</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f.form_related_code.value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f.form_related_code.value = s;
}

// This invokes the find-code popup.
function sel_related() {
 dlgopen('../patient_file/encounter/find_code_popup.php', '_blank', 500, 400);
}



</script>





</head>

<body class="body_top">
<?php
// If we are saving, then save and close the window.
// First check for duplicates.
if ($_POST['sbtrow']) {
session_start();
//$_SESSION['row']=$_POST['trr'];
//$tableRow=$_SESSION['row'];
$tableRow=$_POST['trr'];

}
//
if ($_POST['form_save']) {
	
	//session_start();
 //echo $supply=$_SESSION['supplier']=$_POST['supplier'];

 
	
  $crow = sqlQuery("SELECT COUNT(*) AS count FROM drugs WHERE " .
    "name = '"  . escapedff('form_name')  . "' AND " .
    "form = '"  . escapedff('form_form')  . "' AND " .
    "size = '"  . escapedff('form_size')  . "' AND " .
    "unit = '"  . escapedff('form_unit')  . "' AND " .
    "route = '" . escapedff('form_route') . "' AND " .
    "drug_id != ?", array($drug_id));
	
	 if ($crow['count']) {
    $alertmsg = addslashes(xl('Cannot add this entry because it already exists!'));
	
  }
}

if (($_POST['form_save'] || $_POST['form_delete']) && !$alertmsg) {
  $new_drug = false;
  if ($drug_id) {
   if ($_POST['form_save']) { // updating an existing drug
    sqlStatement("UPDATE drugs SET " .
     "name = '"           . escapedff('form_name')          . "', " .
     "ndc_number = '"     . escapedff('mfr')    . "', " .
     "on_order = '"       . escapedff('form_on_order')      . "', " .
     "reorder_point = '"  . escapedff('form_reorder_point') . "', " .
     "max_level = '"      . escapedff('form_max_level')     . "', " .
     "form = '"           . escapedff('form_form')          . "', " .
     "size = '"           . escapedff('form_size')          . "', " .
     "unit = '"           . escapedff('form_unit')          . "', " .
     "route = '"          . escapedff('form_route')         . "', " .
     "cyp_factor = '"     . numericff('form_cyp_factor')    . "', " .
     "related_code = '"   . escapedff('form_related_code')  . "', " .
     "allow_multiple = "  . (empty($_POST['form_allow_multiple' ]) ? 0 : 1) . ", " .
     "allow_combining = " . (empty($_POST['form_allow_combining']) ? 0 : 1) . ", " .
     "active = "          . (empty($_POST['form_active']) ? 0 : 1) . " " .
     "WHERE drug_id = ?", array($drug_id));
    sqlStatement("DELETE FROM drug_templates WHERE drug_id = ?", array($drug_id));
   }
   else { // deleting
    if (acl_check('admin', 'super')) {
     sqlStatement("DELETE FROM drug_inventory WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drug_templates WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drugs WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM prices WHERE pr_id = ? AND pr_selector != ''", array($drug_id));
    }
   }
  }
  else if ($_POST['form_save']) { // saving a new drug
   $new_drug = true;
   
         if(isset($_POST['supplier'])&& $_POST['supplier']!=""){
			 
			  $sup=$_POST['supplier'];
			  $invoice= $_POST['invoice'];
			  
			  $j=0;
	foreach($_POST['form_name'] as $selected){
		
		$batch= $_POST['batch'][$j];
		$date= $_POST['date'][$j];
		$qty= $_POST['qty'][$j];
		$pack= $_POST['pack'][$j];
		$mrp= $_POST['mrp'][$j];
		$mfr= $_POST['mfr'][$j];
		$instock= $_POST['instock'][$j];
		$trade= $_POST['trade'][$j];
		$discount= $_POST['discount'][$j];
		$vat= $_POST['vat'][$j];
		$total= $_POST['total'][$j];
		$type= $_POST['type'][$j];
		$unitPrice= $mrp/$pack;
	
		$free= $_POST['free'][$j];
        if(empty($selected))
			continue;
		
		
			 $drug_id = sqlInsert("INSERT INTO drugs ( " .
    "name,mfr,inStock,supplier,batch,quantity,free,pack,packType,expdate,mrp,PricePerUnit,tradePrice,discount,vat,totalValue,invoice,max_level, form, " .
    "size, unit, route, cyp_factor, related_code, " .
    "allow_multiple, allow_combining, active " .
    ") VALUES ( " .
    "'" . $selected          . "', " .
	    "'" . $mfr          . "', " .
		  "'" . $instock          . "', " .
	 "'" .$sup. "', " .
    "'" . $batch    . "', " .
    "'" . $qty      . "', " .
	 "'" . $free      . "', " .
	"'" . $pack      . "', " .
	"'" . $type     . "', " .
    "'" . $date . "', " .
	 "'" . $mrp . "', " .
	  "'" . $unitPrice . "', " .
	  "'" . $trade . "', " .
	   "'" . $discount . "', " .
	    "'" . $vat . "', " .
		 "'" . $total . "', " .
		  "'" . $invoice . "', " .
    "'" . escapedff('form_max_level')     . "', " .
    "'" . escapedff('form_form')          . "', " .
    "'" . escapedff('form_size')          . "', " .
    "'" . escapedff('form_unit')          . "', " .
    "'" . escapedff('form_route')         . "', " .
    "'" . numericff('form_cyp_factor')    . "', " .
    "'" . escapedff('form_related_code')  . "', " .
    (empty($_POST['form_allow_multiple' ]) ? 0 : 1) . ", " .
    (empty($_POST['form_allow_combining']) ? 0 : 1) . ", " .
    (empty($_POST['form_active']) ? 0 : 1)        .
    ")");
	
//---------------------------Drugs_Inventory--------------------------------------------//

 $lot_id = sqlInsert("INSERT INTO drug_inventory ( " .
          "drug_id, lot_number, manufacturer, expiration, " .
          "vendor_id, warehouse_id, on_hand " .
          ") VALUES ( " .
          "'" . add_escape_custom($drug_id) . "', "                            .
          "'" . $batch   . "', " .
          "'" . $mfr . "', " .
         "'" . $date . "', " .
          "'" . add_escape_custom($_POST['form_vendor_id'])    . "', " .
          "'onsite', " .
          "'" . $qty              . "' "  .
          ")");	
	//------------------------------------------product_warehouse-------------------------------//
	
	$warehouse = 	sqlInsert("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', "                            .
          "'onsite ', " .
          "' 100', " .
         "' 10000' " .
         ")");	
		 
		 
		 $quantity = $qty + $free;
	//---------------------------------------------drug_sales---------------------------------//	 
		 
		       sqlInsert("INSERT INTO drug_sales ( " .
        "drug_id, inventory_id, prescription_id, pid, encounter, user, " .
        "sale_date, quantity, fee, xfer_inventory_id, distributor_id,rate,free,pack,vat,notes " .
        ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . add_escape_custom($lot_id) . "', '0', '0', '0', " .
        "'" . add_escape_custom($_SESSION['authUser']) . "', " .
        "'" . date('Y/m/d') . "', " .
        "'" . add_escape_custom(0 - $quantity)  . "', " .
        "'" . add_escape_custom(0 - $total)      . "', " .
        "'" . add_escape_custom($form_source_lot) . "', " .
        "'" . add_escape_custom($form_distributor_id) . "', " .
		 "'" . $total . "', " .
		 "'" . $free . "', " .
		 "'" . $pack . "', " .
		 "'" . $vat . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		 
	//------------------------------------drug_templates------------------------------------//	
  
         $quantity = $qty + $free;
		 
		 sqlInsert("INSERT INTO drug_templates ( " .
      "drug_id, selector,quantity,taxrates " .
      ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . $selected . "', " .
		 "'" . $quantity . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		 
		 
		 
	
	
	
	
	
	
	
	$j++;
		}  
		
  }
		else{
			
			$invoice= $_POST['invoice'];
			
			 $j=0;
	foreach($_POST['form_name'] as $selected) {
		
		$batch= $_POST['batch'][$j];
		$date= $_POST['date'][$j];
		$qty= $_POST['qty'][$j];
		$pack= $_POST['pack'][$j];
		$mrp= $_POST['mrp'][$j];
		$mfr= $_POST['mfr'][$j];
		$instock= $_POST['instock'][$j];
		$trade= $_POST['trade'][$j];
		$discount= $_POST['discount'][$j];
		$vat= $_POST['vat'][$j];
		$total= $_POST['total'][$j];
		$type= $_POST['type'][$j];
		$unitPrice= $mrp/$pack;

		$free= $_POST['free'][$j];
			
			if(empty($selected))
			continue;
			
			 $drug_id = sqlInsert("INSERT INTO drugs ( " .
    "name,mfr,inStock,supplier,batch,quantity,free,pack,packType,expdate,mrp,PricePerUnit,tradePrice,discount,vat,totalValue,invoice,max_level, form, " .
    "size, unit, route, cyp_factor, related_code, " .
    "allow_multiple, allow_combining, active " .
    ") VALUES ( " .
    "'" . $selected          . "', " .
	"'" . $mfr          . "', " .
	"'" . $instock          . "', " .
	 "'" .escapedff('form_supplier'). "', " .
    "'" . $batch    . "', " .
    "'" . $qty      . "', " .
	"'" . $free      . "', " .
	 "'" . $pack      . "', " .
	 "'" . $type      . "', " .
    "'" . $date . "', " .
	 "'" . $mrp . "', " .
	 "'" . $unitPrice . "', " .
	  "'" . $trade . "', " .
	   "'" . $discount . "', " .
	    "'" . $vat . "', " .
		 "'" . $total . "', " .
		  "'" . $invoice . "', " .
    "'" . escapedff('form_max_level')     . "', " .
    "'" . escapedff('form_form')          . "', " .
    "'" . escapedff('form_size')          . "', " .
    "'" . escapedff('form_unit')          . "', " .
    "'" . escapedff('form_route')         . "', " .
    "'" . numericff('form_cyp_factor')    . "', " .
    "'" . escapedff('form_related_code')  . "', " .
    (empty($_POST['form_allow_multiple' ]) ? 0 : 1) . ", " .
    (empty($_POST['form_allow_combining']) ? 0 : 1) . ", " .
    (empty($_POST['form_active']) ? 0 : 1)        .
    ")");
	
	//----------------------------------Drugs_warehouse---------------------------------------//
	
	
	
	 $warehouse = 	sqlInsert("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', "                            .
          "'onsite ', " .
          "' 100', " .
         "' 10000' " .
         ")");	
	
//--------------------------------------------drug_inventory-----------------------------------------//
	
	$lot_id = sqlInsert("INSERT INTO drug_inventory ( " .
          "drug_id, lot_number, manufacturer, expiration, " .
          "vendor_id, warehouse_id, on_hand " .
          ") VALUES ( " .
          "'" . add_escape_custom($drug_id) . "', "                            .
          "'" . $batch. "', " .
          "'" . $mfr . "', " .
         "'" . $date . "', " .
          "'" . add_escape_custom($_POST['form_vendor_id'])    . "', " .
          "' onsite ', " .
          "'" . $qty              . "' "  .
          ")");	
		  
		  
		 $quantity = $qty + $free; 
	//-----------------------------Drug_sales------------------------------------------------------//	
		 sqlInsert("INSERT INTO drug_sales ( " .
        "drug_id, inventory_id, prescription_id, pid, encounter, user, " .
        "sale_date, quantity, fee, xfer_inventory_id, distributor_id,rate,free,pack,vat,notes " .
        ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . add_escape_custom($lot_id) . "', '0', '0', '0', " .
        "'" . add_escape_custom($_SESSION['authUser']) . "', " .
        "'" . date('Y/m/d') . "', " .
        "'" . add_escape_custom(0 - $quantity)  . "', " .
        "'" . add_escape_custom(0 - $total)      . "', " .
        "'" . add_escape_custom($form_source_lot) . "', " .
        "'" . add_escape_custom($form_distributor_id) . "', " .
		 "'" . $total . "', " .
		 "'" . $free . "', " .
		 "'" . $pack . "', " .
		 "'" . $vat . "', " .
        "'" . add_escape_custom($form_notes) . "' )");

		//-----------------------------drug_templates---------------------------------//
		
		
		
		
		 sqlInsert("INSERT INTO drug_templates ( " .
      "drug_id, selector,quantity,taxrates " .
      ") VALUES ( " .
        "'" . add_escape_custom($drug_id) . "', " .
        "'" . $selected . "', " .
		 "'" . $quantity . "', " .
        "'" . add_escape_custom($form_notes) . "' )");
		  

		  
	
	$j++;
		}
			
  }
   
	/*-----logic for unique primary key stats--------*/
	
	 
	 $test = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier' order by CONVERT(SUBSTRING(option_id, 1), SIGNED INTEGER) desc limit 1");
 while($test1 = sqlFetchArray($test)){

 $test2=$test1['option_id'];
 }
  $test3= $test2+1;
  
	/*-------logic Ends-------------------------------*/
	
	
	
   if(isset($_POST['supplier'])&& $_POST['supplier']!=""){
			 
			  $sup=$_POST['supplier'];
			
			  
	$valid = sqlQuery("SELECT title FROM list_options WHERE " .
"title = '"  . $sup  . "' " );

   if(!$valid)	
   {	   
		echo "value";	  
   
	$supply_id = sqlInsert("INSERT INTO list_options ( " .
    "list_id,title,option_id " .
    ") VALUES ( " .
    "'" . 'drug_supplier'          . "', " .
	 "'"  . $sup ."', " .
	  "'" . $test3          . "' " .
    ")");
	
   }
   }
   else{
	   
	    
		$valid = sqlQuery("SELECT title FROM list_options WHERE " .
"title = '"  . escapedff('form_supplier')  . "' " );

  
  
        if(!$valid)
		{
	       
		 
	   $supply_id = sqlInsert("INSERT INTO list_options ( " .
    "list_id,title,option_id " .
    ") VALUES ( " .
    "'" . 'drug_supplier'          . "', " .
	 "'"  . escapedff('form_supplier') ."', " .
	  "'" . $test3          . "' " .
    ")");
	   
		}
   }
	
	
	
	
	
  }

  if ($_POST['form_save'] && $drug_id) {
	  
    $tmpl = $_POST['form_tmpl']; 
   // If using the simplified drug form, then force the one and only
   // selector name to be the same as the product name.
   if ($GLOBALS['sell_non_drug_products'] == 2) {
    $tmpl["1"]['selector'] = $_POST['form_name'];
   }
   
   sqlStatement("DELETE FROM prices WHERE pr_id = ? AND pr_selector != ''", array($drug_id));
   for ($lino = 1; isset($tmpl["$lino"]['selector']); ++$lino) {
    $iter = $tmpl["$lino"];
    $selector = trim($iter['selector']);
    if ($selector) {
     $taxrates = "";
     if (!empty($iter['taxrate'])) {
      foreach ($iter['taxrate'] as $key => $value) {
       $taxrates .= "$key:";
      }
     }
     

     // Add prices for this drug ID and selector.
     foreach ($iter['price'] as $key => $value) {
      $value = $value + 0;
      if ($value) {
        sqlStatement("INSERT INTO prices ( " .
          "pr_id, pr_selector, pr_level, pr_price ) VALUES ( " .
          "?, ?, ?, ? )",
          array($drug_id, $selector, $key, $value));
      }
     } // end foreach price
    } // end if selector is present
   } // end for each selector
   // Save warehouse-specific mins and maxes for this drug.
  // sqlStatement("DELETE FROM product_warehouse WHERE pw_drug_id = ?", array($drug_id));
   foreach ($_POST['form_wh_min'] as $whid => $whmin) {
    $whmin = 0 + $whmin;
    $whmax = 0 + $_POST['form_wh_max'][$whid];
    if ($whmin != 0 || $whmax != 0) {
      sqlStatement("INSERT INTO product_warehouse ( " .
        "pw_drug_id, pw_warehouse, pw_min_level, pw_max_level ) VALUES ( " .
        "?, ?, ?, ? )", array($drug_id, $whid, $whmin, $whmax));
    }
   }
  } // end if saving a drug

  // Close this window and redisplay the updated list of drugs.
  //
  echo "<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('$info_msg');\n";
  echo " if (opener.refreshme) opener.refreshme();\n";
  if ($new_drug) {
	  
	 // echo " window.location.href='add_edit_drug.php?drug=$drug_id&lot=0'\n";
	  echo " window.close();\n";
  } else {
   echo " window.close();\n";
  }
  echo "</script></body></html>\n";
  exit();
}

if ($drug_id) {
  $row = sqlQuery("SELECT * FROM drugs WHERE drug_id = ?", array($drug_id));
  $tres = sqlStatement("SELECT * FROM drug_templates WHERE " .
   "drug_id = ? ORDER BY selector", array($drug_id));
}
else {
  $row = array(
    'name' => '',
    'active' => '1',
    'allow_multiple' => '1',
    'allow_combining' => '',
    'ndc_number' => '',
    'on_order' => '0',
    'reorder_point' => '0',
    'max_level' => '0',
    'form' => '',
    'size' => '',
    'unit' => '',
    'route' => '',
	'supplier' => '',
    'cyp_factor' => '',
    'related_code' => '',
  );
}
?>

<form method="post" name="row" action="" >

<center>
<table  width='50%'  cellspacing="10" style="border: 1px solid black;">
<tr> <th>
<input type="text" name="trr" placeholder="Enter Number of Rows" size="50" pattern="\d*"></th> <th> <input type="submit" value="Enter" name="sbtrow"></th>
</tr>
</center>
</table>


</form>



<form method='post' name='theform' action='add_edit_drug.php?drug=<?php echo $drug_id; ?>'>
<center>


	
<table  width='50%'  cellspacing="10" style="border: 1px solid black;">

 <tr>
 <td width="20%"  valign='top' nowrap><b><?php echo xlt('Select Supplier'); ?>:</b></td>
	
<td>
 <select style="width:100%;height:2em;"  name="supplier">
    
       <option value="">Select</option>
    <?php
         $list = sqlStatement("SELECT  * FROM `list_options` WHERE `list_id`='drug_supplier'");
 while($list1 = sqlFetchArray($list)){

         
 
   
          ?> 
            <option value="<?php echo $list1['title'];?><?php if($list1['title']==$supply) echo "selected"; ?>"> <?php echo $list1['title'];?></option>
       <?php   }?>
 
    </select>
 
 </td>


 
 
  <tr>
  <td valign='top' width="20%" nowrap><b><?php echo xlt('Add Supplier'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_supplier' value="<?php echo $supply;?>" maxlength='80' style='width:100%' placeholder="Enter Supplier if it is not in Dropdown List" />
  </td>
 </tr>
 
 <tr>
  <td valign='top' width="20%" nowrap><b><?php echo xlt('Invoice Number'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='invoice' value="" maxlength='80' style='width:100%' placeholder="Please Enter Invoice Number" required/>
  </td>
 </tr>
 
</table>
 
 <br><br><br><br><br><br>
 
 <table border='0' width='100%'   id="dataTable" style=" border: 1px solid black;"> 
 <tr>
  <th nowrap>S.No.</th>
  <th nowrap>Name</th>
   <th nowrap>Manufacturer</th>
   <th nowrap>In </br>Stock</th>
   <th  nowrap>Batch </br>Number</th>
  <th  nowrap>Quantity</th>
  <th  nowrap>Free</th>
  <th  nowrap>Pack</th>
  <th  nowrap>Pack </br>Type</th>
  <th  nowrap>Expiry </br>Date</th>
   <th  nowrap>M.R.P.</th>
   <th  nowrap>Trade</br>Price</th>
   <th  nowrap>Discount</th>
    <th  nowrap>Vat %</th>
	 <th  nowrap>Net Value</th>
 </tr>
 
 
 
 <?php
 $i=1;
  while($i<=$tableRow) 
  {
  
     
?> 
 
 
 
 <tr>
 
   <td>
   <?php echo $i ?>
  </td> 
  <td>
   <input type='text'  name='form_name[]' maxlength='80' value='' style='width:100%' placeholder="Medicine" />
  </td>
  
  
  <td>
  <!-- <input type='text' name='mfr[]' maxlength='80' value='' style='width:100%' placeholder="Manufacturer Name" />-->
   <input type="text" name="mfr[]" class="typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Manufacturer">
  </td>
  
  
  
  <td>
   <input type='text' size="10" name='instock[]' maxlength='80' value='' class="rgt" style='width:100%' placeholder="interger" pattern="\d*"/>
  </td>
  
  <td>
   <input type='text' name='batch[]' maxlength='80' value='' style='width:100%' placeholder="Batch Number" />
  </td>
  
  <td>
   <input type='text' size="10" name='qty[]' maxlength='80' value='' class="rgt" style='width:100%'  pattern="\d*" placeholder="Integer" />
  </td>
  
  
  <td>
   <input type='text' size="10" name='free[]' maxlength='80' value='' class="rgt" style='width:100%' pattern="\d*" placeholder="interger" />
  </td>
  
  <td>
   <input type='text' size="10" name='pack[]' maxlength='80'  class="rgt" style='width:100%'   placeholder="interger" />
  </td>
  
   <td>
 <input type='text' size="10" name='type[]' maxlength='80'  class="rgt" style='width:100%'   placeholder="Type" />
 </td>
  
  
  <td>
   <input type="text" size="10" name="date[]" value="" class="datepicker-example11" placeholder="MM-YYYY" />
  </td>
  
  <td>
  <input type='text' size="10" name='mrp[]' maxlength='80' value='' class="rgt" style='width:100%'  placeholder="00.00"/>
  </td>
  
  <td>
  <input type='text' size="10" name='trade[]' maxlength='80' value='' class="rgt" style='width:100%'  placeholder="00.00" />
   
  </td>
  
  <td>
  <input type='text' size="10" name='discount[]' maxlength='80' value='' class="rgt" style='width:100%'  placeholder="00.00%"  />
  </td>
  
  <td>
 
 <select name="vat[]">
  <option value="0%">0%</option>
  <option value="5.50%">5.50%</option>
  <option value="14.50%">14.50%</option>
  
</select> 
 
  </td>
  
  <td>
  <input type='number' size='10' name='total[]' maxlength='80' value='' class="rgt" style='width:100%'  placeholder="00.00" />
  </td>
  
  </tr>
  
  <?php
   

 $i++;  

         
}
 

?>

  
 
  
  
  
 
 
<!--
 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('Drug Name'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_name' maxlength='80' value='<--?php echo attr($row['name']) ?>' style='width:100%' />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('Active'); ?>:</b></td>
  <td>
   <input type='checkbox' name='form_active' value='1'<--?php if ($row['active']) echo ' checked'; ?> />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('Allow'); ?>:</b></td>
  <td>
   <input type='checkbox' name='form_allow_multiple' value='1'<--?php if ($row['allow_multiple']) echo ' checked'; ?> />
   <--?php echo xlt('Multiple Lots'); ?> &nbsp;
   <input type='checkbox' name='form_allow_combining' value='1'<--?php if ($row['allow_combining']) echo ' checked'; ?> />
   <--?php echo xlt('Combining Lots'); ?>
  </td>
 </tr>
-->
 <!--tr>
  <td valign='top' nowrap><b><--?php echo xlt('NDC Number'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_ndc_number' maxlength='20'
    value='<!--?php echo attr($row['ndc_number']) ?>' style='width:100%'
    onkeyup='maskkeyup(this,"<--?php echo addslashes($GLOBALS['gbl_mask_product_id']); ?>")'
    onblur='maskblur(this,"<--?php echo addslashes($GLOBALS['gbl_mask_product_id']); ?>")'
    />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('On Order'); ?>:</b></td>
  <td>
   <input type='text' size='5' name='form_on_order' maxlength='7' value='<--?php echo attr($row['on_order']) ?>' />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('Limits'); ?>:</b></td>
  <td>
   <table>
    <tr>
     <td valign='top' nowrap>&nbsp;</td>
     <td valign='top' nowrap><--?php echo xlt('Global'); ?></td>
<--?php
  // One column header per warehouse title.
  $pwarr = array();
  $pwres = sqlStatement("SELECT lo.option_id, lo.title, " .
    "pw.pw_min_level, pw.pw_max_level " .
    "FROM list_options AS lo " .
    "LEFT JOIN product_warehouse AS pw ON " .
    "pw.pw_drug_id = ? AND " .
    "pw.pw_warehouse = lo.option_id WHERE " .
    "lo.list_id = 'warehouse' ORDER BY lo.seq, lo.title",
    array($drug_id));
  while ($pwrow = sqlFetchArray($pwres)) {
    $pwarr[] = $pwrow;
    echo "     <td valign='top' nowrap>" .
      text($pwrow['title']) . "</td>\n";
  }
?>
    </tr>
    <tr>
     <td valign='top' nowrap><--?php echo xlt('Min'); ?>&nbsp;</td>
     <td valign='top'>
      <input type='text' size='5' name='form_reorder_point' maxlength='7'
       value='<----?php echo attr($row['reorder_point']) ?>'
       title='<--?php echo xla('Reorder point, 0 if not applicable'); ?>'
       />&nbsp;&nbsp;
     </td>
<--?php
  foreach ($pwarr as $pwrow) {
    echo "     <td valign='top'>";
    echo "<input type='text' name='form_wh_min[" .
      attr($pwrow['option_id']) .
      "]' value='" . attr(0 + $pwrow['pw_min_level']) . "' size='5' " .
      "title='" . xla('Warehouse minimum, 0 if not applicable') . "' />";
    echo "&nbsp;&nbsp;</td>\n";
  }
?>
    </tr>
    <tr>
     <td valign='top' nowrap><--?php echo xlt('Max'); ?>&nbsp;</td>
     <td>
      <input type='text' size='5' name='form_max_level' maxlength='7'
       value='<--?php echo attr($row['max_level']) ?>'
       title='<--?php echo xla('Maximum reasonable inventory, 0 if not applicable'); ?>'
       />
     </td>
<--?php
  foreach ($pwarr as $pwrow) {
    echo "     <td valign='top'>";
    echo "<input type='text' name='form_wh_max[" .
      htmlspecialchars($pwrow['option_id']) .
      "]' value='" . attr(0 + $pwrow['pw_max_level']) . "' size='5' " .
      "title='" . xla('Warehouse maximum, 0 if not applicable') . "' />";
    echo "</td>\n";
  }
?>
    </tr>
   </table>
  </td>
 </tr>

 <tr class='drugsonly'>
  <td valign='top' nowrap><b><--?php echo xlt('Form'); ?>:</b></td>
  <td>
<--?php
 generate_form_field(array('data_type'=>1,'field_id'=>'form','list_id'=>'drug_form','empty_title'=>'SKIP'), $row['form']);
?>
  </td>
 </tr>

 <tr class='drugsonly'>
  <td valign='top' nowrap><b><--?php echo xlt('Pill Size'); ?>:</b></td>
  <td>
   <input type='text' size='5' name='form_size' maxlength='7' value='<--?php echo attr($row['size']) ?>' />
  </td>
 </tr>

 <tr class='drugsonly'>
  <td valign='top' nowrap><b><--?php echo xlt('Units'); ?>:</b></td>
  <td>
<--?php
 generate_form_field(array('data_type'=>1,'field_id'=>'unit','list_id'=>'drug_units','empty_title'=>'SKIP'), $row['unit']);
?>
  </td>
 </tr>

 
 
 <tr class='drugsonly'>
  <td valign='top' nowrap><b><--?php echo xlt('Route'); ?>:</b></td>
  <td>
<--?php
 generate_form_field(array('data_type'=>1,'field_id'=>'route','list_id'=>'drug_route','empty_title'=>'SKIP'), $row['route']);
?>
  </td>
 </tr>

 <tr class='ippfonly'>
  <td valign='top' nowrap><b><--?php echo xlt('CYP Factor'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_cyp_factor' maxlength='20' value='<--?php echo attr($row['cyp_factor']) ?>' />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><--?php echo xlt('Relate To'); ?>:</b></td>
  <td>
   <input type='text' size='50' name='form_related_code'
    value='<--?php echo attr($row['related_code']) ?>' onclick='sel_related()'
    title='<--?php echo xla('Click to select related code'); ?>'
    style='width:100%' readonly />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap>
   <b><--?php echo $GLOBALS['sell_non_drug_products'] == 2 ? xlt('Fees') : xlt('Templates'); ?>:</b>
  </td>
  <td>
   <table border='0' width='100%'>
    <tr>
     <td class='drugsonly'><b><--?php echo xlt('Name'    ); ?></b></td>
     <td class='drugsonly'><b><--?php echo xlt('Schedule'); ?></b></td>
     <td class='drugsonly'><b><--?php echo xlt('Interval'); ?></b></td>
     <td class='drugsonly'><b><--?php echo xlt('Qty'     ); ?></b></td>
     <td class='drugsonly'><b><--?php echo xlt('Refills' ); ?></b></td>
<--?php
  // Show a heading for each price level.  Also create an array of prices
  // for new template lines.
  $emptyPrices = array();
  $pres = sqlStatement("SELECT option_id, title FROM list_options " .
    "WHERE list_id = 'pricelevel' ORDER BY seq");
  while ($prow = sqlFetchArray($pres)) {
    $emptyPrices[$prow['option_id']] = '';
    echo "     <td><b>" .
	 generate_display_field(array('data_type'=>'1','list_id'=>'pricelevel'), $prow['option_id']) .
	 "</b></td>\n";
  }
  // Show a heading for each tax rate.
  $pres = sqlStatement("SELECT option_id, title FROM list_options " .
    "WHERE list_id = 'taxrate' ORDER BY seq");
  while ($prow = sqlFetchArray($pres)) {
    echo "     <td><b>" .
	 generate_display_field(array('data_type'=>'1','list_id'=>'taxrate'), $prow['option_id']) .
	 "</b></td>\n";
  }
?>
    </tr>
<--?php
  $blank_lines = $GLOBALS['sell_non_drug_products'] == 2 ? 1 : 3;
  if ($tres) {
    while ($trow = sqlFetchArray($tres)) {
      $blank_lines = $GLOBALS['sell_non_drug_products'] == 2 ? 0 : 1;
      $selector = $trow['selector'];
      // Get array of prices.
      $prices = array();
      $pres = sqlStatement("SELECT lo.option_id, p.pr_price " .
        "FROM list_options AS lo LEFT OUTER JOIN prices AS p ON " .
        "p.pr_id = ? AND p.pr_selector = ? AND " .
        "p.pr_level = lo.option_id " .
        "WHERE list_id = 'pricelevel' ORDER BY lo.seq",
        array($drug_id, $selector));
      while ($prow = sqlFetchArray($pres)) {
        $prices[$prow['option_id']] = $prow['pr_price'];
      }
      writeTemplateLine($selector, $trow['dosage'], $trow['period'],
        $trow['quantity'], $trow['refills'], $prices, $trow['taxrates']);
    }
  }
  for ($i = 0; $i < $blank_lines; ++$i) {
    $selector = $GLOBALS['sell_non_drug_products'] == 2 ? $row['name'] : '';
    writeTemplateLine($selector, '', '', '', '', $emptyPrices, '');
  }
?> -->
   </table>
 

<p>
<!--
<--INPUT type="button" value="Add Row" onclick="addRow('dataTable')" /> -->
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;
<input type='submit' name='form_delete' value='<?php echo xla('Delete'); ?>' style='color:red' />
<?php } ?>

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />

</p>

</center>
</form>

 <script type="text/javascript" src="datepicker/public/javascript/jquery-1.12.0.js"></script>
        <script type="text/javascript" src="public/javascript/zebra_datepicker.js"></script>
        <script type="text/javascript" src="datepicker/public/javascript/core.js"></script>



 <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/XRegExp.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shCore.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shLegacy.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shBrushJScript.js"></script>
        <script type="text/javascript" src="datepicker/libraries/syntaxhighlighter/public/javascript/shBrushXML.js"></script>

        <script type="text/javascript">
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
        </script>
		
		
		
		

  
    <script src="typeahead.min.js"></script>
    <script>
    $(document).ready(function(){
    $('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'search.php?key=%QUERY',
        limit : 10
    });
});
    </script>
    <style type="text/css">


.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-dropdown-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}
.tt-suggestion {
	font-size: 24px;
	line-height: 24px;
	padding: 3px 20px;
}
.tt-suggestion.tt-is-under-cursor {
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>		
		
		



<div width="100%">		
<script language="JavaScript">

/*
var arraylist = ['0%', '05.50%', '14.50%']

function addRow(tableID) {

			var table = document.getElementById(tableID);

			var rowCount = table.rows.length;
			var row = table.insertRow(rowCount);

			var cell1 = row.insertCell(0);
			var element1 = document.createElement("input");
			element1.type = "text";
			element1.name="form_name[]";
			cell1.appendChild(element1);

			var cell2 = row.insertCell(0);
			cell2.innerHTML = rowCount ;

			var cell3 = row.insertCell(2);
			var element2 = document.createElement("input");
			element2.type = "text";
			element2.name = "mfr[]";
			cell3.appendChild(element2);
			
			var cell4 = row.insertCell(3);
			var element3 = document.createElement("input");
			element3.type = "text";
			element3.name = "instock[]";
			cell4.appendChild(element3);
			
			var cell5 = row.insertCell(4);
			var element4 = document.createElement("input");
			element4.type = "text";
			element4.name = "batch[]";
			cell5.appendChild(element4);
			
			var cell6 = row.insertCell(5);
			var element5 = document.createElement("input");
			element5.type = "text";
			element5.name = "qty[]";
			cell6.appendChild(element5);
			
			var cell7 = row.insertCell(6);
			var element6 = document.createElement("input");
			element6.type = "text";
			element6.name = "free[]";
			cell7.appendChild(element6);
			
			var cell8 = row.insertCell(7);
			var element7 = document.createElement("input");
			element7.type = "text";
			element7.name = "pack[]";
			cell8.appendChild(element7);
			
			var cell9 = row.insertCell(8);
			var element8 = document.createElement("input");
			element8.type = "text";
			element8.name = "date[]";
			cell9.appendChild(element8);
			
			var cell10 = row.insertCell(9);
			var element9 = document.createElement("input");
			element9.type = "number";
			element9.name = "mrp[]";
			cell10.appendChild(element9);
			
			var cell11 = row.insertCell(10);
			var element10 = document.createElement("input");
			element10.type = "text";
			element10.name = "trade[]";
			cell11.appendChild(element10);
			
			var cell12 = row.insertCell(11);
			var element11 = document.createElement("input");
			element11.type = "text";
			element11.name = "discount[]";
			cell12.appendChild(element11);
			
			
			var cell13 = row.insertCell(12);
            //cell13.innerHTML = count;
           // var cell14 = row.insertCell(13);
            var element12 = document.createElement("select");
			element12.name="vat[]";

  //Append the options from the arraylist to the "select" element
  for (var i = 0; i < arraylist.length; i++) {
    var option = document.createElement("option");
    option.value = arraylist[i];
    option.text = arraylist[i];
    element12.appendChild(option);
  }


  cell13.appendChild(element12);



			


		} */

<?php
 if ($alertmsg) {
  echo "alert('" . htmlentities($alertmsg) . "');\n";
 }
?>
</script>
</div>
</body>
</html>
