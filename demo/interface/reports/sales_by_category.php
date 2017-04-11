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
  $form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
  $form_facility  = $_POST['form_facility'];

  if ($_POST['form_csvexport']) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=sales_by_category.csv");
    header("Content-Description: File Transfer");
    // CSV headers:
 
    // CSV headers:
    echo '"' . xl('Category') . '",';
    echo '"' . xl('Patients') . '",';
    echo '"' . xl('Encounters') . '",';
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

<title><?php xl('Sales by Category','e') ?></title>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Sales by Category','e'); ?></span>

<form method='post' action='sales_by_category.php' id='theform'>

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
			   <?php xl('From','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>
			<td class='label'>
			   <?php xl('To','e'); ?>:
			</td>
			<td>
			   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'
				onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
			   <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
				id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
				title='<?php xl('Click here to choose a date','e'); ?>'>
			</td>
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
<div id="report_results">
<table >
 <thead>
  <th>
   <?php xl('Category','e'); ?>
  </th>
  <th align="right">
   <?php xl('Patients','e'); ?>
  </th>
  <th align="right">
   <?php xl('Encounters','e'); ?>
  </th>
 
  <th align="right">
   <?php xl('Amount','e'); ?>
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
      $query = "SELECT b.date,sum(b.fee) fee, count(distinct b.pid) pid, count(distinct b.encounter) encounter, b.code_type, b.code, b.units, " .
        "b.code_text, fe.date, fe.pc_catid,fe.facility_id, fe.invoice_refno,op.pc_catname " .
        "FROM billing AS b " .
        "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
		"JOIN openemr_postcalendar_categories AS op ON op.pc_catid = fe.pc_catid ".
        
        "WHERE b.code not in ('INSURANCE DIFFERENCE AMOUNT','INSURANCE CO PAYMENT') AND b.activity = 1 AND b.fee != 0 AND " .
        "b.date >= '$from_date 00:00:00' AND b.date <= '$to_date 23:59:59' ";
		
      // If a facility was specified.
      if ($form_facility) {
        $query .= " AND fe.facility_id = '$form_facility'";
      }
      $query .= " GROUP BY fe.pc_catid";
      //
      $res = sqlStatement($query);
      while ($row = sqlFetchArray($res)) {
		  	if ($_POST['form_csvexport']) {
   
    echo '"' . qescape($row['pc_catname']) . '",';
    echo '"' . qescape($row['pid']) . '",';
    echo '"' . qescape($row['encounter']) . '",';
    echo '"' . oeFormatMoney($row['fee']) . '"'."\n";
   } else{?>
	  
		   <tr bgcolor="#ddddff">
  <td class="detail">
   <?php echo display_desc($row['pc_catname']) ?>
  </td>
  <td align="right">
   <?php echo display_desc($row['pid']); ?>
  </td>
  <td align="right">
   <?php echo $row['encounter']; ?>
  </td>
  <td align="right">
   <?php bucks($row['fee']); ?>
  </td>
 </tr>
       
     <?php
 $total1 += $row['fee']; 	
 }
	  }     
  
    }
    if (! $_POST['form_csvexport']){
?>



 <tr>
  <td class="detail" colspan="2">
   <?php xl('Grand Total','e'); ?>
  </td>
  <td align="detail">
  </td>
  <td align="right">
   <?php bucks($total1); ?>
  </td>
 </tr>

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
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</html>
<?php
  } // End not csv export
?>
