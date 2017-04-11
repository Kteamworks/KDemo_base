<?php
 // Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 // This report lists front office receipts for a given date range.

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/options.inc.php");

 // Prepare a string for CSV export.
function qescape($str) {
  $str = str_replace('\\', '\\\\', $str);
  return str_replace('"', '\\"', $str);
} 

// $from_date = fixDate($_POST['form_from_date'], date('Y-m-d '));
// $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

$from_date = date('Y-m-d H:i:s', strtotime($_POST['form_from_date']));
$to_date = date('Y-m-d H:i:s', strtotime($_POST['form_to_date']));

//date('Y-m-d H:i:s', strtotime($_POST['intake_date']));
 
 function bucks($amt) {
  return ($amt != 0.00) ? oeFormatMoney($amt) : '';
 }
  if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=front.csv");
  header("Content-Description: File Transfer");
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Front Office Receipts','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 // The OnClick handler for receipt display.
 function show_receipt(pid,timestamp) {
  dlgopen('../patient_file/front_payment.php?receipt=1&patient=' + pid +
   '&time=' + timestamp, '_blank', 550, 400);
 }

</script>

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
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
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Front Office Receipts','e'); ?></span>

<div id="report_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<form name='theform' method='post' action='front_receipts_report.php' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='410px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
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
			<td class='label'>
			   <?php xl('User','e'); ?>:
			</td>
			<td>
			<select name='usr' id='usr'>
		    <option value='all' selected>All</option>
			<?php 
			
			$ures = sqlStatement("SELECT distinct user FROM payments where user is not null");

while ($urow = sqlFetchArray($ures)) {
  $uname = $urow['user'];
  //if ($urow['fname']) $uname .= ", " . $urow['fname'];
  echo " <option value='" . $urow['user'] . "'";
  echo ">$uname</option>\n";
  //$optto .= $tmp1 . $tmp2;
  //echo $optto;
  //if ($urow['id'] == $_SESSION['authUserID']) $tmp1 .= " selected";
 // $optfrom .= $tmp1 . $tmp2;
 // $ulist .= "ulist[$i] = '" . addslashes($uname) . "|" .
 ////   $urow['id'] . "|" . addslashes($urow['specialty']) . "';\n";
//  ++$i;
}
			
						
			
			
			
			?>
		</select>	</td>
		</tr>
	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
					<span>
						<?php xl('Submit','e'); ?>
					</span>
					</a>

					<?php if ($_POST['form_refresh']) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php xl('Print','e'); ?>
						</span>
					</a>
					<a href='javascript:;' class='css_button' onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
	                          <span><?php xl('CSV Export','e'); ?></span>
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
} // end not form_csvexport
 if ($_POST['form_refresh'] || $_POST['form_orderby']|| $_POST['form_csvexport']) {
	 if ($_POST['form_csvexport']) {
    // CSV headers:
    echo '"' . xl('Last Visit') . '",';
    echo '"' . xl('First') . '",';
    echo '"' . xl('Middle') . '",';
    echo '"' . xl('Last') . '",';
    echo '"' . xl('ID') . '",';
    echo '"' . xl('Method') . '",';
    echo '"' . xl('Source') . '",';
    echo '"' . xl('Today') . '",';
    echo '"' . xl('Previous') . '",';
    echo '"' . xl('Total') . '"'."\n";
  }else {
?>
<div id="report_results">
<table>
 <thead>
  <th> <?php xl('Time','e'); ?> </th>
  <th> <?php xl('Receipt No','e'); ?></th>
  <th> <?php xl('Patient','e'); ?> </th>
  <th> <?php xl('MED NO.','e'); ?> </th>
  <th> <?php xl('Method','e'); ?> </th>
  <th> <?php xl('Source','e'); ?> </th>
  <th align='right'> <?php xl('Today','e'); ?> </th>
  <th align='right'> <?php xl('Previous','e'); ?> </th>
  <th align='right'> <?php xl('Total','e'); ?> </th>
 </thead>
 <tbody>
<?php
  }
 if (true || $_POST['form_refresh']) {
  $total1 = 0.00;
  $total2 = 0.00;
$usr=$_POST['usr'];
if ($usr=='all')
{
	$cod=' ';
}
 else{
	 $cod=" AND r.user='$usr' ";
 }
  $query = "SELECT r.pid, r.dtime,r.receipt_id, " .
    "SUM(r.amount1) AS amount1, " .
    "SUM(r.amount2) AS amount2, " .
    "MAX(r.method) AS method, " .
    "MAX(r.source) AS source, " .
    "MAX(r.user) AS user, " .
    "p.fname, p.mname, p.lname, p.pubpid,p.genericname1 " .
    "FROM payments AS r " .
    "LEFT OUTER JOIN patient_data AS p ON " .
    "p.pid = r.pid " .
    "WHERE " .
	 "r.activity=1 AND " .
    "r.dtime >= '$from_date' AND " .
    "r.dtime <= '$to_date'" .
	 $cod.
    "GROUP BY r.dtime, r.pid,r.encounter ORDER BY r.dtime, r.pid,r.encounter";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query);

  while ($row = sqlFetchArray($res)) {
    // Make the timestamp URL-friendly.
    $timestamp = preg_replace('/[^0-9]/', '', $row['dtime']);
		if ($_POST['form_csvexport']) {
    echo '"' . oeFormatShortDate(substr($row['dtime'], 0, 10)) . '",';
    echo '"' . qescape($row['fname']) . '",';
    echo '"' . qescape($row['mname']) . '",';
    echo '"' . qescape($row['lname']) . '",';
    echo '"' . qescape($row['genericname1']) . '",';
    echo '"' . qescape($row['method']) . '",';
    echo '"' . qescape($row['source']) . '",';
    echo '"' . oeFormatMoney($row['amount1']) . '",';
    echo '"' . oeFormatMoney($row['amount2']) . '",';
    echo '"' . oeFormatMoney($row['amount1']+$row['amount2']) . '"'."\n";
   }
   else {
?>
 <tr>
  <td nowrap>
   <a href="javascript:show_receipt(<?php echo $row['pid'] . ",'$timestamp'"; ?>)">
   <?php echo oeFormatShortDate(substr($row['dtime'], 0, 10)) . substr($row['dtime'], 10, 6); ?>
   </a>
  </td>
  <td>
   <?php echo $row['receipt_id'] ?>
  </td>
  <td>
   <?php echo $row['fname'] . '  ' . $row['lname'] . ' ' . $row['mname'] ?>
  </td>
 
  <td>
   <?php echo $row['genericname1'] ?>
  </td>
  <td>
   <?php echo $row['method'] ?>
  </td>
  <td>
   <?php echo $row['source'] ?>
  </td>
  <td align='right'>
   <?php echo bucks($row['amount1']) ?>
  </td>
  <td align='right'>
   <?php echo bucks($row['amount2']) ?>
  </td>
  <td align='right'>
   <?php echo bucks($row['amount1'] + $row['amount2']) ?>
  </td>
 </tr>
<?php
 } // end not export
    $total1 += $row['amount1'];
    $total2 += $row['amount2'];
	
  }
  if(!$_POST['form_csvexport'])
  {
?>

 <tr>
  <td colspan='8'>
   &nbsp;
  </td>
 </tr>

 <tr class="report_totals">
  <td colspan='6'>
   <?php xl('Totals','e'); ?>
  </td>
  <td align='right'>
   <?php echo bucks($total1) ?>
  </td>
  <td align='right'>
   <?php echo bucks($total2) ?>
  </td>
  <td align='right'>
   <?php echo bucks($total1 + $total2) ?>
  </td>
 </tr>


</tbody>
</table>
</div> <!-- end of results -->
<?php
 }
 }
?>
<?php } else { 
if(!$_POST['form_csvexport']){?>
<div class='text'>
 	<?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php }}if(!$_POST['form_csvexport']){ ?>

</form>
</body>
<?php
 }
 if(!$_POST['form_csvexport'])
 {
?>
<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_from_date", showsTime:true});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_to_date", showsTime:true});
</script>
</html>
 <?php }?>
