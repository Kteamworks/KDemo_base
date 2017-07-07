<?php
 // Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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
 require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 // Check authorization.
 $thisauth = acl_check('admin', 'drugs');
 if (!$thisauth) die(xlt('Not authorized'));

// For each sorting option, specify the ORDER BY argument.
//
$ORDERHASH = array(
  'prod' => 'd.name, d.drug_id, di.expiration, di.lot_number',
  'ndc'  => 'd.ndc_number, d.name, d.drug_id, di.expiration, di.lot_number',
  'form' => 'lof.title, d.name, d.drug_id, di.expiration, di.lot_number',
  'lot'  => 'di.lot_number, d.name, d.drug_id, di.expiration',
  'wh'   => 'lo.title, d.name, d.drug_id, di.expiration, di.lot_number',
  'qoh'  => 'di.on_hand, d.name, d.drug_id, di.expiration, di.lot_number',
  'exp'  => 'di.expiration, d.name, d.drug_id, di.lot_number',
);

// Get the order hash array value and key for this request.
$form_orderby = $ORDERHASH[$_REQUEST['form_orderby']] ? $_REQUEST['form_orderby'] : 'prod';
$orderby = $ORDERHASH[$form_orderby];

 // get drugs
 $res = sqlStatement("SELECT d.*, " .
  "di.inventory_id, di.lot_number, di.expiration, di.manufacturer, " .
  "di.on_hand, lo.title " .
  "FROM drugs AS d " .
  "LEFT JOIN drug_inventory AS di ON di.drug_id = d.drug_id " .
  "AND di.destroy_date IS NULL " .
  "LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
  "lo.option_id = di.warehouse_id " .
  "LEFT JOIN list_options AS lof ON lof.list_id = 'drug_form' AND " .
  "lof.option_id = d.form " .
  "ORDER BY $orderby");
?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php echo xlt('Drug Inventory'); ?></title>

<style>
tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
a, a:visited, a:hover { color:#0000cc; }
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">

// callback from add_edit_drug.php or add_edit_drug_inventory.php:
function refreshme() {
 location.reload();
}

// Process click on drug title.
function dodclick(id) {
 dlgopen('add_edit_drug.php?drug=' + id, '_blank', 725, 475);
}

// Process click on drug QOO or lot.
function doiclick(id, lot) {
 dlgopen('add_edit_lot.php?drug=' + id + '&lot=' + lot, '_blank', 600, 475);
}

// Process click on a column header for sorting.
function dosort(orderby) {
 var f = document.forms[0];
 f.form_orderby.value = orderby;
 top.restoreSession();
 f.submit();
 return false;
}

</script>

</head>

<body class="body_top">
<form method='post' action='drug_inventory.php'>

<table width='100%' cellpadding='1' cellspacing='2'>
 <tr class='head'>
  <th title='<?php echo xla('Click to edit'); ?>'>
   
   <?php echo xlt('Name'); ?>
  </th>
  
  <th>
   <?php echo xlt('Mfr'); ?>
  </th>
  
  
   <th>
   <?php echo xlt('Supplier'); ?>
  </th>
  <th>
   <?php echo xlt('Batch'); ?>
  </th>
  
  <th>
   <?php echo xlt('In Stock'); ?>
  </th>
  
    <th>
   <?php echo xlt('Quantity'); ?>
  </th>
  
   
   <th>
   <?php echo xlt('Pack'); ?>
  </th>
  
  <th>
   <?php echo xlt('Expires'); ?>
  </th>
  <th>
   <?php echo xlt('MRP On Pack'); ?>
  </th>
   <th>
   <?php echo xlt('Trade Price'); ?>
  </th>
  
  <th>
   <?php echo xlt('Discount'); ?>
  </th>
  
  <th>
   <?php echo xlt('Vat'); ?>
  </th>
  
  <th>
   <?php echo xlt('Net Value'); ?>
  </th>
  
  
  
 

 </tr>
<?php 
 $lastid = "";
 $encount = 0;
 while ($row = sqlFetchArray($res)) {
  if ($lastid != $row['drug_id']) {
   ++$encount;
   $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");
   $lastid = $row['drug_id'];
   echo " <tr class='detail' bgcolor='$bgcolor'>\n";
   echo "  <td align='center'>" .
    text($row['name']) . "</a></td>\n";
	echo "  <td align='center'>" . text($row['mfr']) . "</td>\n";
  echo "  <td align='center'>" . text($row['supplier']) . "</td>\n";
   echo "  <td align='center'>" . text($row['batch']) . "</td>\n";
    echo "  <td align='center'>" . text($row['inStock']) . "</td>\n";
    echo "  <td align='center'>" . text($row['quantity']) . "</td>\n";
	echo "  <td align='center'>" . text($row['pack']) . "</td>\n";
  
   echo "  <td align='center'>" . text($row['expdate']) . "</td>\n";
    echo "  <td align='center'>" . text($row['mrp']) . "</td>\n";
	echo "  <td align='center'>" . text($row['tradePrice']) . "</td>\n";
	echo "  <td align='center'>" . text($row['discount']) . "</td>\n";
	echo "  <td align='center'>" . text($row['vat']) . "</td>\n";
	echo "  <td align='center'>" . text($row['totalValue']) . "</td>\n";
	
  
  
  } else {
   echo " <tr class='detail' bgcolor='$bgcolor'>\n";
   echo "  <td colspan='7'>&nbsp;</td>\n";
  }
 /* if (!empty($row['inventory_id'])) {
   echo "  <td onclick='doiclick(" . attr($lastid) . "," . attr($row['inventory_id']) . ")'>" .
    "<a href='' onclick='return false'>" . text($row['lot_number']) . "</a></td>\n";
   echo "  <td>" . text($row['title']) . "</td>\n";
   echo "  <td>" . text($row['on_hand']) . "</td>\n";
   echo "  <td>" . text(oeFormatShortDate($row['expiration'])) . "</td>\n";
  } else {
   echo "  <td colspan='4'>&nbsp;</td>\n";
  } */
  echo " </tr>\n";
 } // end while
?>
</table>

<center><p>
 <input type='button' value='<?php echo xla('Add Drug'); ?>' onclick='dodclick(0)'/>
</p></center>

<input type="hidden" name="form_orderby" value="<?php echo attr($form_orderby) ?>" />

</form>
</body>
</html>
