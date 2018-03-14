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

function display_desc($desc) {
  if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
    $desc = $matches[1];
  }
  return $desc;
}

function thisLineItem($patient_id, $encounter_id, $rowcat, $description, $transdate, $qty,$payout, $amount, $irnumber='') {
  global $product, $category, $producttotal, $productqty, $cattotal, $catqty,$catpayout,$grandhospayout,$granddocpayout, $grandtotal, $grandqty,$totcatpayout;
  global $productleft, $catleft,$catsubpayout;

  $invnumber = $irnumber ? $irnumber : "$patient_id.$encounter_id";
  $rowamount = sprintf('%01.2f', $amount);
  $catpayout= sprintf('%01.2f', $payout);
  if (empty($rowcat)) $rowcat = 'None';
  $rowproduct = $description;
  if (! $rowproduct) $rowproduct = 'Unknown';

  if ($product != $rowproduct || $category != $rowcat) {
    if ($product) {
      // Print product total.
      if ($_POST['form_csvexport']) {
        if (! $_POST['form_details']) {
          echo '"' . display_desc($category) . '",';
          echo '"' . display_desc($product)  . '",';
          echo '"' . $productqty             . '",';
          echo '"'; bucks($producttotal); echo '"' . "\n";
        }
      }
      else {
?>
 <!--<tr bgcolor="#ddddff">
  <td class="detail">
   <!--?php echo display_desc($catleft); $catleft = "&nbsp;"; ?>
  </td>
  <td class="detail" colspan="3">
   <!--?php if ($_POST['form_details']) echo xl('Total for') . ' '; echo display_desc($product); ?>
  </td>
  <td align="right">
   <!--?php echo $productqty; ?>
  </td>
   <td align="right">
   <!--?php echo  bucks($catsubpayout); ?>
  </td>
   <td align="right">
   <!--?php echo bucks($producttotal-$catsubpayout); ?>
  </td>
  <td align="right">
   <!--?php bucks($producttotal); ?>
  </td>
 </tr> -->
<?php
      } // End not csv export
    }
    $producttotal = 0;
    $productqty = 0;
	$catsubpayout=0;
    $product = $rowproduct;
    $productleft = $product;
  }

  if ($category != $rowcat) {
    if ($category) {
      // Print category total.
      if (!$_POST['form_csvexport']) {
?>

<tr bgcolor="#E5E4E2">
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" colspan="5">
  
  </td>
  
 </tr> 
<?php
      } // End not csv export
    }
    $cattotal = 0;
    $catqty = 0;
    $category = $rowcat;
    $catleft = $category;
	//$totcatpayout=0;
  }

  if ($_POST['form_details']) {
    if ($_POST['form_csvexport']) {
      echo '"' . display_desc($category ) . '",';
      echo '"' . display_desc($product  ) . '",';
      echo '"' . oeFormatShortDate(display_desc($transdate)) . '",';
      echo '"' . display_desc($invnumber) . '",';
	  
      echo '"' . display_desc($qty      ) . '",';
	  echo '"' . bucks($catpayout      ) . '",';
	  echo '"' . bucks($rowamount-$catpayout      ) . '",';
      echo '"'; bucks($rowamount); echo '"' . "\n";
    }
    else {
?>

 <tr>
  <td class="detail">
   <?php echo display_desc($catleft); $catleft = "&nbsp;"; ?>
  </td>
  <td class="detail" >
   <?php echo display_desc($productleft); ?>
  </td>
  <td align="right">
   <?php echo oeFormatShortDate($transdate); ?>
  </td>
  <td align="right">
  
  <?php  $tmp = sqlQuery("SELECT fname,lname FROM patient_data WHERE " .
            "pid = '$patient_id' ".
            ""); 
			$name=$tmp['fname'].' '.$tmp['lname'];?>
   <a href='../patient_file/pos_checkout.php?ptid=<?php echo $patient_id; ?>&enc=<?php echo $encounter_id; ?>'>
   <?php echo $name ?></a>
  </td>
 
  <td align="right">
  <!-- <?php bucks($rowamount); ?>-->
  </td>
 </tr>
<?php

    } // End not csv export
  } // end details
  $producttotal += $rowamount;
  $catsubpayout += $catpayout; 
  $cattotal     += $rowamount;
  $grandtotal   += $rowamount;
  $totcatpayout += $catpayout;
  $productqty   += $qty;
  $catqty       += $qty;
  $grandqty     += $qty;
  $grandhospayout+=$totcatpayout;
} // end function

  if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

  $INTEGRATED_AR = $GLOBALS['oer_config']['ws_accounting']['enabled'] === 2;

  if (!$INTEGRATED_AR) SLConnect();

  $form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
  $form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
  $form_facility  = $_POST['form_facility'];
  $docuser  = $_POST['doc'];
  

  if ($_POST['form_csvexport']) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=sales_by_item.csv");
    header("Content-Description: File Transfer");
    // CSV headers:
    if ($_POST['form_details']) {
      echo '"Category",';
      echo '"Item",';
      echo '"Date",';
      echo '"Bill No.",';
     // echo '"Qty",';
	 // echo '"Doctor Chrg",';
	 // echo '"Hospital Chrg",';
     // echo '"Amount"' . "\n";
    }
    else {
      echo '"Category",';
      echo '"Item",';
      echo '"Qty",';
	  echo '"Doctor Chrg",';
	  echo '"Hospital Chrg",';
      echo '"Total"' . "\n";
    }
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

<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
<script language="JavaScript">
$(document).ready(function(){
//$("tr td:nth-child(5)").css("display","none");
//$("tr td:nth-child(6)").css("display","none");
//$("tr td:nth-child()").css("display","none");


});
</script>







<title><?php xl('Doctor Charges IP & OP','e') ?></title>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">

<span class='title'><?php xl('Registered Patients','e'); ?>  <?php xl('','e'); ?></span>

<form method='post' action='RegPat.php' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <td width='630px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<!--<td class='label'>
				<!--?php xl('Facility','e'); ?>:
			</td>
		<!--	<td>
			<!--?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', true); ?>
			</td> -->
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
			
			 <?php $qdoc = "SELECT id, username, fname, lname FROM users WHERE authorized != 0 AND active = 1"; 
			 $redoc= sqlStatement($qdoc); ?>
			
		
			
			
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			   <input type='checkbox' name='form_details'<?php  if ($form_details) echo ' checked'; ?> checked>
			   <?php  xl('Details','e'); ?>
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
  
  <th align="left">
   <?php xl('S.NO.','e'); ?>
  </th>
  
  <th align="left">
   <?php xl('','e'); ?>
  </th>
 
 <th align="right">
   <?php xl('Date','e'); ?>
  </th>
   <th align="right">
   <?php xl('Patient Name','e'); ?>
  </th>
 
  <!--<th align="left">
   <?php xl('Referred To','e'); ?>
  </th>-->
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
    $totcatpayout=0;
	$catsubpayout=0;
    if ($INTEGRATED_AR) {
		
		
		//where b.servicegrp_id=c.code_type AND b.activity = 1 AND b.fee != 0 and b.activity=1 and b.servicegrp_id=8 group by b.encounter,b.code_text order by fe.encounter_ipop;
		
			  $query = "SELECT  * FROM patient_data 
where date >= '$from_date 00:00:00' AND date <= '$to_date 23:59:59' " ;
		 
		
      if ($form_facility) {
       // $query .= " AND fe.facility_id = '$form_facility'";
      }
      $query .= " ORDER BY date desc";
      //group by fe.encounter_ipop,b.code_text,fe.encounter 
        //    ORDER BY b.code, IPOP
      $res = sqlStatement($query);
	  $i=1;
      while ($row = sqlFetchArray($res)) {
		  
		  $des = $row['code_text'];
		  
		  if (strpos($des,DR) !== false) {
                $des = 'Consultation';
  }
		  
        thisLineItem($row['pid'], $row['encounter'],
          $i, $row['code1'] . ' ' . $des,
          substr($row['date'], 0, 10), $row['units'], $row['payout'],$row['fee'], $row['invoice_refno']);
		  $i++;
      }
      //
      /* $query = "SELECT s.sale_date, s.fee, s.quantity, s.pid, s.encounter, " .
        "d.name, fe.date, fe.facility_id, fe.invoice_refno " .
        "FROM drug_sales AS s " .
        "JOIN drugs AS d ON d.drug_id = s.drug_id " .
        "JOIN form_encounter AS fe ON " .
        "fe.pid = s.pid AND fe.encounter = s.encounter AND " .
        "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' " .
        "WHERE s.fee != 0"; */
      // If a facility was specified.
      if ($form_facility) {
        $query .= " AND fe.facility_id = '$form_facility'";
      }
      //$query .= " ORDER BY d.name, fe.date, fe.id";
      //
    /*   $res = sqlStatement($query);
      while ($row = sqlFetchArray($res)) {
        thisLineItem($row['pid'], $row['encounter'], xl('Products'), $row['name'],
          substr($row['date'], 0, 10), $row['quantity'], $row['fee'], $row['invoice_refno']);
      } */
    }
    else {
      $query = "SELECT ar.invnumber, ar.transdate, " .
        "invoice.description, invoice.qty, invoice.sellprice " .
        "FROM ar, invoice WHERE " .
        "ar.transdate >= '$from_date' AND ar.transdate <= '$to_date' " .
        "AND invoice.trans_id = ar.id " .
        "ORDER BY invoice.description, ar.transdate, ar.id";
      $t_res = SLQuery($query);
      if ($sl_err) die($sl_err);
      for ($irow = 0; $irow < SLRowCount($t_res); ++$irow) {
        $row = SLGetRow($t_res, $irow);
        list($patient_id, $encounter_id) = explode(".", $row['invnumber']);
        // If a facility was specified then skip invoices whose encounters
        // do not indicate that facility.
        if ($form_facility) {
          $tmp = sqlQuery("SELECT count(*) AS count FROM form_encounter WHERE " .
            "pid = '$patient_id' AND encounter = '$encounter_id' AND " .
            "facility_id = '$form_facility'");
          if (empty($tmp['count'])) continue;
        }
        thisLineItem($patient_id, $encounter_id, '', $row['description'],
          $row['transdate'], $row['qty'], $row['sellprice'] * $row['qty']);
      } // end for
    } // end not $INTEGRATED_AR

    if ($_POST['form_csvexport']) {
      if (! $_POST['form_details']) {
        echo '"' . display_desc($product) . '",';
        echo '"' . $productqty            . '",';
        echo '"'; bucks($producttotal); echo '"' . "\n";
      }
    }
    else {
?>

 <!--<tr bgcolor="#ddddff">
  <td class="detail">
   <!--?php echo display_desc($catleft); $catleft = "&nbsp;"; ?>
  </td>
  <td class="detail" colspan="3">
   <!--?php if ($_POST['form_details']) echo xl('Total for') . ' '; echo display_desc($product); ?>
  </td>
  <td align="right">
   <!--?php echo $productqty; ?>
  </td>
  <td align="right">
   <!--?php //echo $productqty; ?>
  </td>
  <td align="right">
   <!--?php //echo $productqty; ?>
  </td>
  <td align="right">
   <!--?php bucks($producttotal); ?>
  </td>
 </tr> -->

 <!--<tr bgcolor="#ffdddd">
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" colspan="3">
   <!--?php echo xl('Total for category') . ' '; echo display_desc($category); ?>
  </td>
  <td align="right">
   <!--?php echo $catqty; ?>
  </td>
  <td align="right" >
   <!--?php echo bucks($totcatpayout); ?>
  </td>
  <td align="right" >
   <!--?php echo bucks($cattotal-$totcatpayout); ?>
  </td>
  <td align="right">
   <!--?php bucks($cattotal); ?>
  </td>
 </tr> -->

<!--<tr>
  <td class="detail" colspan="4">
   <!--?php xl('Grand Total','e'); ?>
  </td>
  <td align="right" >
   <!--?php echo $grandqty; ?>
  </td>
  <td align="right" >
   <!--?php  ?>
  </td>
  <td align="right" >
   <!--?php //echo $grandqty; ?>
  </td>
  <td align="right">
   <!--?php bucks($grandtotal); ?>
  </td>
 </tr> -->

<?php

    } // End not csv export
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
