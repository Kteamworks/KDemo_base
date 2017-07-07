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
<?php html_header_show(); ?>
<title><?php echo $drug_id ? xlt("Edit") : xlt("Add New"); echo ' ' . xlt('Drug'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>
td { font-size:10pt; }

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
//
if ($_POST['form_save']) {
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
     "mfr = '"     . escapedff('mfr')    . "', " .
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
   /* if (acl_check('admin', 'super')) {
     sqlStatement("DELETE FROM drug_inventory WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drug_templates WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM drugs WHERE drug_id = ?", array($drug_id));
     sqlStatement("DELETE FROM prices WHERE pr_id = ? AND pr_selector != ''", array($drug_id));
    } */
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
     sqlInsert("INSERT INTO drug_templates ( " .
      "drug_id, selector, dosage, period, quantity, refills, taxrates " .
      ") VALUES ( ?, ?, ?, ?, ?, ?, ? )",
      array($drug_id, $selector, trim($iter['dosage']), trim($iter['period']),
      trim($iter['quantity']), trim($iter['refills']), $taxrates));

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
   sqlStatement("DELETE FROM product_warehouse WHERE pw_drug_id = ?", array($drug_id));
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
   echo " window.location.href='add_edit_lot.php?drug=$drug_id&lot=0'\n";
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
    'cyp_factor' => '',
    'related_code' => '',
  );
}
?>

<form method='post' name='theform' action='add_edit_drug.php?drug=<?php echo $drug_id; ?>'>
<center>

<table  width='60%'  cellspacing="10" style="border: 1px solid black;">

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Name'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='name' maxlength='80' value='<?php echo attr($row['name']) ?>' style='width:100%' />
  </td>
 </tr>
 
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Manufacturer'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='mfr' maxlength='80' value='<?php echo attr($row['mfr']) ?>' style='width:100%' />
  </td>
 </tr>
 
  <tr>
  <td valign='top' nowrap><b><?php echo xlt('In Stock'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='instock' maxlength='80' value='<?php echo attr($row['inStock']) ?>' style='width:100%' />
  </td>
 </tr>
 
  <tr>
  <td valign='top' nowrap><b><?php echo xlt('Batch'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='batch' maxlength='80' value='<?php echo attr($row['batch']) ?>' style='width:100%' />
  </td>
 </tr>
 
 
  <tr>
  <td valign='top' nowrap><b><?php echo xlt('Quantity'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='qty' maxlength='80' value='<?php echo attr($row['quantity']) ?>' style='width:100%' />
  </td>
 </tr>
 
  <tr>
  <td valign='top' nowrap><b><?php echo xlt('Free'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='free' maxlength='80' value='<?php echo attr($row['free']) ?>' style='width:100%' />
  </td>
 </tr>
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Pack'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='pack' maxlength='80' value='<?php echo attr($row['pack']) ?>' style='width:100%' />
  </td>
 </tr>

 
  <tr>
  <td valign='top' nowrap><b><?php echo xlt('Date'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='date' maxlength='80' value='<?php echo attr($row['expdate']) ?>' style='width:100%' />
  </td>
 </tr>
 
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('M.R.P.'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='mrp' maxlength='80' value='<?php echo attr($row['mrp']) ?>' style='width:100%' />
  </td>
 </tr>
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Trade Price'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='trade' maxlength='80' value='<?php echo attr($row['tradePrice']) ?>' style='width:100%' />
  </td>
 </tr>
 
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Discount'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='discount' maxlength='80' value='<?php echo attr($row['discount']) ?>' style='width:100%' />
  </td>
 </tr>
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Vat'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='vat' maxlength='80' value='<?php echo attr($row['vat']) ?>' style='width:100%' />
  </td>
 </tr>
 
 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Net Value'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='total' maxlength='80' value='<?php echo attr($row['totalValue']) ?>' style='width:100%' />
  </td>
 </tr>

</table>
  </td>
 </tr>

</table>

<p>
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

<script language="JavaScript">
<?php
 if ($alertmsg) {
  echo "alert('" . htmlentities($alertmsg) . "');\n";
 }
?>
</script>

</body>
</html>
